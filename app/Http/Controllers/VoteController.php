<?php

namespace App\Http\Controllers;

use App\Http\Requests\VoteRequest;
use App\Models\Audience;
use App\Models\Award;
use App\Models\Category;
use App\Models\Company;
use App\Models\Sponsor;
use App\Models\Vote;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    public function index(Request $request)
    {
        $audienceId = session('audience_id');
        $audience = $audienceId ? Audience::find($audienceId) : null;

        $categories = Category::open()->with('companies')->withCount(['votes' => fn ($query) => $query->forYear(\App\Models\LandingPageSection::campaignYear())])->orderBy('votes_count', 'asc')->get();
        $awards = Award::currentYear()->where('is_active', true)->get();
        $awards = $awards->filter(fn (Award $award) => $award->hasRemainingQuantity() && $award->requiredVotes() > 0);
        $minimumPrizeVotes = $awards->min(fn (Award $award) => $award->requiredVotes());
        $prizeProgress = $awards->mapWithKeys(fn (Award $award) => [
            $award->id => $audience ? $award->qualifyingVotes()->where('audience_id', $audience->id)->distinct()->count('category_id') : 0,
        ]);
        $sponsors = Sponsor::active()->ordered()->get();

        if ($audience) {
            $votedCategoryIds = $audience->votes()->forYear(\App\Models\LandingPageSection::campaignYear())->pluck('category_id')->toArray();
        } else {
            $votedCategoryIds = [];
        }

        return view('vote.index', compact('categories', 'votedCategoryIds', 'audience', 'awards', 'sponsors', 'minimumPrizeVotes', 'prizeProgress'));
    }

    public function show(Request $request, Category $category)
    {
        abort_unless($category->is_active, 404);
        $category->load(['companies' => fn ($query) => $query
            ->when(\App\Models\LandingPageSection::campaignOption('randomize_companies'),
                fn ($query) => $query->inRandomOrder(),
                fn ($query) => $query->orderBy('trade_name')->orderBy('legal_name'))]);

        $audienceId = session('audience_id');
        $audience = $audienceId ? Audience::find($audienceId) : null;

        $userVote = null;
        if (! $audience) {
            session(['voting_category_slug' => $category->slug]);
        }
        $nextCategory = null;
        $totalCategories = 0;
        $votedCount = 0;

        if ($audience) {
            $userVote = Vote::where('audience_id', $audience->id)
                ->forYear(\App\Models\LandingPageSection::campaignYear())
                ->where('category_id', $category->id)
                ->first();

            // Progresso de votação
            $votedCategoryIds = $audience->votes()->forYear(\App\Models\LandingPageSection::campaignYear())->pluck('category_id')->toArray();
            $openCategories = Category::open()->withCount(['votes' => fn ($query) => $query->forYear(\App\Models\LandingPageSection::campaignYear())])->orderBy('votes_count', 'asc')->get();
            $totalCategories = $openCategories->count();
            $votedCount = $openCategories->whereIn('id', $votedCategoryIds)->count();

            // Próxima categoria não votada
            $nextCategory = $openCategories
                ->whereNotIn('id', $votedCategoryIds)
                ->where('id', '!=', $category->id)
                ->first();
        }

        $highlightCompanyId = $request->query('ref');

        return view('vote.show', compact(
            'category', 'audience', 'userVote', 'highlightCompanyId',
            'nextCategory', 'totalCategories', 'votedCount'
        ));
    }

    public function store(VoteRequest $request, Category $category, Company $company)
    {
        $audienceId = session('audience_id');

        Vote::create([
            'audience_id' => $audienceId,
            'category_id' => $category->id,
            'company_id' => $company->id,
            'ip_hash' => hash('sha256', $request->ip()),
            'user_agent' => $request->userAgent(),
        ]);

        // Buscar próxima categoria ainda não votada
        $votedCategoryIds = Vote::where('audience_id', $audienceId)
            ->forYear(\App\Models\LandingPageSection::campaignYear())
            ->pluck('category_id')
            ->toArray();

        $nextCategory = Category::open()
            ->whereNotIn('id', $votedCategoryIds)
            ->withCount(['votes' => fn ($query) => $query->forYear(\App\Models\LandingPageSection::campaignYear())])
            ->orderBy('votes_count', 'asc')
            ->first();

        if ($nextCategory) {
            return redirect()->route('vote.show', $nextCategory)
                ->with('success', 'Voto registrado em ' . $category->name . '! Agora vote nesta categoria.');
        }

        return redirect()->route('vote.show', $category)
            ->with('success', 'Seu voto foi registrado com sucesso!');
    }

    public function company(Request $request, Company $company)
    {
        $company->load('categories', 'votes.category');

        $audienceId = session('audience_id');
        $audience = $audienceId ? Audience::find($audienceId) : null;

        // Contar votos por categoria
        $votesByCategory = $company->votes()
            ->with('category')
            ->get()
            ->groupBy('category_id')
            ->map(function ($votes) {
                return [
                    'category' => $votes->first()->category,
                    'count' => $votes->count()
                ];
            });

        return view('vote.company', compact('company', 'audience', 'votesByCategory'));
    }
}

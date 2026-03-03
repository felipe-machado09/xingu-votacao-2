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

        $categories = Category::open()->with('companies')->get();
        $awards = Award::where('is_active', true)->get();
        $sponsors = Sponsor::active()->ordered()->get();

        if ($audience) {
            $votedCategoryIds = $audience->votes()->pluck('category_id')->toArray();
        } else {
            $votedCategoryIds = [];
        }

        return view('vote.index', compact('categories', 'votedCategoryIds', 'audience', 'awards', 'sponsors'));
    }

    public function show(Request $request, Category $category)
    {
        $category->load('companies', 'winner.company');

        $audienceId = session('audience_id');
        $audience = $audienceId ? Audience::find($audienceId) : null;

        $userVote = null;
        $nextCategory = null;
        $totalCategories = 0;
        $votedCount = 0;

        if ($audience) {
            $userVote = Vote::where('audience_id', $audience->id)
                ->where('category_id', $category->id)
                ->first();

            // Progresso de votação
            $votedCategoryIds = $audience->votes()->pluck('category_id')->toArray();
            $openCategories = Category::open()->orderBy('name')->get();
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
            ->pluck('category_id')
            ->toArray();

        $nextCategory = Category::open()
            ->whereNotIn('id', $votedCategoryIds)
            ->orderBy('name')
            ->first();

        if ($nextCategory) {
            return redirect()->route('vote.show', $nextCategory)
                ->with('success', 'Voto registrado em "' . $category->name . '"! Agora vote nesta categoria.');
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

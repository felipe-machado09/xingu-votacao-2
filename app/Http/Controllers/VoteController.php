<?php

namespace App\Http\Controllers;

use App\Http\Requests\VoteRequest;
use App\Models\Audience;
use App\Models\Category;
use App\Models\Company;
use App\Models\Vote;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    public function index(Request $request)
    {
        $audienceId = session('audience_id');
        $audience = $audienceId ? Audience::find($audienceId) : null;

        $categories = Category::open()->with('companies')->get();

        if ($audience) {
            $votedCategoryIds = $audience->votes()->pluck('category_id')->toArray();
        } else {
            $votedCategoryIds = [];
        }

        return view('vote.index', compact('categories', 'votedCategoryIds', 'audience'));
    }

    public function show(Request $request, Category $category)
    {
        $category->load('companies', 'winner.company');

        $audienceId = session('audience_id');
        $audience = $audienceId ? Audience::find($audienceId) : null;

        $userVote = null;
        if ($audience) {
            $userVote = Vote::where('audience_id', $audience->id)
                ->where('category_id', $category->id)
                ->first();
        }

        $highlightCompanyId = $request->query('ref');

        return view('vote.show', compact('category', 'audience', 'userVote', 'highlightCompanyId'));
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

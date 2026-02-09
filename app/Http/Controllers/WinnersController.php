<?php

namespace App\Http\Controllers;

use App\Models\Audience;
use App\Models\Category;
use App\Models\CategoryWinner;
use App\Models\Company;
use App\Models\Sponsor;
use App\Models\Vote;
use Illuminate\Http\Request;

class WinnersController extends Controller
{
    public function index(Request $request)
    {
        $audienceId = session('audience_id');
        $audience = $audienceId ? Audience::find($audienceId) : null;

        // Filtros
        $year = $request->get('year', date('Y'));
        $categoryId = $request->get('category');
        $search = $request->get('search');

        // Buscar vencedores com filtros
        $query = CategoryWinner::with(['category', 'company'])
            ->where('year', $year)
            ->whereHas('category', function ($q) {
                $q->where('is_active', true);
            });

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($search) {
            $query->whereHas('company', function ($q) use ($search) {
                $q->where('legal_name', 'like', "%{$search}%")
                  ->orWhere('trade_name', 'like', "%{$search}%");
            });
        }

        $categoriesWithWinners = $query->get();

        // Se houver informação de votos, adicionar ao resultado
        foreach ($categoriesWithWinners as $winner) {
            $winner->votes_count = $winner->company->votes()
                ->where('category_id', $winner->category_id)
                ->count();
        }

        // Estatísticas
        $totalVotes = Vote::whereYear('created_at', $year)->count();
        $totalCompanies = Company::count();
        $totalCategories = Category::where('is_active', true)->count();

        // Anos disponíveis
        $availableYears = CategoryWinner::selectRaw('DISTINCT year')
            ->orderBy('year', 'desc')
            ->pluck('year');

        // Categorias para filtro
        $categories = Category::where('is_active', true)
            ->orderBy('name')
            ->get();

        // Patrocinadores
        $sponsors = Sponsor::active()->ordered()->get();

        $winners = $categoriesWithWinners;

        return view('winners', compact(
            'winners',
            'categoriesWithWinners',
            'audience',
            'totalVotes',
            'totalCompanies',
            'totalCategories',
            'availableYears',
            'categories',
            'sponsors',
            'year',
            'categoryId',
            'search'
        ));
    }
}

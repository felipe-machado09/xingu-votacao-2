<?php

namespace App\Http\Controllers;

use App\Models\Audience;
use App\Models\Category;
use App\Models\CategoryWinner;
use App\Models\Company;
use App\Models\Sponsor;
use App\Models\Vote;
use App\Models\AwardDraw;
use Illuminate\Http\Request;

class WinnersController extends Controller
{
    public function index(Request $request)
    {
        $audienceId = session('audience_id');
        $audience = $audienceId ? Audience::find($audienceId) : null;

        // Filtros
        $year = $request->get('year');
        $categoryId = $request->get('category');
        $search = $request->get('search');

        // Buscar vencedores com filtros
        $query = CategoryWinner::with(['category', 'company'])
            ->whereHas('category', function ($q) {
                $q->where('is_active', true);
            });

        if ($year) {
            $query->where('year', $year);
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($search) {
            $query->whereHas('company', function ($q) use ($search) {
                $q->where('legal_name', 'like', "%{$search}%")
                  ->orWhere('trade_name', 'like', "%{$search}%");
            });
        }

        $categoriesWithWinners = $query->get()->sortBy(fn ($w) => mb_strtolower($w->company->legal_name));

        // Calcular porcentagem de votos por categoria
        foreach ($categoriesWithWinners as $winner) {
            $winnerVotes = $winner->company->votes()
                ->where('category_id', $winner->category_id)
                ->count();
            $totalCategoryVotes = Vote::where('category_id', $winner->category_id)->count();
            $winner->votes_percentage = $totalCategoryVotes > 0
                ? round(($winnerVotes / $totalCategoryVotes) * 100, 1)
                : 0;
        }

        // Estatísticas
        $totalVotes = $year ? Vote::whereYear('created_at', $year)->count() : Vote::count();
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

        // Ganhadores dos eletrodomésticos (sorteios concluídos e marcados para exibição)
        $awardDraws = AwardDraw::with(['award', 'audience'])
            ->where('status', 'completed')
            ->where('show_on_home', true)
            ->orderBy('drawn_at', 'desc')
            ->get();

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
            'awardDraws',
            'year',
            'categoryId',
            'search'
        ));
    }
}

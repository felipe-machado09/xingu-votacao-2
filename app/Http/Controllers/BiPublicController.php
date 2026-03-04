<?php

namespace App\Http\Controllers;

use App\Models\Audience;
use App\Models\Category;
use App\Models\Company;
use App\Models\TvSession;
use App\Models\Vote;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class BiPublicController extends Controller
{
    /**
     * Exibe o BI público (sem dados de empresas).
     */
    public function index()
    {
        return view('bi.public');
    }

    /**
     * Gera ou retorna um código de TV pendente.
     */
    public function requestCode(): JsonResponse
    {
        $code = TvSession::generateUniqueCode();

        $session = TvSession::create([
            'code' => $code,
            'is_active' => false,
        ]);

        return response()->json([
            'code' => $session->code,
            'session_id' => $session->id,
        ]);
    }

    /**
     * Verifica se uma sessão de TV foi autorizada pelo admin.
     */
    public function checkAuth(): JsonResponse
    {
        $sessionId = request()->input('session_id');

        if (!$sessionId) {
            return response()->json(['authorized' => false, 'error' => 'Sessão não informada'], 400);
        }

        $session = TvSession::find($sessionId);

        if (!$session) {
            return response()->json(['authorized' => false, 'error' => 'Sessão não encontrada'], 404);
        }

        return response()->json([
            'authorized' => $session->isAuthorized(),
            'name' => $session->name,
        ]);
    }

    /**
     * API que retorna os dados globais (sem empresas/líderes).
     */
    public function data(): JsonResponse
    {
        // Total geral de votos
        $totalVotes = Vote::count();

        // Total de eleitores (audiência que votou)
        $totalVoters = Vote::distinct('audience_id')->count('audience_id');

        // Total de categorias ativas
        $totalCategories = Category::where('is_active', true)->count();

        // Total de empresas participantes (só o número, sem nomes)
        $totalCompanies = Company::whereHas('categories', function ($q) {
            $q->where('is_active', true);
        })->count();

        // Votos por hora (últimas 24h)
        $votesPerHour = Vote::where('created_at', '>=', now()->subHours(24))
            ->select(
                DB::raw("DATE_FORMAT(created_at, '%H:00') as hour"),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('hour')
            ->orderBy('hour')
            ->get()
            ->pluck('total', 'hour')
            ->toArray();

        // Preencher todas as horas das últimas 24h
        $hoursData = [];
        for ($i = 23; $i >= 0; $i--) {
            $hour = now()->subHours($i)->format('H:00');
            $hoursData[$hour] = $votesPerHour[$hour] ?? 0;
        }

        // TODAS as categorias ativas com votos (ranking completo)
        $allCategories = Category::where('is_active', true)
            ->withCount('votes')
            ->orderByDesc('votes_count')
            ->get()
            ->map(fn($c) => [
                'name' => $c->name,
                'votes' => $c->votes_count,
                'group' => $c->category_group,
            ]);

        // Votos por grupo de categoria
        $votesByGroup = Category::where('is_active', true)
            ->withCount('votes')
            ->get()
            ->groupBy('category_group')
            ->map(fn($cats) => $cats->sum('votes_count'))
            ->sortDesc()
            ->toArray();

        // Quantidade de candidatos (empresas) por categoria
        $companiesPerCategory = Category::where('is_active', true)
            ->withCount('companies')
            ->orderByDesc('companies_count')
            ->get()
            ->map(fn($c) => [
                'name' => $c->name,
                'companies' => $c->companies_count,
                'group' => $c->category_group,
            ]);

        // Votos por faixa etária
        $votesByAge = Vote::join('audiences', 'votes.audience_id', '=', 'audiences.id')
            ->whereNotNull('audiences.birth_date')
            ->select(DB::raw("
                CASE
                    WHEN TIMESTAMPDIFF(YEAR, audiences.birth_date, CURDATE()) < 18 THEN 'Até 17'
                    WHEN TIMESTAMPDIFF(YEAR, audiences.birth_date, CURDATE()) BETWEEN 18 AND 24 THEN '18-24'
                    WHEN TIMESTAMPDIFF(YEAR, audiences.birth_date, CURDATE()) BETWEEN 25 AND 34 THEN '25-34'
                    WHEN TIMESTAMPDIFF(YEAR, audiences.birth_date, CURDATE()) BETWEEN 35 AND 44 THEN '35-44'
                    WHEN TIMESTAMPDIFF(YEAR, audiences.birth_date, CURDATE()) BETWEEN 45 AND 54 THEN '45-54'
                    WHEN TIMESTAMPDIFF(YEAR, audiences.birth_date, CURDATE()) BETWEEN 55 AND 64 THEN '55-64'
                    ELSE '65+'
                END as age_range
            "), DB::raw('COUNT(*) as total'))
            ->groupBy('age_range')
            ->orderByRaw("FIELD(age_range, 'Até 17', '18-24', '25-34', '35-44', '45-54', '55-64', '65+')")
            ->pluck('total', 'age_range')
            ->toArray();

        // Votos nos últimos 10 segundos (indicador de atividade)
        $recentVotes = Vote::where('created_at', '>=', now()->subSeconds(10))->count();

        // Votos hoje
        $votesToday = Vote::whereDate('created_at', today())->count();

        // Votos ontem
        $votesYesterday = Vote::whereDate('created_at', today()->subDay())->count();

        // Votos na última hora
        $votesLastHour = Vote::where('created_at', '>=', now()->subHour())->count();

        // Votos semana atual
        $startOfWeek = now()->startOfWeek();
        $votesThisWeek = Vote::where('created_at', '>=', $startOfWeek)->count();

        // Votos semana passada
        $startOfLastWeek = now()->subWeek()->startOfWeek();
        $endOfLastWeek = now()->subWeek()->endOfWeek();
        $votesLastWeek = Vote::whereBetween('created_at', [$startOfLastWeek, $endOfLastWeek])->count();

        // Votos semana passada até o mesmo dia (comparativo justo)
        $dayOfWeek = now()->dayOfWeekIso;
        $sameDayLastWeek = $startOfLastWeek->copy()->addDays($dayOfWeek - 1)->endOfDay();
        $votesLastWeekSameDay = Vote::whereBetween('created_at', [$startOfLastWeek, $sameDayLastWeek])->count();

        // Eleitores hoje vs ontem
        $votersToday = Vote::whereDate('created_at', today())->distinct('audience_id')->count('audience_id');
        $votersYesterday = Vote::whereDate('created_at', today()->subDay())->distinct('audience_id')->count('audience_id');

        // Eleitores semana atual vs semana passada
        $votersThisWeek = Vote::where('created_at', '>=', $startOfWeek)->distinct('audience_id')->count('audience_id');
        $votersLastWeek = Vote::whereBetween('created_at', [$startOfLastWeek, $endOfLastWeek])->distinct('audience_id')->count('audience_id');

        // Média de votos por hora hoje vs ontem
        $hoursToday = max(now()->hour, 1);
        $avgPerHourToday = round($votesToday / $hoursToday, 1);
        $avgPerHourYesterday = round($votesYesterday / 24, 1);

        // Votos por dia (últimos 7 dias)
        $votesPerDay = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dayLabel = $date->translatedFormat('D d/m');
            $votesPerDay[$dayLabel] = Vote::whereDate('created_at', $date->toDateString())->count();
        }

        // Média de votos por categoria
        $avgVotesPerCategory = $totalCategories > 0 ? round($totalVotes / $totalCategories) : 0;

        // Média de candidatos por categoria
        $avgCompaniesPerCategory = $totalCategories > 0
            ? round(DB::table('category_company')
                ->join('categories', 'categories.id', '=', 'category_company.category_id')
                ->where('categories.is_active', true)
                ->count() / $totalCategories, 1)
            : 0;

        // --- NOVAS MÉTRICAS GLOBAIS ---

        // Média de votos por eleitor
        $avgVotesPerVoter = $totalVoters > 0 ? round($totalVotes / $totalVoters, 1) : 0;

        // Total de audiência cadastrada (incluindo quem não votou)
        $totalAudience = Audience::count();

        // Taxa de participação (% da audiência que votou)
        $participationRate = $totalAudience > 0 ? round(($totalVoters / $totalAudience) * 100, 1) : 0;

        // Média de categorias votadas por eleitor
        $avgCategoriesPerVoter = $totalVoters > 0
            ? round(Vote::select('audience_id')
                ->selectRaw('COUNT(DISTINCT category_id) as cat_count')
                ->groupBy('audience_id')
                ->get()
                ->avg('cat_count'), 1)
            : 0;

        // Categoria mais disputada (mais candidatos)
        $mostDisputedCategory = Category::where('is_active', true)
            ->withCount('companies')
            ->orderByDesc('companies_count')
            ->first();
        $mostDisputedName = $mostDisputedCategory ? $mostDisputedCategory->name : '—';
        $mostDisputedCount = $mostDisputedCategory ? $mostDisputedCategory->companies_count : 0;

        // Categoria mais votada
        $mostVotedCategory = Category::where('is_active', true)
            ->withCount('votes')
            ->orderByDesc('votes_count')
            ->first();
        $mostVotedName = $mostVotedCategory ? $mostVotedCategory->name : '—';
        $mostVotedCount = $mostVotedCategory ? $mostVotedCategory->votes_count : 0;

        // Dias desde o início da votação
        $firstVote = Vote::orderBy('created_at')->first();
        $daysActive = $firstVote ? (int) $firstVote->created_at->diffInDays(now()) : 0;

        // Média de votos por dia (desde o início)
        $avgVotesPerDay = $daysActive > 0 ? round($totalVotes / $daysActive) : $totalVotes;

        // Média de eleitores por dia
        $avgVotersPerDay = $daysActive > 0 ? round($totalVoters / $daysActive) : $totalVoters;

        // Cadastros por hora (últimas 24h)
        $registrationsPerHour = Audience::where('created_at', '>=', now()->subHours(24))
            ->select(DB::raw("DATE_FORMAT(created_at, '%H:00') as hour"), DB::raw('COUNT(*) as total'))
            ->groupBy('hour')
            ->pluck('total', 'hour')
            ->toArray();

        $registrationsData = [];
        for ($i = 23; $i >= 0; $i--) {
            $hour = now()->subHours($i)->format('H:00');
            $registrationsData[$hour] = $registrationsPerHour[$hour] ?? 0;
        }

        return response()->json([
            'total_votes' => $totalVotes,
            'total_voters' => $totalVoters,
            'total_categories' => $totalCategories,
            'total_companies' => $totalCompanies,
            'votes_today' => $votesToday,
            'votes_yesterday' => $votesYesterday,
            'votes_last_hour' => $votesLastHour,
            'votes_this_week' => $votesThisWeek,
            'votes_last_week' => $votesLastWeek,
            'votes_last_week_same_day' => $votesLastWeekSameDay,
            'voters_today' => $votersToday,
            'voters_yesterday' => $votersYesterday,
            'voters_this_week' => $votersThisWeek,
            'voters_last_week' => $votersLastWeek,
            'avg_per_hour_today' => $avgPerHourToday,
            'avg_per_hour_yesterday' => $avgPerHourYesterday,
            'recent_votes' => $recentVotes,
            'votes_per_hour' => $hoursData,
            'all_categories' => $allCategories,
            'votes_by_group' => $votesByGroup,
            'companies_per_category' => $companiesPerCategory,
            'votes_by_age' => $votesByAge,
            'votes_per_day' => $votesPerDay,
            'avg_votes_per_category' => $avgVotesPerCategory,
            'avg_companies_per_category' => $avgCompaniesPerCategory,
            'avg_votes_per_voter' => $avgVotesPerVoter,
            'total_audience' => $totalAudience,
            'participation_rate' => $participationRate,
            'avg_categories_per_voter' => $avgCategoriesPerVoter,
            'most_disputed_category' => $mostDisputedName,
            'most_disputed_count' => $mostDisputedCount,
            'most_voted_category' => $mostVotedName,
            'most_voted_count' => $mostVotedCount,
            'days_active' => $daysActive,
            'avg_votes_per_day' => $avgVotesPerDay,
            'avg_voters_per_day' => $avgVotersPerDay,
            'registrations_per_hour' => $registrationsData,
            'updated_at' => now()->format('H:i:s'),
        ]);
    }
}

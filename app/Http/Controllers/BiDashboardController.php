<?php

namespace App\Http\Controllers;

use App\Models\Audience;
use App\Models\Category;
use App\Models\Company;
use App\Models\TvSession;
use App\Models\Vote;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class BiDashboardController extends Controller
{
    /**
     * Exibe a tela do BI para TV.
     * Gera um código se não existir sessão, ou reutiliza o existente.
     */
    public function index()
    {
        return view('bi.dashboard');
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
     * API que retorna os dados em tempo real (chamada via AJAX a cada 5s)
     */
    public function data(): JsonResponse
    {
        // Total geral de votos
        $totalVotes = Vote::count();

        // Total de eleitores (audiência que votou)
        $totalVoters = Vote::distinct('audience_id')->count('audience_id');

        // Total de categorias ativas
        $totalCategories = Category::where('is_active', true)->count();

        // Total de empresas participantes
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

        // Top 10 categorias com mais votos
        $topCategories = Category::where('is_active', true)
            ->withCount('votes')
            ->orderByDesc('votes_count')
            ->limit(10)
            ->get()
            ->map(fn($c) => [
                'name' => $c->name,
                'votes' => $c->votes_count,
                'group' => $c->category_group,
            ]);

        // Top 10 empresas mais votadas (geral)
        $topCompanies = Company::withCount('votes')
            ->orderByDesc('votes_count')
            ->limit(10)
            ->get()
            ->map(fn($c) => [
                'name' => $c->legal_name,
                'votes' => $c->votes_count,
                'logo' => $c->logo_path ? asset('storage/' . $c->logo_path) : null,
            ]);

        // Votos por faixa etária
        $votesByAge = Vote::join('audiences', 'votes.audience_id', '=', 'audiences.id')
            ->whereNotNull('audiences.birth_date')
            ->select(DB::raw("
                CASE
                    WHEN TIMESTAMPDIFF(YEAR, audiences.birth_date, CURDATE()) < 18 THEN 'Ate 17'
                    WHEN TIMESTAMPDIFF(YEAR, audiences.birth_date, CURDATE()) BETWEEN 18 AND 24 THEN '18-24'
                    WHEN TIMESTAMPDIFF(YEAR, audiences.birth_date, CURDATE()) BETWEEN 25 AND 34 THEN '25-34'
                    WHEN TIMESTAMPDIFF(YEAR, audiences.birth_date, CURDATE()) BETWEEN 35 AND 44 THEN '35-44'
                    WHEN TIMESTAMPDIFF(YEAR, audiences.birth_date, CURDATE()) BETWEEN 45 AND 54 THEN '45-54'
                    WHEN TIMESTAMPDIFF(YEAR, audiences.birth_date, CURDATE()) BETWEEN 55 AND 64 THEN '55-64'
                    ELSE '65+'
                END as age_range
            "), DB::raw('COUNT(*) as total'))
            ->groupBy('age_range')
            ->orderByRaw("FIELD(age_range, 'Ate 17', '18-24', '25-34', '35-44', '45-54', '55-64', '65+')")
            ->pluck('total', 'age_range')
            ->toArray();

        // Votos nos últimos 5 segundos (indicador de atividade)
        $recentVotes = Vote::where('created_at', '>=', now()->subSeconds(10))->count();

        // Votos hoje
        $votesToday = Vote::whereDate('created_at', today())->count();

        // Votos ontem
        $votesYesterday = Vote::whereDate('created_at', today()->subDay())->count();

        // Votos na última hora
        $votesLastHour = Vote::where('created_at', '>=', now()->subHour())->count();

        // Votos semana atual (segunda a hoje)
        $startOfWeek = now()->startOfWeek();
        $votesThisWeek = Vote::where('created_at', '>=', $startOfWeek)->count();

        // Votos semana passada (segunda a domingo)
        $startOfLastWeek = now()->subWeek()->startOfWeek();
        $endOfLastWeek = now()->subWeek()->endOfWeek();
        $votesLastWeek = Vote::whereBetween('created_at', [$startOfLastWeek, $endOfLastWeek])->count();

        // Votos semana passada até o mesmo dia da semana (comparativo justo)
        $dayOfWeek = now()->dayOfWeekIso; // 1=seg ... 7=dom
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

        // Ranking por categoria (top empresa de cada categoria)
        $categoryLeaders = Category::where('is_active', true)
            ->with(['votes' => function ($q) {
                $q->select('category_id', 'company_id', DB::raw('COUNT(*) as total'))
                    ->groupBy('category_id', 'company_id');
            }])
            ->withCount('votes')
            ->orderByDesc('votes_count')
            ->limit(15)
            ->get()
            ->map(function ($category) {
                $topCompany = Vote::where('category_id', $category->id)
                    ->select('company_id', DB::raw('COUNT(*) as total'))
                    ->groupBy('company_id')
                    ->orderByDesc('total')
                    ->first();

                $company = $topCompany ? Company::find($topCompany->company_id) : null;

                return [
                    'category' => $category->name,
                    'group' => $category->category_group,
                    'total_votes' => $category->votes_count,
                    'leader' => $company ? $company->legal_name : '—',
                    'leader_votes' => $topCompany ? $topCompany->total : 0,
                ];
            });

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
            'top_categories' => $topCategories,
            'top_companies' => $topCompanies,
            'votes_by_age' => $votesByAge,
            'category_leaders' => $categoryLeaders,
            'votes_per_day' => $votesPerDay,
            'updated_at' => now()->format('H:i:s'),
        ]);
    }
}

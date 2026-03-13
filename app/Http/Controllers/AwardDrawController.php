<?php

namespace App\Http\Controllers;

use App\Models\Award;
use App\Models\AwardDraw;
use App\Models\Audience;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AwardDrawController extends Controller
{
    public function index()
    {
        $awards = Award::where('is_active', true)
            ->withCount(['draws as completed_draws_count' => function ($query) {
                $query->where('status', 'completed');
            }])
            ->orderBy('tier')
            ->get()
            ->filter(fn($award) => $award->hasRemainingQuantity());

        return view('sorteio.index', compact('awards'));
    }

    public function draw(Award $award): JsonResponse
    {
        if (!$award->is_active || !$award->hasRemainingQuantity()) {
            return response()->json([
                'success' => false,
                'message' => 'Este prêmio não está disponível para sorteio.',
            ], 422);
        }

        $alreadyDrawnIds = AwardDraw::where('award_id', $award->id)
            ->where('status', 'completed')
            ->pluck('audience_id')
            ->toArray();

        $eligibleIds = \DB::table('votes')
            ->select('audience_id')
            ->groupBy('audience_id')
            ->havingRaw('COUNT(DISTINCT category_id) >= ?', [$award->min_votes])
            ->when(count($alreadyDrawnIds) > 0, function ($query) use ($alreadyDrawnIds) {
                $query->whereNotIn('audience_id', $alreadyDrawnIds);
            })
            ->pluck('audience_id');

        $eligible = Audience::whereIn('id', $eligibleIds)->get();

        if ($eligible->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Não há participantes elegíveis para este prêmio.',
            ], 422);
        }

        $winner = $eligible->random();

        $awardDraw = AwardDraw::create([
            'award_id' => $award->id,
            'audience_id' => $winner->id,
            'status' => 'completed',
            'drawn_at' => now(),
            'meta' => [
                'total_eligible' => $eligible->count(),
                'min_votes_required' => $award->min_votes,
            ],
        ]);

        $phone = $winner->phone ?? '';
        $maskedPhone = strlen($phone) >= 4
            ? '****' . substr($phone, -4)
            : $phone;

        $email = $winner->email ?? '';
        $maskedEmail = strlen($email) >= 4
            ? substr($email, 0, 4) . '****'
            : $email;

        // Nomes aleatórios para animação de "sorteando"
        $decoyNames = Audience::whereNotIn('id', [$winner->id])
            ->inRandomOrder()
            ->limit(20)
            ->pluck('name')
            ->map(fn($name) => mb_substr($name, 0, mb_strpos($name, ' ') ?: mb_strlen($name)))
            ->toArray();

        return response()->json([
            'success' => true,
            'winner' => [
                'name' => $winner->name,
                'phone' => $maskedPhone,
                'email' => $maskedEmail,
            ],
            'decoy_names' => $decoyNames,
            'award' => [
                'name' => $award->name,
                'image' => $award->image_path ? asset('storage/' . $award->image_path) : null,
                'remaining' => $award->remainingQuantity() - 1,
            ],
            'total_eligible' => $eligible->count(),
        ]);
    }
}

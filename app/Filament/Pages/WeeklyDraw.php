<?php

namespace App\Filament\Pages;

use App\Models\Audience;
use App\Models\Award;
use App\Models\AwardDraw;
use App\Models\Vote;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;

class WeeklyDraw extends Page implements HasForms, HasTable
{
    use InteractsWithForms, InteractsWithTable;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-gift';

    protected static ?string $navigationLabel = 'Sorteio Semanal';

    protected static ?string $title = 'Sorteio Semanal';

    protected static string | \UnitEnum | null $navigationGroup = 'Sorteios';

    protected string $view = 'filament.pages.weekly-draw';

    protected static ?int $navigationSort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(AwardDraw::query()->with(['award', 'audience'])->where('status', 'completed')->latest())
            ->columns([
                TextColumn::make('award.name')
                    ->label('Prêmio'),
                TextColumn::make('audience.name')
                    ->label('Ganhador'),
                TextColumn::make('audience.email')
                    ->label('E-mail'),
                TextColumn::make('drawn_at')
                    ->dateTime('d/m/Y H:i:s')
                    ->label('Data do Sorteio'),
            ])
            ->paginated([10, 25, 50]);
    }

    public function runDraw(): void
    {
        DB::beginTransaction();

        try {
            // Buscar prêmio disponível (ordenado por tier, do menor para o maior)
            $award = Award::where('is_active', true)
                ->whereHas('draws', function ($query) {
                    $query->where('status', 'completed');
                }, '<', DB::raw('quantity'))
                ->orWhereDoesntHave('draws')
                ->orderBy('tier', 'asc')
                ->first();

            if (!$award || !$award->hasRemainingQuantity()) {
                Notification::make()
                    ->title('Nenhum prêmio disponível')
                    ->warning()
                    ->send();
                DB::rollBack();
                return;
            }

            $lastCompletedDraw = AwardDraw::where('status', 'completed')
                ->latest('drawn_at')
                ->first();

            $cutoffDate = $lastCompletedDraw 
                ? $lastCompletedDraw->drawn_at 
                : now()->subDays(7);

            // Buscar IDs de audiências elegíveis com base no min_votes do prêmio
            $minVotesRequired = $award->min_votes ?? 5;
            
            // Para tier 3 (todos os votos), precisamos verificar se votou em TODAS as categorias
            if ($award->tier == 3) {
                $totalCategories = \App\Models\Category::count();
                $eligibleAudienceIds = Vote::where('created_at', '>=', $cutoffDate)
                    ->select('audience_id')
                    ->groupBy('audience_id')
                    ->havingRaw('COUNT(DISTINCT category_id) >= ?', [$totalCategories])
                    ->pluck('audience_id')
                    ->toArray();
            } else {
                // Para tier 1 e 2, verificar se tem o número mínimo de votos
                $eligibleAudienceIds = Vote::where('created_at', '>=', $cutoffDate)
                    ->select('audience_id')
                    ->groupBy('audience_id')
                    ->havingRaw('COUNT(DISTINCT category_id) >= ?', [$minVotesRequired])
                    ->pluck('audience_id')
                    ->toArray();
            }

            if (empty($eligibleAudienceIds)) {
                Notification::make()
                    ->title('Nenhum votante elegível encontrado')
                    ->body("Este prêmio requer {$minVotesRequired} votos mínimos.")
                    ->warning()
                    ->send();
                DB::rollBack();
                return;
            }

            $winnerId = $eligibleAudienceIds[array_rand($eligibleAudienceIds)];

            $draw = AwardDraw::create([
                'award_id' => $award->id,
                'audience_id' => $winnerId,
                'status' => 'completed',
                'drawn_at' => now(),
                'meta' => [
                    'eligible_count' => count($eligibleAudienceIds),
                    'cutoff_date' => $cutoffDate->toDateTimeString(),
                    'min_votes_required' => $minVotesRequired,
                    'award_tier' => $award->tier,
                ],
            ]);

            DB::commit();

            Notification::make()
                ->title('Sorteio realizado com sucesso!')
                ->body("Prêmio Nível {$award->tier}: {$award->name}")
                ->success()
                ->send();

        } catch (\Exception $e) {
            DB::rollBack();
            
            Notification::make()
                ->title('Falha no sorteio: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function getAvailableAwards(): array
    {
        return Award::where('is_active', true)
            ->get()
            ->filter(fn (Award $award) => $award->hasRemainingQuantity())
            ->map(fn (Award $award) => [
                'name' => $award->name,
                'remaining' => $award->remainingQuantity(),
            ])
            ->toArray();
    }
}

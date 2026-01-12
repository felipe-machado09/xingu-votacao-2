<?php

namespace Tests\Feature;

use App\Models\Audience;
use App\Models\Award;
use App\Models\AwardDraw;
use App\Models\Category;
use App\Models\Company;
use App\Models\Vote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WeeklyDrawTest extends TestCase
{
    use RefreshDatabase;

    public function test_draw_picks_eligible_voter(): void
    {
        $award = Award::factory()->create(['quantity' => 1, 'is_active' => true]);
        
        $audience1 = Audience::factory()->create(['email_verified_at' => now()]);
        $audience2 = Audience::factory()->create(['email_verified_at' => now()]);
        $audience3 = Audience::factory()->create(['email_verified_at' => now()]);
        
        $category = Category::factory()->create(['is_active' => true]);
        $company = Company::factory()->create();
        $category->companies()->attach($company->id);

        Vote::create([
            'audience_id' => $audience1->id,
            'category_id' => $category->id,
            'company_id' => $company->id,
            'created_at' => now()->subDays(2),
        ]);

        Vote::create([
            'audience_id' => $audience2->id,
            'category_id' => $category->id,
            'company_id' => $company->id,
            'created_at' => now()->subDays(1),
        ]);

        $cutoffDate = now()->subDays(7);
        $eligibleAudienceIds = Vote::where('created_at', '>=', $cutoffDate)
            ->distinct('audience_id')
            ->pluck('audience_id')
            ->toArray();

        $this->assertCount(2, $eligibleAudienceIds);

        $winnerId = $eligibleAudienceIds[array_rand($eligibleAudienceIds)];

        $draw = AwardDraw::create([
            'award_id' => $award->id,
            'audience_id' => $winnerId,
            'status' => 'completed',
            'drawn_at' => now(),
            'meta' => [
                'eligible_count' => count($eligibleAudienceIds),
                'cutoff_date' => $cutoffDate->toDateTimeString(),
            ],
        ]);

        $this->assertDatabaseHas('award_draws', [
            'award_id' => $award->id,
            'audience_id' => $winnerId,
            'status' => 'completed',
        ]);

        $this->assertContains($draw->audience_id, [$audience1->id, $audience2->id]);
        $this->assertNotEquals($audience3->id, $draw->audience_id);
    }

    public function test_draw_respects_award_quantity(): void
    {
        $award = Award::factory()->create(['quantity' => 2, 'is_active' => true]);
        
        $audience1 = Audience::factory()->create(['email_verified_at' => now()]);
        $audience2 = Audience::factory()->create(['email_verified_at' => now()]);
        
        $category = Category::factory()->create(['is_active' => true]);
        $company = Company::factory()->create();
        $category->companies()->attach($company->id);

        Vote::create([
            'audience_id' => $audience1->id,
            'category_id' => $category->id,
            'company_id' => $company->id,
            'created_at' => now(),
        ]);

        Vote::create([
            'audience_id' => $audience2->id,
            'category_id' => $category->id,
            'company_id' => $company->id,
            'created_at' => now(),
        ]);

        AwardDraw::create([
            'award_id' => $award->id,
            'audience_id' => $audience1->id,
            'status' => 'completed',
            'drawn_at' => now(),
        ]);

        $this->assertTrue($award->fresh()->hasRemainingQuantity());
        $this->assertEquals(1, $award->fresh()->remainingQuantity());

        AwardDraw::create([
            'award_id' => $award->id,
            'audience_id' => $audience2->id,
            'status' => 'completed',
            'drawn_at' => now(),
        ]);

        $this->assertFalse($award->fresh()->hasRemainingQuantity());
        $this->assertEquals(0, $award->fresh()->remainingQuantity());
    }
}

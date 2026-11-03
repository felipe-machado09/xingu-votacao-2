<?php

namespace Tests\Feature;

use App\Models\Audience;
use App\Models\Award;
use App\Models\Category;
use App\Models\CategoryWinner;
use App\Models\Company;
use App\Models\Vote;
use Illuminate\Foundation\Testing\RefreshDatabaseState;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CampaignMigrationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh')->assertExitCode(0);
    }

    protected function tearDown(): void
    {
        RefreshDatabaseState::$migrated = false;
        parent::tearDown();
    }

    public function test_migration_preserves_dates_and_separates_all_legacy_data_into_edition_2025(): void
    {
        $category = Category::factory()->create();
        $company = Company::factory()->create(['legal_name' => 'Nome antigo']);
        $category->companies()->attach($company);
        $audience = Audience::factory()->create();
        $vote = Vote::create(['category_id' => $category->id, 'company_id' => $company->id, 'audience_id' => $audience->id]);
        $award = Award::factory()->create();
        $winner = CategoryWinner::create(['category_id' => $category->id, 'company_id' => $company->id, 'year' => 2026, 'chosen_at' => now()]);
        DB::table('votes')->where('id', $vote->id)->update(['created_at' => '2026-03-01 10:00:00']);
        DB::table('awards')->where('id', $award->id)->update(['created_at' => '2024-11-01 10:00:00']);
        $migration = require database_path('migrations/2026_11_02_000001_add_campaign_fields.php');
        $migration->down();
        $migration->up();
        $this->assertSame('Nome antigo', $company->fresh()->trade_name);
        $this->assertSame(2025, $vote->fresh()->year);
        $this->assertSame('2026-03-01 10:00:00', $vote->fresh()->created_at->format('Y-m-d H:i:s'));
        $this->assertSame(2025, $award->fresh()->year);
        $this->assertSame('2024-11-01 10:00:00', $award->fresh()->created_at->format('Y-m-d H:i:s'));
        $this->assertSame(2025, $winner->fresh()->year);
        $this->assertSame(2025, $audience->fresh()->year);
        $this->assertDatabaseCount('votes', 1);
        $this->assertDatabaseHas('category_company', ['category_id' => $category->id, 'company_id' => $company->id]);
    }

    public function test_rollback_refuses_to_delete_or_collapse_annual_votes(): void
    {
        $category = Category::factory()->create();
        $company = Company::factory()->create();
        $audience = Audience::factory()->create();
        foreach ([2025, 2026] as $year) {
            Vote::create(['category_id' => $category->id, 'company_id' => $company->id, 'audience_id' => $audience->id, 'year' => $year]);
        }
        $migration = require database_path('migrations/2026_11_02_000001_add_campaign_fields.php');
        try {
            $migration->down();
            $this->fail('O rollback deveria recusar registros de múltiplas edições.');
        } catch (\RuntimeException $exception) {
            $this->assertStringContainsString('várias edições', $exception->getMessage());
        }
        $this->assertDatabaseCount('votes', 2);
        $this->assertSame(1, Vote::forYear(2025)->count());
        Vote::forYear(2025)->delete();
    }
}

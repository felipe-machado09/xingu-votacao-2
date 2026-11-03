<?php

namespace Tests\Feature;

use App\Filament\Pages\CategoryResults;
use App\Filament\Resources\CompanyResource\Pages\CompanyReport;
use App\Filament\Resources\VoteResource\Pages\ListVotes;
use App\Filament\Widgets\TopCompaniesTable;
use App\Models\Audience;
use App\Models\Category;
use App\Models\CategoryWinner;
use App\Models\Company;
use App\Models\LandingPageSection;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class AnnualReportsTest extends TestCase
{
    use RefreshDatabase;

    private function votes(): array
    {
        LandingPageSection::updateOrCreate(['key' => 'campaign'], ['is_active' => true, 'metadata' => ['year' => 2026]]);
        $category = Category::factory()->create();
        $company = Company::factory()->create();
        $category->companies()->attach($company);
        $audience = Audience::factory()->create();
        $old = Vote::create(['category_id' => $category->id, 'company_id' => $company->id, 'audience_id' => $audience->id, 'year' => 2025]);
        $current = Vote::create(['category_id' => $category->id, 'company_id' => $company->id, 'audience_id' => $audience->id, 'year' => 2026]);
        $other = Audience::factory()->create();
        Vote::create(['category_id' => $category->id, 'company_id' => $company->id, 'audience_id' => $other->id, 'year' => 2026]);

        return [$category, $company, $old, $current];
    }

    private function admin(): User
    {
        $admin = User::factory()->create();
        foreach (['ViewAny:Vote', 'View:Vote', 'ViewAny:Company', 'View:Company', 'ViewAny:Category', 'ViewAny:Audience'] as $name) {
            $admin->givePermissionTo(Permission::findOrCreate($name, 'web'));
        }
        $this->actingAs($admin);

        return $admin;
    }

    public function test_company_and_category_reports_isolate_totals_rankings_and_winners(): void
    {
        [$category, $company] = $this->votes();
        $report = new CompanyReport;
        $report->record = $company;
        $report->year = 2025;
        $this->assertSame(1, $report->getTotalVotesProperty());
        $this->assertSame(1, $report->getUniqueVotersProperty());
        $this->assertSame(1, $report->getCategoryRankings()[0]['votes']);
        $report->year = 2026;
        $this->assertSame(2, $report->getTotalVotesProperty());
        $this->assertSame(2, $report->getCategoryRankings()[0]['total_votes']);

        CategoryWinner::create(['category_id' => $category->id, 'company_id' => $company->id, 'year' => 2025, 'chosen_at' => now()]);
        $results = new CategoryResults;
        $results->selectedCategoryId = $category->id;
        $results->year = 2025;
        $this->assertSame(1, $results->getTopCompanies()[0]['votes']);
        $this->assertSame(2025, $results->getCurrentWinner()->year);
        $results->year = 2026;
        $this->assertSame(2, $results->getTopCompanies()[0]['votes']);
        $this->assertNull($results->getCurrentWinner());
        $results->setWinner($company->id);
        $this->assertDatabaseCount('category_winners', 2);
    }

    public function test_votes_table_defaults_to_latest_edition_and_filters_exports(): void
    {
        [, , $old, $current] = $this->votes();
        $this->admin();
        $table = Livewire::test(ListVotes::class)->assertSuccessful()
            ->assertCanSeeTableRecords([$current])->assertCanNotSeeTableRecords([$old]);
        $table->filterTable('year', 2025)->assertCanSeeTableRecords([$old])->assertCanNotSeeTableRecords([$current]);
        $table->callTableAction('exportExcel')->assertFileDownloaded('votos.xlsx');
        $path = tempnam(sys_get_temp_dir(), 'xingu-test-export-');
        file_put_contents($path, base64_decode(data_get($table->effects, 'download.content'), true));
        try {
            $zip = new \ZipArchive;
            $this->assertTrue($zip->open($path));
            $sheet = $zip->getFromName('xl/worksheets/sheet1.xml');
            $this->assertSame(2, substr_count($sheet, '<row '));
            $this->assertStringContainsString('Participante', $sheet);
            $this->assertStringContainsString('Data do voto', $sheet);
            $zip->close();
        } finally {
            unlink($path);
        }
    }

    public function test_dashboard_ranking_uses_the_dashboard_year(): void
    {
        [, $company] = $this->votes();
        $this->admin();
        $table = Livewire::test(TopCompaniesTable::class, ['pageFilters' => ['year' => 2025]])->assertSuccessful();
        $this->assertSame(1, (int) $table->instance()->getTableRecords()->first()->votes_count);
        $table = Livewire::test(TopCompaniesTable::class, ['pageFilters' => ['year' => 2026]])->assertSuccessful();
        $this->assertSame(2, (int) $table->instance()->getTableRecords()->first()->votes_count);
    }

    public function test_dashboard_renders_and_updates_its_year_filter(): void
    {
        $this->votes();
        $this->admin();

        Livewire::test(\App\Filament\Pages\Dashboard::class)
            ->assertSuccessful()
            ->assertSet('filters.year', 2026)
            ->set('filters.year', 2025)
            ->assertSuccessful()
            ->call('$refresh')
            ->assertSuccessful();
    }

    public function test_dashboard_widgets_render_and_refresh(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            $this->markTestSkipped('Os gráficos usam funções SQL do MariaDB.');
        }

        $this->votes();
        $this->admin();

        foreach (\Filament\Facades\Filament::getWidgets() as $widget) {
            foreach ([2026, 2025] as $year) {
                Livewire::test($widget, ['pageFilters' => ['year' => $year]])
                    ->assertSuccessful()
                    ->call('$refresh')
                    ->assertSuccessful();
            }
        }
    }

    public function test_administrative_listings_filter_vote_counts_and_export_by_edition(): void
    {
        $this->votes();
        $this->admin();
        foreach ([
            \App\Filament\Resources\CompanyResource\Pages\ListCompanies::class => 'empresas.xlsx',
            \App\Filament\Resources\CategoryResource\Pages\ListCategories::class => 'categorias.xlsx',
            \App\Filament\Resources\AudienceResource\Pages\ListAudiences::class => 'participantes.xlsx',
        ] as $page => $filename) {
            $table = Livewire::test($page)->assertSuccessful()->filterTable('year', 2025);
            $this->assertSame(1, (int) $table->instance()->getFilteredSortedTableQuery()->first()->votes_count);
            $table->callTableAction('exportExcel')->assertFileDownloaded($filename);
        }
    }

    public function test_legacy_participants_are_not_assigned_to_new_editions_by_registration_date(): void
    {
        $this->admin();
        $old = Audience::factory()->create(['year' => 2025, 'created_at' => '2026-03-01 10:00:00']);
        Livewire::test(\App\Filament\Resources\AudienceResource\Pages\ListAudiences::class)
            ->filterTable('year', 2026)->assertCanNotSeeTableRecords([$old])
            ->filterTable('year', 2025)->assertCanSeeTableRecords([$old]);
    }

    public function test_both_bis_filter_metrics_rankings_and_excel_by_year(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            $this->markTestSkipped('Os painéis BI usam funções SQL do MariaDB; este teste também é executado em MariaDB.');
        }
        $this->votes();
        $this->admin();
        foreach (['/bi', '/bi-publico'] as $path) {
            $response = $this->getJson($path.'/data?year=2025')->assertOk()
                ->assertJsonPath('total_votes', 1)->assertJsonPath('total_voters', 1);
            $key = $path === '/bi' ? 'top_categories' : 'all_categories';
            $this->assertSame(1, (int) $response->json($key.'.0.votes'));
            if ($path === '/bi') {
                $this->assertSame(1, (int) $response->json('top_companies.0.votes'));
                $this->assertSame(1, (int) $response->json('category_leaders.0.leader_votes'));
            }

            $this->getJson($path.'/data?year=2026')->assertOk()->assertJsonPath('total_votes', 2);
            $this->getJson($path.'/data')->assertOk()->assertJsonPath('total_votes', 2);
            $this->get($path.'/export?year=2025')->assertDownload();
        }
    }

    public function test_admin_report_pages_render_and_export_the_selected_edition(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            $this->markTestSkipped('Os gráficos usam funções SQL do MariaDB.');
        }
        [$category, $company] = $this->votes();
        $this->admin();
        Livewire::test(CategoryResults::class)->assertSuccessful()->set('year', 2025)
            ->call('selectCategory', $category->id)->assertSee($company->display_name)
            ->callAction('exportExcel')->assertFileDownloaded('resultados-2025.xlsx');
        Livewire::test(CompanyReport::class, ['record' => $company->slug])->assertSuccessful()
            ->set('year', 2025)->callAction('exportExcel')->assertFileDownloaded('empresa-'.$company->id.'-2025.xlsx');
        Livewire::test(\App\Filament\Pages\Dashboard::class)->assertSuccessful()
            ->assertSet('filters.year', 2026)->set('filters.year', 2025)
            ->callAction('exportExcel')->assertFileDownloaded('painel-2025.xlsx');
    }
}

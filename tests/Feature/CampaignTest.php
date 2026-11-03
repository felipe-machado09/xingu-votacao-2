<?php

namespace Tests\Feature;

use App\Filament\Pages\CampaignSettings;
use App\Models\Audience;
use App\Models\Award;
use App\Models\Category;
use App\Models\Company;
use App\Models\LandingPageSection;
use App\Models\User;
use App\Models\Vote;
use App\Services\ExcelExport;
use App\Services\ReportYear;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class CampaignTest extends TestCase
{
    use RefreshDatabase;

    private function configure(array $metadata = [], array $attributes = []): void
    {
        LandingPageSection::updateOrCreate(['key' => 'campaign'], array_merge([
            'is_active' => true,
            'metadata' => array_merge(['year' => 2026], $metadata),
        ], $attributes));
    }

    public function test_public_pages_follow_the_configured_edition_logo_and_regulation(): void
    {
        $this->configure(['year' => 2027], ['image' => 'campaign-logos/2027.png', 'document_path' => 'regulations/2027.pdf']);
        LandingPageSection::create(['key' => 'hero', 'title' => 'Participe em {ano}', 'is_active' => true, 'image' => 'old-logo.png']);
        foreach (['/', '/vote', '/login', '/register', '/empresa/cadastro'] as $path) {
            $this->get($path)->assertOk()->assertSee('2027')->assertSee('campaign-logos/2027.png')
                ->assertSee('regulations/2027.pdf')->assertDontSee('/vencedores');
        }
        $this->get('/')->assertSee('Participe em 2027')->assertDontSee('old-logo.png');
        $this->get('/vencedores')->assertNotFound();
        $this->get('/sitemap.xml')->assertOk()->assertDontSee('/vencedores');
    }

    public function test_company_registration_accepts_only_the_requested_business_fields(): void
    {
        Storage::fake('public');
        $category = Category::factory()->create();
        $this->post(route('company.register.store'), [
            'trade_name' => 'Empresa do Xingu',
            'cnpj' => '11.222.333/0001-81',
            'whatsapp_number' => '(93) 99999-1234',
            'instagram_url' => 'https://instagram.com/empresa',
            'logo_path' => UploadedFile::fake()->image('logo.png'),
            'categoria_id' => [$category->id],
            'lgpd' => '1',
        ])->assertRedirect(route('home'))->assertSessionHasNoErrors();

        $company = Company::firstOrFail();
        $this->assertSame('Empresa do Xingu', $company->display_name);
        $this->assertSame('11222333000181', $company->cnpj);
        $this->assertSame('93999991234', $company->whatsapp_number);
        $this->assertSame('https://instagram.com/empresa', $company->instagram_url);
        $this->assertTrue($company->categories->contains($category));
        Storage::disk('public')->assertExists($company->logo_path);
    }

    public function test_company_registration_can_be_disabled_from_admin_settings(): void
    {
        $this->configure(['company_registration_enabled' => false]);
        $this->get('/empresa/cadastro')->assertNotFound();
        $this->get('/')->assertDontSee('Cadastre sua empresa');
    }

    public function test_company_registration_rejects_inactive_categories_and_invalid_urls(): void
    {
        $category = Category::factory()->create(['is_active' => false]);
        $this->post(route('company.register.store'), [
            'trade_name' => 'Empresa',
            'cnpj' => '11.222.333/0001-81',
            'whatsapp_number' => '93999991234',
            'instagram_url' => 'javascript:alert(1)',
            'logo_path' => UploadedFile::fake()->image('logo.png'),
            'categoria_id' => [$category->id],
            'lgpd' => 1,
        ])->assertSessionHasErrors(['categoria_id.0', 'instagram_url']);
        $this->assertDatabaseCount('companies', 0);
    }

    public function test_global_deadline_closes_both_category_queries_and_vote_submission(): void
    {
        $this->configure(['end_date' => now()->subMinute()->toDateTimeString()]);
        $category = Category::factory()->create(['voting_ends_at' => now()->addDay()]);
        $company = Company::factory()->create();
        $category->companies()->attach($company);
        $audience = Audience::factory()->create();
        $this->assertFalse($category->isOpen());
        $this->assertSame(0, Category::open()->count());
        $this->withSession(['audience_id' => $audience->id])->post(route('vote.store', [$category, $company]))
            ->assertSessionHasErrors('category');
        $this->assertDatabaseCount('votes', 0);
    }

    public function test_vote_uniqueness_and_progress_are_scoped_to_the_edition(): void
    {
        $this->configure(['year' => 2026]);
        $category = Category::factory()->create();
        $company = Company::factory()->create();
        $category->companies()->attach($company);
        $audience = Audience::factory()->create();
        Vote::create(['audience_id' => $audience->id, 'company_id' => $company->id, 'category_id' => $category->id, 'year' => 2025]);
        $this->assertFalse($audience->hasVotedInCategory($category->id));
        $this->withSession(['audience_id' => $audience->id])->post(route('vote.store', [$category, $company]))
            ->assertSessionHasNoErrors();
        $this->assertTrue($audience->hasVotedInCategory($category->id));
        $this->assertDatabaseCount('votes', 2);
        $this->post(route('vote.store', [$category, $company]))->assertSessionHasErrors('vote');
    }

    public function test_category_links_use_random_order_only_when_enabled(): void
    {
        $category = Category::factory()->create();
        $companies = Company::factory()->count(3)->create();
        $category->companies()->attach($companies->pluck('id'));
        $this->configure(['randomize_companies' => true]);
        DB::enableQueryLog();
        $this->get(route('vote.category', $category))->assertOk()->assertSessionHas('voting_category_slug', $category->slug);
        $queries = collect(DB::getQueryLog())->pluck('query')->implode("\n");
        $this->assertMatchesRegularExpression('/(?:RANDOM|RAND)\\(\\)/i', $queries);
        DB::disableQueryLog();
        DB::flushQueryLog();

        $this->configure(['randomize_companies' => false]);
        $this->get(route('vote.show', $category))->assertOk()->assertViewHas('category', function ($loaded) {
            return $loaded->companies->pluck('id')->all() === Company::orderBy('trade_name')->orderBy('legal_name')->pluck('id')->all();
        });
    }

    public function test_magic_link_returns_to_the_shared_category(): void
    {
        $category = Category::factory()->create();
        $audience = Audience::factory()->create();
        $url = URL::temporarySignedRoute('auth.magic', now()->addMinutes(30), [
            'audience' => $audience->id, 'category' => $category->slug,
        ]);
        $this->get($url)->assertRedirect(route('vote.show', $category));
    }

    public function test_awards_are_visible_only_in_the_current_edition_and_while_active(): void
    {
        $this->configure(['year' => 2027]);
        Award::factory()->create(['name' => 'Prêmio atual', 'year' => 2027]);
        Award::factory()->create(['name' => 'Prêmio antigo', 'year' => 2026]);
        Award::factory()->create(['name' => 'Prêmio desativado', 'year' => 2027, 'is_active' => false]);
        $this->get('/vote')->assertOk()->assertSee('Prêmio atual')->assertDontSee('Prêmio antigo')->assertDontSee('Prêmio desativado');
        $this->assertSame(2027, Award::factory()->create()->year);
    }

    public function test_year_options_and_queries_keep_editions_separate(): void
    {
        $this->configure(['year' => 2026]);
        $category = Category::factory()->create();
        $company = Company::factory()->create();
        $audience = Audience::factory()->create();
        foreach ([2025, 2026] as $year) {
            Vote::create(['audience_id' => $audience->id, 'company_id' => $company->id, 'category_id' => $category->id, 'year' => $year]);
        }
        $this->assertSame([2026 => 2026, 2025 => 2025], ReportYear::options());
        $this->assertSame(2026, ReportYear::latest());
        $this->assertSame(1, Vote::forYear(2025)->count());
        $this->assertSame(1, $company->votes()->forYear(2026)->count());
        $this->get('/bi?year=invalid')->assertSessionHasErrors('year');
        $this->get('/bi-publico?year=2025')->assertOk()->assertSee('year=2025', false);
    }

    public function test_admin_settings_persist_and_control_the_public_site(): void
    {
        $admin = User::factory()->create();
        $admin->givePermissionTo(Permission::findOrCreate('Update:LandingPageSection', 'web'));
        $this->actingAs($admin);
        Livewire::test(CampaignSettings::class)
            ->fillForm(['year' => 2028, 'end_date' => null, 'logo' => null, 'regulation' => null, 'company_registration_enabled' => false, 'randomize_companies' => false])
            ->call('save')->assertHasNoFormErrors();
        $this->assertSame(2028, LandingPageSection::campaignYear());
        $this->assertFalse(LandingPageSection::campaignOption('company_registration_enabled'));
        $this->get('/')->assertOk()->assertSee('2028');
    }

    public function test_admin_can_create_a_company_with_the_requested_fields(): void
    {
        Storage::fake('public');
        $category = Category::factory()->create();
        $admin = User::factory()->create();
        foreach (['ViewAny:Company', 'Create:Company'] as $name) {
            $admin->givePermissionTo(Permission::findOrCreate($name, 'web'));
        }
        $this->actingAs($admin);
        Livewire::test(\App\Filament\Resources\CompanyResource\Pages\CreateCompany::class)
            ->fillForm([
                'trade_name' => 'Empresa no painel',
                'cnpj' => '11.222.333/0001-81',
                'whatsapp_number' => '93999991234',
                'instagram_url' => 'https://instagram.com/empresa',
                'logo_path' => UploadedFile::fake()->image('logo.png'),
                'categories' => [$category->id],
            ])->call('create')->assertHasNoFormErrors();
        $this->assertDatabaseHas('companies', ['trade_name' => 'Empresa no painel', 'cnpj' => '11222333000181']);
        $this->assertTrue(Company::firstOrFail()->categories->contains($category));
    }

    public function test_email_branding_follows_admin_settings(): void
    {
        $this->configure(['year' => 2029], ['image' => 'campaign-logos/2029.png']);
        $html = (new \App\Mail\MagicLinkMail('https://example.test/magic'))->render();
        $this->assertStringContainsString('Melhores do Ano 2029', $html);
        $this->assertStringContainsString('campaign-logos/2029.png', $html);
        $this->assertStringNotContainsString('2025', $html);
    }

    public function test_admin_deactivation_excludes_awards_from_both_draw_flows(): void
    {
        $this->configure();
        $award = Award::factory()->create(['year' => 2026]);
        $admin = User::factory()->create();
        foreach (['ViewAny:Award', 'Update:Award'] as $name) {
            $admin->givePermissionTo(Permission::findOrCreate($name, 'web'));
        }
        $this->actingAs($admin);
        Livewire::test(\App\Filament\Resources\AwardResource\Pages\ListAwards::class)
            ->callTableAction('toggleActive', $award)->assertHasNoErrors();
        $this->assertFalse($award->fresh()->is_active);
        $this->postJson(route('sorteio.draw', $award))->assertStatus(422);
        Award::factory()->create(['year' => 2025, 'is_active' => true]);
        Award::factory()->create(['year' => 2027, 'is_active' => true]);
        $weekly = new \App\Filament\Pages\WeeklyDraw;
        $this->assertSame([], $weekly->getAvailableAwards());
        $weekly->runDraw();
        $this->assertDatabaseCount('award_draws', 0);
    }

    public function test_draw_eligibility_does_not_count_votes_from_another_edition(): void
    {
        $this->configure();
        $category = Category::factory()->create();
        $company = Company::factory()->create();
        $audience = Audience::factory()->create();
        Vote::create(['audience_id' => $audience->id, 'company_id' => $company->id, 'category_id' => $category->id, 'year' => 2025]);
        $award = Award::factory()->create(['year' => 2026, 'min_votes' => 1]);
        $this->actingAs(User::factory()->create())->postJson(route('sorteio.draw', $award))
            ->assertStatus(422)->assertJsonPath('message', 'Não há participantes elegíveis para este prêmio.');
        $this->assertDatabaseCount('award_draws', 0);
    }

    public function test_settings_reject_unprivileged_users(): void
    {
        $this->actingAs(User::factory()->create());
        $this->get(CampaignSettings::getUrl())->assertForbidden();
    }

    public function test_excel_export_is_a_real_xlsx_with_literal_untrusted_text(): void
    {
        $response = ExcelExport::download('relatorio.xlsx', ['Empresa', 'Votos'], [['=HYPERLINK("https://example.com")', 12]]);
        $path = $response->getFile()->getPathname();
        try {
            $zip = new \ZipArchive;
            $this->assertTrue($zip->open($path));
            $sheet = $zip->getFromName('xl/worksheets/sheet1.xml');
            $this->assertStringContainsString('HYPERLINK', $sheet);
            $this->assertStringNotContainsString('<f>', $sheet);
            $this->assertStringContainsString('<v>12</v>', $sheet);
            $zip->close();
            $this->assertSame('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', $response->headers->get('Content-Type'));
        } finally {
            unlink($path);
        }
    }
}

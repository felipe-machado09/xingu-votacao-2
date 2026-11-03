<?php

namespace Tests\Feature;

use App\Filament\Pages\CampaignSettings;
use App\Filament\Pages\CategoryResults;
use App\Filament\Pages\ManageTvSessions;
use App\Filament\Resources\AwardResource\Pages\CreateAward;
use App\Filament\Resources\AwardResource\Pages\ListAwards;
use App\Filament\Resources\CategoryResource\Pages\CreateCategory;
use App\Filament\Resources\CategoryResource\Pages\EditCategory;
use App\Filament\Resources\CompanyResource\Pages\CreateCompany;
use App\Filament\Resources\CompanyResource\Pages\EditCompany;
use App\Filament\Resources\CompanyResource\Pages\ListCompanies;
use App\Models\Audience;
use App\Models\Award;
use App\Models\Category;
use App\Models\Company;
use App\Models\LandingPageSection;
use App\Models\TvSession;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class AdminInteractionsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $admin = User::factory()->create();
        foreach (['Award', 'Category', 'Company', 'Sponsor', 'Winner', 'LandingPageSection', 'User', 'Audience', 'Vote'] as $model) {
            foreach (['ViewAny', 'View', 'Create', 'Update', 'Delete', 'DeleteAny'] as $action) {
                $admin->givePermissionTo(Permission::findOrCreate($action.':'.$model, 'web'));
            }
        }
        $this->actingAs($admin);
    }

    public function test_category_name_generates_slug_and_edit_preserves_the_public_link(): void
    {
        Livewire::test(CreateCategory::class)
            ->set('data.name', 'Categoria de Teste')
            ->assertSet('data.slug', 'categoria-de-teste')
            ->call('create')
            ->assertHasNoFormErrors();

        $category = Category::where('slug', 'categoria-de-teste')->firstOrFail();
        Livewire::test(EditCategory::class, ['record' => $category->id])
            ->set('data.name', 'Categoria atualizada')
            ->assertSet('data.slug', 'categoria-de-teste')
            ->call('save')
            ->assertHasNoFormErrors();
        $this->assertSame('Categoria atualizada', $category->fresh()->name);

        Livewire::test(CreateCategory::class)
            ->fillForm(['name' => 'Duplicada', 'slug' => 'categoria-de-teste'])
            ->call('create')
            ->assertHasFormErrors(['slug' => 'unique']);
    }

    public function test_legacy_company_name_is_visible_searchable_and_prefilled_without_changing_its_url(): void
    {
        Storage::fake('public');
        $logo = UploadedFile::fake()->image('logo.png')->store('company-logos', 'public');
        $category = Category::factory()->create();
        $company = Company::factory()->create([
            'trade_name' => null, 'legal_name' => 'Empresa Legada',
            'cnpj' => '11222333000181', 'logo_path' => $logo,
        ]);
        $company->categories()->attach($category);
        $other = Company::factory()->create(['trade_name' => 'Alfa Empresa']);

        $table = Livewire::test(ListCompanies::class)
            ->assertSee('Empresa Legada')
            ->searchTable('Empresa Legada')
            ->assertCanSeeTableRecords([$company]);
        $table->searchTable('')->sortTable('trade_name', 'asc');
        $this->assertSame([$other->id, $company->id], $table->instance()->getTableRecords()->pluck('id')->all());
        Livewire::test(EditCompany::class, ['record' => $company->slug])
            ->assertFormSet(['trade_name' => 'Empresa Legada'])
            ->call('save')
            ->assertHasNoFormErrors();
        $this->assertSame($company->slug, $company->fresh()->slug);
        $this->assertSame('Empresa Legada', $company->fresh()->trade_name);
    }

    public function test_admin_company_registration_saves_requested_fields_upload_and_categories(): void
    {
        Storage::fake('public');
        $category = Category::factory()->create();
        Livewire::test(CreateCompany::class)
            ->fillForm([
                'trade_name' => 'Empresa Nova', 'cnpj' => '11222333000181',
                'whatsapp_number' => '93999991234',
                'instagram_url' => 'https://instagram.com/empresa',
                'logo_path' => UploadedFile::fake()->image('logo.png'),
                'categories' => [$category->id],
            ])
            ->call('create')
            ->assertHasNoFormErrors();
        $company = Company::where('trade_name', 'Empresa Nova')->firstOrFail();
        $this->assertSame('empresa-nova', $company->slug);
        $this->assertSame('https://instagram.com/empresa', $company->instagram_url);
        $this->assertTrue($company->categories->contains($category));
        Storage::disk('public')->assertExists($company->logo_path);
    }

    public function test_award_create_requirements_and_activation_actions(): void
    {
        Livewire::test(CreateAward::class)
            ->fillForm(['name' => 'Prêmio de Teste', 'year' => 2026, 'tier' => 2, 'min_votes' => 7, 'quantity' => 1])
            ->call('create')->assertHasNoFormErrors();
        $award = Award::where('name', 'Prêmio de Teste')->firstOrFail();
        $this->assertSame(7, $award->requiredVotes());
        $table = Livewire::test(ListAwards::class)
            ->assertSee($award->requirementLabel())
            ->callTableAction('toggleActive', $award);
        $this->assertFalse($award->fresh()->is_active);
        $table->callTableAction('toggleActive', $award);
        $this->assertTrue($award->fresh()->is_active);

        Livewire::test(CreateAward::class)
            ->fillForm(['name' => 'Quantidade inválida', 'year' => 2026, 'tier' => 1, 'min_votes' => 1, 'quantity' => 1.5])
            ->call('create')->assertHasFormErrors(['quantity' => 'integer']);
    }

    public function test_content_resources_create_edit_and_delete_and_winner_defaults_to_campaign_year(): void
    {
        LandingPageSection::updateOrCreate(['key' => 'campaign'], ['metadata' => ['year' => 2027], 'is_active' => true]);
        foreach ([
            ['Sponsor', \App\Models\Sponsor::class, ['name' => 'Conteúdo de teste', 'website' => 'https://example.test', 'order' => 1]],
            ['Winner', \App\Models\Winner::class, ['name' => 'Conteúdo de teste', 'category' => 'Categoria', 'order' => 1]],
            ['LandingPageSection', LandingPageSection::class, ['key' => 'test-section', 'title' => 'Conteúdo de teste', 'order' => 1]],
        ] as [$resource, $model, $data]) {
            $namespace = 'App\\Filament\\Resources\\'.$resource.'Resource\\Pages\\';
            $create = Livewire::test($namespace.'Create'.$resource);
            if ($resource === 'Winner') {
                $create->assertFormSet(['year' => 2027]);
            }
            $create->fillForm($data)->call('create')->assertHasNoFormErrors();
            $record = $model::where(array_key_first($data), reset($data))->firstOrFail();
            $field = $resource === 'LandingPageSection' ? 'title' : 'name';
            $edit = Livewire::test($namespace.'Edit'.$resource, ['record' => $record->getRouteKey()])
                ->fillForm([$field => 'Conteúdo atualizado'])->call('save')->assertHasNoFormErrors();
            $this->assertSame('Conteúdo atualizado', $record->fresh()->getAttribute($field));
            $edit->callAction('delete');
            $this->assertModelMissing($record);
        }
    }

    public function test_user_password_is_saved_and_blank_edit_does_not_replace_it(): void
    {
        Livewire::test(\App\Filament\Resources\UserResource\Pages\CreateUser::class)
            ->fillForm(['name' => 'Operador', 'email' => 'operador@example.test', 'password' => 'senha-de-teste-123'])
            ->call('create')->assertHasNoFormErrors();
        $user = User::where('email', 'operador@example.test')->firstOrFail();
        $this->assertTrue(Hash::check('senha-de-teste-123', $user->password));
        Livewire::test(\App\Filament\Resources\UserResource\Pages\EditUser::class, ['record' => $user->id])
            ->fillForm(['name' => 'Operador atualizado', 'password' => ''])
            ->call('save')->assertHasNoFormErrors();
        $this->assertSame($user->password, $user->fresh()->password);
    }

    public function test_tv_activation_deactivation_reactivation_and_delete_actions(): void
    {
        $session = TvSession::create(['code' => '012345', 'is_active' => false]);
        $table = Livewire::test(ManageTvSessions::class)
            ->callAction('activateTv', data: ['activationCode' => '012345', 'tvName' => 'TV de Teste'])
            ->assertHasNoActionErrors();
        $this->assertTrue($session->fresh()->isAuthorized());
        $this->assertSame('TV de Teste', $session->fresh()->name);
        $table->callTableAction('deactivate', $session);
        $this->assertFalse($session->fresh()->isAuthorized());
        $table->callTableAction('activate', $session);
        $this->assertTrue($session->fresh()->isAuthorized());
        $table->callTableAction('delete', $session);
        $this->assertModelMissing($session);
    }

    public function test_tv_activation_rejects_non_digit_and_short_codes(): void
    {
        foreach (['12345', 'abcdef'] as $code) {
            Livewire::test(ManageTvSessions::class)
                ->callAction('activateTv', data: ['activationCode' => $code, 'tvName' => ''])
                ->assertHasActionErrors(['activationCode']);
        }
    }

    public function test_campaign_logo_and_regulation_uploads_are_saved_with_the_edition(): void
    {
        Storage::fake('public');
        Livewire::test(CampaignSettings::class)
            ->fillForm([
                'year' => 2027, 'end_date' => now()->addMonth()->toDateTimeString(),
                'logo' => UploadedFile::fake()->image('logo.png'),
                'regulation' => UploadedFile::fake()->create('regulamento.pdf', 32, 'application/pdf'),
                'company_registration_enabled' => true, 'randomize_companies' => true,
            ])
            ->call('save')->assertHasNoFormErrors();
        $campaign = LandingPageSection::getByKey('campaign');
        $this->assertSame(2027, LandingPageSection::campaignYear());
        Storage::disk('public')->assertExists($campaign->image);
        Storage::disk('public')->assertExists($campaign->document_path);
        $this->get('/')->assertOk()->assertSee($campaign->image)->assertSee($campaign->document_path);
    }

    public function test_save_and_create_another_and_bulk_delete_actions(): void
    {
        Livewire::test(CreateCategory::class)
            ->fillForm(['name' => 'Categoria temporária', 'slug' => 'categoria-temporaria'])
            ->call('create', true)->assertHasNoFormErrors()
            ->assertFormSet(['name' => null, 'slug' => null]);
        $this->assertDatabaseHas('categories', ['slug' => 'categoria-temporaria']);
        $companies = Company::factory()->count(2)->create();
        Livewire::test(ListCompanies::class)->callTableBulkAction('delete', $companies);
        foreach ($companies as $company) {
            $this->assertModelMissing($company);
        }
    }

    public function test_category_ranking_opens_closes_and_displays_winner_trade_name(): void
    {
        $category = Category::factory()->create();
        $company = Company::factory()->create(['legal_name' => 'Razão Social', 'trade_name' => 'Nome Fantasia']);
        $audience = Audience::factory()->create();
        Vote::create([
            'category_id' => $category->id, 'company_id' => $company->id,
            'audience_id' => $audience->id, 'year' => 2026,
        ]);
        $table = Livewire::test(CategoryResults::class)
            ->callTableAction('viewResults', $category)
            ->assertSet('selectedCategoryId', $category->id)
            ->assertSee('Fechar ranking')
            ->assertSee('Definir Vencedor')
            ->call('closeResults')
            ->assertSet('selectedCategoryId', null);
        $table->call('selectCategory', $category->id)->call('setWinner', $company->id)
            ->assertSet('selectedCategoryId', null)->assertSee('Nome Fantasia');
    }
}

<?php

namespace Tests\Feature;

use App\Models\Audience;
use App\Models\Award;
use App\Models\AwardDraw;
use App\Models\Category;
use App\Models\Company;
use App\Models\LandingPageSection;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DynamicAwardRequirementsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        LandingPageSection::where('key', 'campaign')->update(['metadata' => ['year' => 2026]]);
    }

    public function test_each_award_displays_its_configured_threshold_and_progress(): void
    {
        $audience = Audience::factory()->create();
        $company = Company::factory()->create();
        foreach (Category::factory()->count(7)->create() as $category) {
            Vote::create(['audience_id' => $audience->id, 'company_id' => $company->id, 'category_id' => $category->id]);
        }
        Award::factory()->create(['tier' => 1, 'min_votes' => 7]);
        Award::factory()->create(['tier' => 1, 'min_votes' => 11]);
        Award::factory()->create(['tier' => 2, 'min_votes' => 9]);
        Award::factory()->create(['tier' => 1, 'min_votes' => 1, 'is_active' => false]);
        Award::factory()->create(['tier' => 1, 'min_votes' => 2, 'year' => 2025]);
        $this->get('/')->assertOk()->assertSee('a partir de 7 votos')->assertDontSee('5+ empresas');
        $this->withSession(['audience_id' => $audience->id])->get('/vote')->assertOk()
            ->assertSee('7 categorias')->assertSee('11 categorias')->assertSee('9 categorias')
            ->assertSee('7 / 7 votos')->assertSee('7 / 11 votos')->assertSee('Faltam 4 votos')
            ->assertSee('Requisito de votos atingido')->assertDontSee('Prêmios Nível 1 e 2');
    }

    public function test_changing_or_exhausting_an_award_updates_the_promotion(): void
    {
        $award = Award::factory()->create(['tier' => 1, 'min_votes' => 8, 'quantity' => 1]);
        $this->get('/')->assertOk()->assertSee('a partir de 8 votos');
        $award->update(['min_votes' => 12]);
        $this->get('/')->assertOk()->assertSee('a partir de 12 votos')->assertDontSee('a partir de 8 votos');
        AwardDraw::create(['award_id' => $award->id, 'audience_id' => Audience::factory()->create()->id, 'status' => 'completed', 'drawn_at' => now()]);
        $this->get('/')->assertOk()->assertDontSee('a partir de 12 votos');
        $this->get('/vote')->assertOk()->assertDontSee($award->name)->assertDontSee('Concorra aos Prêmios!');
    }

    public function test_all_categories_requirement_ignores_inactive_votes_and_survives_closure(): void
    {
        $audience = Audience::factory()->create();
        $company = Company::factory()->create();
        $active = Category::factory()->count(2)->create(['voting_ends_at' => now()->subDay()]);
        $inactive = Category::factory()->create(['is_active' => false]);
        foreach ([$active[0], $inactive] as $category) {
            Vote::create(['audience_id' => $audience->id, 'company_id' => $company->id, 'category_id' => $category->id]);
        }
        $award = Award::factory()->create(['tier' => 3, 'min_votes' => 999, 'quantity' => 1]);
        $this->assertSame(2, $award->requiredVotes());
        $this->withSession(['audience_id' => $audience->id])->get('/vote')->assertOk()
            ->assertSee('todas as categorias')->assertSee('1 / 2 votos')->assertSee('Faltam 1 votos');
        $this->actingAs(User::factory()->create())->postJson(route('sorteio.draw', $award))->assertStatus(422);
        Vote::create(['audience_id' => $audience->id, 'company_id' => $company->id, 'category_id' => $active[1]->id]);
        $this->get('/vote')->assertOk()->assertSee('2 / 2 votos')->assertSee('Requisito de votos atingido');
        $this->postJson(route('sorteio.draw', $award))->assertOk()->assertJsonPath('success', true);
    }

    public function test_all_categories_awards_cannot_unlock_when_no_category_is_active(): void
    {
        $award = Award::factory()->create(['tier' => 3, 'min_votes' => 999]);
        $this->get('/vote')->assertOk()->assertDontSee($award->name);
        $this->actingAs(User::factory()->create())->postJson(route('sorteio.draw', $award))->assertStatus(422);
        $this->assertDatabaseCount('award_draws', 0);
    }
}

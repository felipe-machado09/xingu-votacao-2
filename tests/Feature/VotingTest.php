<?php

namespace Tests\Feature;

use App\Models\Audience;
use App\Models\Category;
use App\Models\Company;
use App\Models\Vote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VotingTest extends TestCase
{
    use RefreshDatabase;

    public function test_audience_can_vote_once_per_category(): void
    {
        $audience = Audience::factory()->create(['email_verified_at' => now()]);
        $category = Category::factory()->create(['is_active' => true]);
        $company1 = Company::factory()->create();
        $company2 = Company::factory()->create();
        
        $category->companies()->attach([$company1->id, $company2->id]);

        session(['audience_id' => $audience->id]);

        $response = $this->post(route('vote.store', [$category, $company1]));
        $response->assertRedirect();

        $this->assertDatabaseHas('votes', [
            'audience_id' => $audience->id,
            'category_id' => $category->id,
            'company_id' => $company1->id,
        ]);

        $response = $this->post(route('vote.store', [$category, $company2]));
        $response->assertSessionHasErrors();

        $this->assertEquals(1, Vote::where('audience_id', $audience->id)
            ->where('category_id', $category->id)
            ->count());
    }

    public function test_cannot_vote_for_company_not_in_category(): void
    {
        $audience = Audience::factory()->create(['email_verified_at' => now()]);
        $category = Category::factory()->create(['is_active' => true]);
        $company = Company::factory()->create();
        $otherCompany = Company::factory()->create();
        
        $category->companies()->attach($company->id);

        session(['audience_id' => $audience->id]);

        $response = $this->post(route('vote.store', [$category, $otherCompany]));
        $response->assertSessionHasErrors();

        $this->assertDatabaseMissing('votes', [
            'audience_id' => $audience->id,
            'category_id' => $category->id,
            'company_id' => $otherCompany->id,
        ]);
    }

    public function test_guest_cannot_vote(): void
    {
        $category = Category::factory()->create(['is_active' => true]);
        $company = Company::factory()->create();
        
        $category->companies()->attach($company->id);

        $response = $this->post(route('vote.store', [$category, $company]));
        $response->assertForbidden();
    }
}

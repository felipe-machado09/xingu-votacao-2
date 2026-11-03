<?php

namespace Tests\Feature;

use App\Mail\MagicLinkMail;
use App\Models\Audience;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class PublicInteractionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_validates_sends_access_email_and_preserves_category_destination(): void
    {
        Mail::fake();
        $category = Category::factory()->create();
        $this->withSession(['voting_category_slug' => $category->slug])->post('/register', [
            'name' => 'Participante de Teste', 'email' => 'participante@example.test',
            'birth_date' => '1990-01-01', 'phone' => '(93) 99999-1234',
        ])->assertRedirect('/')->assertSessionHasNoErrors();
        $audience = Audience::where('email', 'participante@example.test')->firstOrFail();
        $this->assertSame('93999991234', $audience->phone);
        Mail::assertSent(MagicLinkMail::class, fn ($mail) => $mail->hasTo($audience->email));
        $url = URL::temporarySignedRoute('auth.magic', now()->addMinutes(30), [
            'audience' => $audience->id, 'category' => $category->slug,
        ]);
        $this->get($url)->assertRedirect(route('vote.show', $category))
            ->assertSessionHas('audience_id', $audience->id);
        $this->post('/register', [
            'name' => 'Duplicado', 'email' => $audience->email,
            'birth_date' => now()->addDay()->toDateString(), 'phone' => '',
        ])->assertSessionHasErrors(['email', 'birth_date', 'phone']);
        $this->assertDatabaseCount('audiences', 1);
    }

    public function test_login_validates_unknown_email_and_sends_access_for_existing_participant(): void
    {
        Mail::fake();
        $audience = Audience::factory()->create();
        $this->post('/login', ['email' => 'ausente@example.test'])->assertSessionHasErrors('email');
        Mail::assertNothingSent();
        $this->post('/login', ['email' => $audience->email])->assertRedirect('/')->assertSessionHasNoErrors();
        Mail::assertSent(MagicLinkMail::class, fn ($mail) => $mail->hasTo($audience->email));
    }

    public function test_public_logout_does_not_log_the_administrator_out_of_the_panel(): void
    {
        $admin = User::factory()->create();
        $audience = Audience::factory()->create();
        $adminSessionKey = Auth::guard('web')->getName();
        $this->withSession([
            $adminSessionKey => $admin->id,
            'audience_id' => $audience->id,
            'audience_name' => $audience->name,
            'voting_category_slug' => 'categoria',
        ])->post('/logout')->assertRedirect('/')
            ->assertSessionMissing('audience_id')
            ->assertSessionMissing('audience_name')
            ->assertSessionMissing('voting_category_slug')
            ->assertSessionHas($adminSessionKey, $admin->id);
    }

    public function test_company_registration_displays_checkbox_categories_and_retains_selection_after_validation_errors(): void
    {
        $category = Category::factory()->create();
        $this->get('/empresa/cadastro')->assertOk()
            ->assertSee('type="checkbox" name="categoria_id[]"', false)
            ->assertSee($category->name);
        $this->from('/empresa/cadastro')->post('/empresa/cadastro', [
            'trade_name' => 'Empresa', 'categoria_id' => [$category->id],
        ])->assertRedirect('/empresa/cadastro')
            ->assertSessionHasErrors(['cnpj', 'whatsapp_number', 'logo_path', 'lgpd']);
        $this->get('/empresa/cadastro')->assertOk()->assertSee('value="'.$category->id.'" checked', false);
    }
}

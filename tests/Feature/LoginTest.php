<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function login_form_is_accessible()
    {
        $response = $this->get(route('login'));
        $response->assertStatus(200);
        $response->assertSee('Masuk ke Akun Anda');
    }

    /** @test */
    public function user_can_login_with_correct_credentials()
    {
        $user = User::factory()->create([
            'email' => 'admin@smaharmoni.id',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post(route('login'), [
            'email' => 'admin@smaharmoni.id',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/home'); // Ganti jika redirect-nya berbeda
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function user_cannot_login_with_invalid_password()
    {
        $user = User::factory()->create([
            'email' => 'agus@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->from(route('login'))->post(route('login'), [
            'email' => 'agus@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('email'); // Laravel menaruh error login di 'email'
        $this->assertGuest();
    }

    /** @test */
    public function remember_me_functionality_works()
    {
        $user = User::factory()->create([
            'email' => 'agus@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post(route('login'), [
            'email' => 'agus@example.com',
            'password' => 'password123',
            'remember' => 'on',
        ]);

        $response->assertRedirect('/home'); // Ganti dengan tujuan redirect
        $this->assertAuthenticatedAs($user);
    }
}

<?php

use App\Models\User;
use App\Enums\UserRole;

describe('Authentication', function () {
    describe('Login', function () {
        it('allows admin to login with valid credentials', function () {
            $user = User::factory()->create([
                'role' => UserRole::Admin,
                'username' => 'admin_test',
                'password' => bcrypt('password'),
            ]);

            $response = $this->post(route('login'), [
                'username' => 'admin_test',
                'password' => 'password',
            ]);

            $response->assertRedirect(route('dashboard'));
            $this->assertAuthenticatedAs($user);
        });

        it('allows bidan to login with valid credentials', function () {
            $user = User::factory()->create([
                'role' => UserRole::Bidan,
                'username' => 'bidan_test',
                'password' => bcrypt('password'),
            ]);

            $response = $this->post(route('login'), [
                'username' => 'bidan_test',
                'password' => 'password',
            ]);

            $response->assertRedirect(route('dashboard'));
            $this->assertAuthenticatedAs($user);
        });

        it('allows kader to login with valid credentials', function () {
            $user = User::factory()->create([
                'role' => UserRole::Kader,
                'username' => 'kader_test',
                'password' => bcrypt('password'),
            ]);

            $response = $this->post(route('login'), [
                'username' => 'kader_test',
                'password' => 'password',
            ]);

            $response->assertRedirect(route('dashboard'));
            $this->assertAuthenticatedAs($user);
        });

        it('redirects peserta to portal after login', function () {
            $user = User::factory()->create([
                'role' => UserRole::Peserta,
                'username' => 'peserta_test',
                'password' => bcrypt('password'),
            ]);

            $response = $this->post(route('login'), [
                'username' => 'peserta_test',
                'password' => 'password',
            ]);

            $response->assertRedirect(route('portal.index'));
            $this->assertAuthenticatedAs($user);
        });

        it('fails login with invalid password', function () {
            $user = User::factory()->create([
                'username' => 'test_user',
                'password' => bcrypt('password'),
            ]);

            $response = $this->post(route('login'), [
                'username' => 'test_user',
                'password' => 'wrong_password',
            ]);

            $response->assertSessionHasErrors('username');
            $this->assertGuest();
        });

        it('fails login with non-existent username', function () {
            $response = $this->post(route('login'), [
                'username' => 'nonexistent',
                'password' => 'password',
            ]);

            $response->assertSessionHasErrors('username');
            $this->assertGuest();
        });

        it('inactive user cannot login', function () {
            $user = User::factory()->create([
                'username' => 'inactive_user',
                'password' => bcrypt('password'),
                'is_active' => false,
            ]);

            $response = $this->post(route('login'), [
                'username' => 'inactive_user',
                'password' => 'password',
            ]);

            $response->assertSessionHasErrors('username');
            $this->assertGuest();
        });
    });

    describe('Logout', function () {
        it('allows authenticated user to logout', function () {
            $user = User::factory()->create();

            $this->actingAs($user);

            $response = $this->post(route('logout'));

            $response->assertRedirect('/');
            $this->assertGuest();
        });

        it('redirects to login when accessing protected route after logout', function () {
            $user = User::factory()->create();

            $this->actingAs($user);
            $this->post(route('logout'));

            $this->get(route('dashboard'))
                ->assertRedirect(route('login'));
        });
    });

    describe('Password Update', function () {
        it('allows user to update password', function () {
            $user = User::factory()->create([
                'password' => bcrypt('old_password'),
            ]);

            $this->actingAs($user);

            $response = $this->patch(route('password.update'), [
                'current_password' => 'old_password',
                'password' => 'new_password',
                'password_confirmation' => 'new_password',
            ]);

            $response->assertSessionHasNoErrors();
        });

        it('fails password update with wrong current password', function () {
            $user = User::factory()->create([
                'password' => bcrypt('correct_password'),
            ]);

            $this->actingAs($user);

            $response = $this->patch(route('password.update'), [
                'current_password' => 'wrong_password',
                'password' => 'new_password',
                'password_confirmation' => 'new_password',
            ]);

            $response->assertSessionHasErrors('current_password');
        });

        it('validates password confirmation matches', function () {
            $user = User::factory()->create([
                'password' => bcrypt('old_password'),
            ]);

            $this->actingAs($user);

            $response = $this->patch(route('password.update'), [
                'current_password' => 'old_password',
                'password' => 'new_password',
                'password_confirmation' => 'different_password',
            ]);

            $response->assertSessionHasErrors('password');
        });

        it('validates password minimum length', function () {
            $user = User::factory()->create([
                'password' => bcrypt('old_password'),
            ]);

            $this->actingAs($user);

            $response = $this->patch(route('password.update'), [
                'current_password' => 'old_password',
                'password' => '123',
                'password_confirmation' => '123',
            ]);

            $response->assertSessionHasErrors('password');
        });
    });
});

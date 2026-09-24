<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ForcedPasswordChangeTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(string $role, bool $mustChangePassword): User
    {
        return User::create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => strtolower($role).'@example.com',
            'password' => Hash::make('temporary-password'),
            'must_change_password' => $mustChangePassword,
            'role' => $role,
            'status' => 'Active',
        ]);
    }

    public function test_student_flagged_for_password_change_is_redirected_after_login(): void
    {
        $user = $this->makeUser('Student', true);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'temporary-password',
        ]);

        $response->assertRedirect(route('password.change'));
    }

    public function test_teacher_flagged_for_password_change_is_redirected_after_login(): void
    {
        $user = $this->makeUser('Teacher', true);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'temporary-password',
        ]);

        $response->assertRedirect(route('password.change'));
    }

    public function test_admin_is_never_forced_to_change_password_even_if_flagged(): void
    {
        $user = $this->makeUser('Admin', true);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'temporary-password',
        ]);

        $response->assertRedirect('/admin/');
    }

    public function test_flagged_student_cannot_bypass_dashboard_via_direct_url(): void
    {
        $user = $this->makeUser('Student', true);

        $response = $this->actingAs($user)->get('/student/');

        $response->assertRedirect(route('password.change'));
    }

    public function test_student_can_change_password_and_is_no_longer_flagged(): void
    {
        $user = $this->makeUser('Student', true);

        $response = $this->actingAs($user)->put('/password/change', [
            'current_password' => 'temporary-password',
            'password' => 'brand-new-password',
            'password_confirmation' => 'brand-new-password',
        ]);

        $response->assertRedirect(route('student.dashboard'));

        $user->refresh();
        $this->assertFalse($user->must_change_password);
        $this->assertTrue(Hash::check('brand-new-password', $user->password));
    }

    public function test_password_change_requires_correct_current_password(): void
    {
        $user = $this->makeUser('Student', true);

        $response = $this->actingAs($user)->put('/password/change', [
            'current_password' => 'wrong-password',
            'password' => 'brand-new-password',
            'password_confirmation' => 'brand-new-password',
        ]);

        $response->assertSessionHasErrors('current_password');

        $user->refresh();
        $this->assertTrue($user->must_change_password);
    }

    public function test_unflagged_student_is_not_redirected_after_login(): void
    {
        $user = $this->makeUser('Student', false);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'temporary-password',
        ]);

        $response->assertRedirect(route('student.dashboard'));
    }
}

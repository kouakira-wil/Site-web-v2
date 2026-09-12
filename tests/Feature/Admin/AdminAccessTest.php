<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<int, array<int, string>>
     */
    public static function adminRoutes(): array
    {
        return [
            'dashboard' => ['admin.dashboard'],
            'projets' => ['admin.projects.index'],
            'nouveau projet' => ['admin.projects.create'],
            'articles' => ['admin.posts.index'],
            'nouvel article' => ['admin.posts.create'],
            'equipe' => ['admin.team.index'],
            'nouveau membre' => ['admin.team.create'],
            'temoignages' => ['admin.testimonials.index'],
            'nouveau temoignage' => ['admin.testimonials.create'],
            'textes du site' => ['admin.pages.index'],
            'recherche admin' => ['admin.search'],
            'messages' => ['admin.messages.index'],
            'lettre d information' => ['admin.newsletter.index'],
        ];
    }

    /**
     * @dataProvider adminRoutes
     */
    public function test_guests_are_redirected_to_login(string $routeName): void
    {
        $this->get(route($routeName))->assertRedirect(route('login'));
    }

    /**
     * @dataProvider adminRoutes
     */
    public function test_staff_can_open_every_back_office_page(string $routeName): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $this->actingAs($admin)->get(route($routeName))->assertOk();
    }

    public function test_a_user_without_a_staff_role_is_forbidden(): void
    {
        $outsider = User::factory()->create(['role' => 'guest']);

        $this->actingAs($outsider)->get(route('admin.dashboard'))->assertForbidden();
    }

    public function test_an_editor_reaches_the_back_office(): void
    {
        $editor = User::factory()->create(['role' => User::ROLE_EDITOR]);

        $this->actingAs($editor)->get(route('admin.dashboard'))->assertOk();
    }
}

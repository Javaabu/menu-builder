<?php

namespace Javaabu\MenuBuilder\Tests\Unit;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Javaabu\MenuBuilder\Tests\Controllers\UsersController;
use Illuminate\Support\Facades\Route;
use Javaabu\MenuBuilder\Tests\InteractsWithDatabase;
use Javaabu\MenuBuilder\Menu\MenuItem;
use Javaabu\MenuBuilder\Tests\Models\User;
use Javaabu\MenuBuilder\Tests\Policies\UserPolicy;
use Javaabu\MenuBuilder\Tests\TestCase;

class MenuItemTest extends TestCase
{
    use InteractsWithDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->runMigrations();
    }

    /** @test */
    public function it_can_set_the_menu_item_label(): void
    {
        $menu_item = MenuItem::make('Dashboard');

        $this->assertEquals('Dashboard', $menu_item->getLabel());
    }

    /** @test */
    public function it_can_set_the_menu_item_route(): void
    {
        Route::get('/home')->name('web.home');

        $menu_item = MenuItem::make('Dashboard')
                        ->route('web.home');

        $this->assertEquals('web.home', $menu_item->getRoute());
        $this->assertEquals('http://localhost/home', $menu_item->getLink());
    }

    /** @test */
    public function it_can_set_the_menu_item_route_with_a_parameter(): void
    {
        Route::get('/users/{id}')->name('users.show');

        $menu_item = MenuItem::make('Users')
            ->route('users.show', 1);

        $this->assertEquals('users.show', $menu_item->getRoute());
        $this->assertEquals('http://localhost/users/1', $menu_item->getLink());
    }

    /** @test */
    public function it_can_determine_active_state_from_route(): void
    {
        $active_item = MenuItem::make('Home')->route('web.home');
        $inactive_item = MenuItem::make('Settings')->route('web.settings');

        Route::get('/settings')->name('web.settings');
        Route::view('/home', 'active-link', compact('active_item', 'inactive_item'))->name('web.home');

        $this->visit('/home')
            ->seeElement('a[href="http://localhost/home"].active')
            ->seeElement('a[href="http://localhost/settings"]')
            ->dontSeeElement('a[href="http://localhost/settings"].active');
    }

    /** @test */
    public function it_can_determine_active_state_from_route_with_parameters(): void
    {
        $user_1 = User::factory()->create();
        $user_2 = User::factory()->create();

        $active_item = MenuItem::make('User 1')->route('users.action', [$user_1, 'edit']);
        $inactive_item = MenuItem::make('User 2')->route('users.action', [$user_2, 'edit']);

        Route::view('/users/{user}/{action}', 'active-link', compact('active_item', 'inactive_item'))->name('users.action');

        $this->visit('/users/1/edit')
            ->seeElement('a[href="http://localhost/users/1/edit"].active')
            ->seeElement('a[href="http://localhost/users/2/edit"]')
            ->dontSeeElement('a[href="http://localhost/users/2/edit"].active');
    }

    /** @test */
    public function it_can_set_the_menu_item_controller(): void
    {
        Route::get('/users', [UsersController::class, 'index']);

        $menu_item = MenuItem::make('Users')
            ->controller(UsersController::class);

        $this->assertEquals(UsersController::class, $menu_item->getController());
        $this->assertEquals('http://localhost/users', $menu_item->getLink());
    }

    /** @test */
    public function it_can_determine_active_state_from_controller(): void
    {
        Route::get('/home')->name('web.home');
        Route::get('/users', [UsersController::class, 'index']);

        $this->visit('/users')
            ->seeElement('a[href="http://localhost/users"].active')
            ->seeElement('a[href="http://localhost/home"]')
            ->dontSeeElement('a[href="http://localhost/home"].active');
    }

    /** @test */
    public function it_can_set_the_menu_item_url(): void
    {
        Route::get('/home')->name('web.home');

        $menu_item = MenuItem::make('Dashboard')
            ->url(route('web.home'));

        $this->assertEquals('http://localhost/home', $menu_item->getUrl());
        $this->assertEquals('http://localhost/home', $menu_item->getLink());
    }

    /** @test */
    public function it_can_determine_active_state_from_url(): void
    {
        Route::get('/settings')->name('web.settings');

        $active_item = MenuItem::make('Home')->url('http://localhost/home');
        $inactive_item = MenuItem::make('Settings')->url(route('web.settings'));

        Route::view('/home', 'active-link', compact('active_item', 'inactive_item'))->name('web.home');

        $this->visit('/home')
            ->seeElement('a[href="http://localhost/home"].active')
            ->seeElement('a[href="http://localhost/settings"]')
            ->dontSeeElement('a[href="http://localhost/settings"].active');
    }

    /** @test */
    public function it_can_determine_active_state_from_url_with_parameters(): void
    {
        $active_item = MenuItem::make('Home')->url('http://localhost/home?foo=bar');
        $inactive_item = MenuItem::make('Home 2')->url('http://localhost/home');

        Route::view('/home', 'active-link', compact('active_item', 'inactive_item'))->name('web.home');

        $this->visit('/home?foo=bar')
            ->seeElement('a[href="http://localhost/home?foo=bar"].active')
            ->seeElement('a[href="http://localhost/home"]')
            ->dontSeeElement('a[href="http://localhost/home"].active');
    }

    /** @test */
    public function it_uses_the_last_set_method_to_generate_the_link(): void
    {
        Route::get('/users', [UsersController::class, 'index']);
        Route::get('/settings')->name('web.settings');

        $menu_item = MenuItem::make('Users')
            ->url('http://localhost/foo')
            ->controller(UsersController::class)
            ->route('web.settings');

        $this->assertEquals('http://localhost/settings', $menu_item->getLink());
    }

    /** @test */
    public function it_can_determine_active_state_from_active_closure(): void
    {
        $active_item = MenuItem::make('Home')
                        ->url('http://localhost/home?foo=bar')
                        ->active(function (Request $request) {
                            return $request->query('foo') == 'bars';
                        });

        $inactive_item = MenuItem::make('Home 2')->url('http://localhost/home?foo=baz');

        Route::view('/home', 'active-link', compact('active_item', 'inactive_item'))->name('web.home');

        $this->visit('/home?foo=bars')
            ->seeElement('a[href="http://localhost/home?foo=bar"].active')
            ->seeElement('a[href="http://localhost/home?foo=baz"]')
            ->dontSeeElement('a[href="http://localhost/home?foo=baz"].active');
    }

    /** @test */
    public function it_can_set_the_menu_item_icon(): void
    {
        $menu_item = MenuItem::make('Dashboard')->icon('book');

        $this->assertEquals('zmdi zmdi-book', $menu_item->getIcon('zmdi zmdi-'));
    }

    /** @test */
    public function it_can_set_the_menu_item_permissions(): void
    {
        $this->assertEquals(['view_users'], MenuItem::make('Users')->permissions('view_users')->getPermissions());
        $this->assertEquals(['view_users', 'edit_users'], MenuItem::make('Users')->permissions('view_users', 'edit_users')->getPermissions());
        $this->assertEquals(['view_users', 'edit_users'], MenuItem::make('Users')->permissions(['view_users', 'edit_users'])->getPermissions());
    }

    /** @test */
    public function it_can_determine_if_a_user_can_view_the_menu_item_using_permissions(): void
    {
        Gate::define('view_users', function (User $user) {
            return $user->name == 'John';
        });

        Gate::define('edit_users', function (User $user) {
            return $user->name != 'John';
        });

        /** @var User $user */
        $user = User::factory() ->create(['name' => 'John']);

        // sanity check
        $this->assertTrue($user->can('view_users'));
        $this->assertFalse($user->can('edit_users'));

        $this->assertTrue(MenuItem::make('Users')->canView());
        $this->assertTrue(MenuItem::make('Users')->canView($user));
        $this->assertTrue(MenuItem::make('Users')->permissions('view_users')->canView($user));
        $this->assertTrue(MenuItem::make('Users')->permissions('view_users', 'edit_users')->canView($user));

        $this->assertFalse(MenuItem::make('Users')->permissions('view_users')->canView());
        $this->assertFalse(MenuItem::make('Users')->permissions('edit_users')->canView($user));
    }

    /** @test */
    public function it_can_determine_if_a_user_can_view_the_menu_item_using_can(): void
    {
        Gate::policy(User::class, UserPolicy::class);

        /** @var User $user */
        $user = User::factory() ->create(['name' => 'John']);
        $other_user = User::factory() ->create(['name' => 'Doe']);

        // sanity check
        $this->assertTrue($user->can('viewAny', User::class));
        $this->assertTrue($user->can('update', $user));
        $this->assertFalse($user->can('update', $other_user));

        $this->assertTrue(MenuItem::make('Users')->can('viewAny', User::class)->canView($user));
        $this->assertTrue(MenuItem::make('Users')->can('update', $user)->canView($user));
        $this->assertTrue(MenuItem::make('Users')->can(function ($user) { return $user->name == 'John';})->canView($user));

        $this->assertFalse(MenuItem::make('Users')->can('update', $other_user)->canView());
        $this->assertFalse(MenuItem::make('Users')->can(function ($user) { return $user->name != 'John';})->canView($user));
    }

    /** @test */
    public function it_can_determine_if_a_user_can_view_the_menu_item_using_both_can_and_permssions(): void
    {
        Gate::policy(User::class, UserPolicy::class);

        Gate::define('view_users', function (User $user) {
            return $user->name == 'John';
        });

        Gate::define('edit_users', function (User $user) {
            return $user->name != 'John';
        });

        /** @var User $user */
        $user = User::factory() ->create(['name' => 'John']);

        $this->assertFalse(MenuItem::make('Users')->can('viewAny', User::class)->permissions('edit_users')->canView($user));
        $this->assertTrue(MenuItem::make('Users')->can('viewAny', User::class)->permissions('view_users')->canView($user));
    }
}

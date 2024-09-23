<?php

namespace Javaabu\MenuBuilder\Tests\Feature;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Javaabu\MenuBuilder\MenuBuilder;
use Javaabu\MenuBuilder\Tests\InteractsWithDatabase;
use Javaabu\MenuBuilder\Tests\Menus\Sidebar;
use Javaabu\MenuBuilder\Tests\Models\User;
use Javaabu\MenuBuilder\Tests\TestCase;

class MenuTest extends TestCase
{
    use InteractsWithDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->runMigrations();
    }

    protected function setupTestRoutes(): void
    {
        $sidebar = new Sidebar();

        Route::view('/', 'sidebar', compact('sidebar'))->name('home');
    }

    protected function setupTestPermissions(): void
    {
        Gate::define('view_categories', function (User $user) {
            return $user->name == 'John';
        });

        Gate::define('view_returns', function (User $user) {
            return $user->name != 'John';
        });
    }

    /** @test */
    public function it_can_render_a_bootstrap_5_menu(): void
    {
        $this->setupTestRoutes();
        $this->setupTestPermissions();

        /** @var User $user */
        $user = User::factory()->create(['name' => 'John']);

        $this->actingAs($user);

        MenuBuilder::useBootstrap5();

        $this->visit('/');

        $actual_content = $this->response->getContent();

        $this->assertXmlStringEqualsXmlString($this->loadTestStub('bootstrap-5.blade.php'), $actual_content);
    }

    /** @test */
    public function it_can_render_a_material_admin_26_menu(): void
    {
        $this->setupTestRoutes();
        $this->setupTestPermissions();

        /** @var User $user */
        $user = User::factory()->create(['name' => 'John']);

        $this->actingAs($user);

        MenuBuilder::useMaterialAdmin26();

        $this->visit('/');

        $actual_content = $this->response->getContent();

        $expected_content = $this->loadTestStub('material-admin-26.blade.php');

        // remove extra whitespace
        $expected_content = preg_replace('/\s+/', ' ', $expected_content);
        $expected_content = str_replace("\n", '', $expected_content);

        // remove extra whitespace
        $actual_content = preg_replace('/\s+/', ' ', $actual_content);
        $actual_content = str_replace("\n", '', $actual_content);
        $actual_content = str_replace(' <', '<', $actual_content);
        $actual_content = trim($actual_content);

        $this->assertXmlStringEqualsXmlString($expected_content, $actual_content);
    }
}

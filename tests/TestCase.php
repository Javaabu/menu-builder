<?php

namespace Javaabu\MenuBuilder\Tests;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Javaabu\MenuBuilder\MenuBuilderServiceProvider;
use Orchestra\Testbench\BrowserKit\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected $baseUrl = 'http://localhost';

    protected function setUp(): void
    {
        parent::setUp();

        $this->app['config']->set('app.key', 'base64:yWa/ByhLC/GUvfToOuaPD7zDwB64qkc/QkaQOrT5IpE=');

        $this->app['config']->set('session.serialization', 'php');

        View::addLocation(__DIR__ . '/views');

    }

    protected function loadTestStub($stub): string
    {
        return file_get_contents(__DIR__ . '/stubs/' . $stub);
    }

    protected function getPackageProviders($app)
    {
        return [
            MenuBuilderServiceProvider::class,
        ];
    }
}

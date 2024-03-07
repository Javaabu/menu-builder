<?php

namespace Javaabu\MenuBuilder\Tests;

trait InteractsWithDatabase
{
    protected function runMigrations()
    {
        include_once __DIR__ . '/database/create_users_table.php';

        (new \CreateUsersTable)->up();
    }
}

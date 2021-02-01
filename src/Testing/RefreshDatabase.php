<?php

namespace MuhmdRaouf\LaravelParatest\Testing;

use Illuminate\Foundation\Testing\RefreshDatabase as BaseRefreshDatabase;

trait RefreshDatabase
{
    use BaseRefreshDatabase{
        refreshTestDatabase as protected parentRefreshTestDatabase;
    }

    protected function refreshTestDatabase()
    {
        $this->swapTestingDatabase();
        $this->parentRefreshTestDatabase();
    }

    protected function swapTestingDatabase(): void
    {
        $driver = config('database.default');
        $dbName = config("database.connections.$driver.database");

        // Paratest gives each process a unique TEST_TOKEN env variable.
        // When that's not set, we can default to 1 because it's
        // probably running on PHPUnit instead.
        $TEST_TOKEN = env('TEST_TOKEN', 1);
        config()->set("database.connections.$driver.database", "$dbName-$TEST_TOKEN");
    }
}

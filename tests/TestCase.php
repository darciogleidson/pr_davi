<?php

namespace Tests;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;

abstract class TestCase extends BaseTestCase
{
    public function createApplication(): Application
    {
        $application = parent::createApplication();
        $application['config']->set('database.default', 'sqlite');
        $application['config']->set('database.connections.sqlite.database', ':memory:');
        $application['config']->set('database.connections.sqlite.url', null);

        DB::purge('sqlite');

        return $application;
    }

    protected function tearDown(): void
    {
        if ($this->app !== null) {
            $this->app['auth']->logout();
            $this->app['session']->flush();
        }

        parent::tearDown();
    }
}

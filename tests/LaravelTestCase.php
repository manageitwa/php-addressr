<?php

namespace Tests;

use ManageItWA\PhpAddressr\ServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class LaravelTestCase extends BaseTestCase
{
    protected function getPackageProviders($app) 
    {
        return [
            ServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'config' => \Illuminate\Config\Repository::class,
        ];
    }
}

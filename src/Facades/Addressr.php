<?php

namespace ManageItWA\PhpAddressr\Facades;

use Illuminate\Support\Facades\Facade;
use ManageItWA\PhpAddressr\Addressr as LaravelAddressr;

class Addressr extends Facade
{
    protected static function getFacadeAccessor()
    {
        return LaravelAddressr::class;
    }
}
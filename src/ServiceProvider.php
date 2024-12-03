<?php

namespace ManageItWA\PhpAddressr;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use ManageItWA\PhpAddressr\ApiClient\LaravelHttp;

class ServiceProvider extends BaseServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->app->singleton(Addressr::class, function () {
            return new Addressr(
                new LaravelHttp(config('services.addressr.endpoint', ''))
            );
        });
    }

    public function provides()
    {
        return [
            Addressr::class,
        ];
    }
}
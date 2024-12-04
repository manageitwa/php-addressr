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
            /** @var string */
            $endpoint = config('services.addressr.endpoint', '');

            return new Addressr(
                new LaravelHttp((string) $endpoint)
            );
        });
    }

    /**
     * @return array<int, class-string>
     */
    public function provides()
    {
        return [
            Addressr::class,
        ];
    }
}
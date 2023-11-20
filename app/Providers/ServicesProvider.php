<?php

namespace App\Providers;

use App\Services\GetData;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\DeferrableProvider;

class ServicesProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            GetData::class,
            fn (Application $app) =>
                /**
                 * Idealmente seria el lugar para pasar todas las variables con configuracion del servicio como tokens,
                 * variables de autenticacion, url, rates, etc.
                 */
                new GetData(env('URL_SERVICE', ''))
        );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array<int, string>
     */
    public function provides(): array
    {
        return [
            GetData::class,
        ];
    }
}

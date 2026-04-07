<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\ArchivoAdjunto;
use App\Policies\ArchivoAdjuntoPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Políticas de autorización
     */
    protected $policies = [
        ArchivoAdjunto::class => ArchivoAdjuntoPolicy::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Registrar políticas
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use App\Models\Company;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
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
        try {
            View::composer('*', function ($view) {
                $view->with('sidebar_entreprises', Company::all());
            });
        } catch (\Exception $e) {
            // Pour eviter les erreurs lors des migrations initiales
        }
    }
}

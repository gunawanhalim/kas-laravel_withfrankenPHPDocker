<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use ConsoleTVs\Charts\Facades\Charts;
use ConsoleTVs\Charts\Builder\Chart;

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
        Paginator::useBootstrapFive();
        // Charts::register('kas_bank_chart', \App\Charts\KasBankChart::class);
    }

}

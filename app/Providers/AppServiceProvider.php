<?php

namespace App\Providers;

use App\Interfaces\AirportsRepositoryInterface;
use App\Interfaces\FlightsRepositoryInterface;
use App\Repository\AirportsRepository;
use App\Repository\FlightsRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
      $this->app->bind(AirportsRepositoryInterface::class, AirportsRepository::class);
      $this->app->bind(FlightsRepositoryInterface::class, FlightsRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

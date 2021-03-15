<?php

namespace App\Providers;

use App\Media\Concerns\WidthCalculator;
use App\Media\MediaWidthCalculator;
use Illuminate\Support\ServiceProvider;
use Spatie\MediaLibrary\ResponsiveImages\ResponsiveImageGenerator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }

        $this->app->bind(WidthCalculator::class, MediaWidthCalculator::class);
        $this->app->bind(ResponsiveImageGenerator::class, function ($app) {
            return $app->make(\App\Media\ResponsiveImageGenerator::class);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

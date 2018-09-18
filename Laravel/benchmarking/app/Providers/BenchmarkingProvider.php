<?php

namespace App\Providers;

use \App\Services\BenchmarkingService;
use Illuminate\Support\ServiceProvider;

class BenchmarkingProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(BenchmarkingService::class, function () {
			return new BenchmarkingService();
		});
    }
}

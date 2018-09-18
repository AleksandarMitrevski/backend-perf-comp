<?php

namespace App\Providers;

use \Memcache;
use Illuminate\Support\ServiceProvider;

class BenchmarkingStorageProvider extends ServiceProvider
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
		$this->app->singleton(Memcache::class, function () {
			$memcache = new Memcache;
			$memcache->connect('localhost', 11211);
			return $memcache;
		});
    }
}

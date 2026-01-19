<?php

namespace App\Providers;

use App\Repositories\FavoriteListRepository;
use App\Repositories\FavoriteListProductRepository;
use App\Services\ProductService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(FavoriteListRepository::class, function ($app) {
            return new FavoriteListRepository($app->make(ProductService::class));
        });

        $this->app->singleton(FavoriteListProductRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

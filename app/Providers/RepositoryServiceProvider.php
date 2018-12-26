<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
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
        $this->app->bind(\App\Repositories\DataOriginRepository::class, \App\Repositories\DataOriginRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\FortuneRepository::class, \App\Repositories\FortuneRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\FormInfoRepository::class, \App\Repositories\FormInfoRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\WechatSessionRepository::class, \App\Repositories\WechatSessionRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\ActivityRepository::class, \App\Repositories\ActivityRepositoryEloquent::class);
        //:end-bindings:
    }
}

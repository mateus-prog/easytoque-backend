<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(
            'App\Repositories\Contracts\MenuRepositoryInterface', 
            'App\Repositories\Elouquent\MenuRepository'
        );

        $this->app->bind(
            'App\Repositories\Contracts\MenuRoleRepositoryInterface', 
            'App\Repositories\Elouquent\MenuRoleRepository'
        );

        $this->app->bind(
            'App\Repositories\Contracts\UserRepositoryInterface', 
            'App\Repositories\Elouquent\UserRepository'
        );

        $this->app->bind(
            'App\Repositories\Contracts\UserCorporateRepositoryInterface', 
            'App\Repositories\Elouquent\UserCorporateRepository'
        );

        $this->app->bind(
            'App\Repositories\Contracts\UserStoreRepositoryInterface', 
            'App\Repositories\Elouquent\UserStoreRepository'
        );

        $this->app->bind(
            'App\Repositories\Contracts\StatusUserRepositoryInterface', 
            'App\Repositories\Elouquent\StatusUserRepository'
        );

        $this->app->bind(
            'App\Repositories\Contracts\StatusRequestRepositoryInterface', 
            'App\Repositories\Elouquent\StatusRequestRepository'
        );

        $this->app->bind(
            'App\Repositories\Contracts\RequestRepositoryInterface', 
            'App\Repositories\Elouquent\RequestRepository'
        );

        $this->app->bind(
            'App\Repositories\Contracts\RolesRepositoryInterface', 
            'App\Repositories\Elouquent\RolesRepository'
        );

        $this->app->bind(
            'App\Repositories\Contracts\StateRepositoryInterface',
            'App\Repositories\Elouquent\StateRepository'
        );

        $this->app->bind(
            'App\Repositories\Contracts\BankRepositoryInterface', 
            'App\Repositories\Elouquent\BankRepository'
        );

        $this->app->bind(
            'App\Repositories\Contracts\ReasonRepositoryInterface', 
            'App\Repositories\Elouquent\ReasonRepository'
        );
    }
}

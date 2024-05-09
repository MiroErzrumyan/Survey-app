<?php

namespace App\Providers;

use App\Contracts\AnswerContract;
use App\Contracts\GroupContract;
use App\Repositories\AnswerRepository;
use App\Repositories\GroupRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->singleton(
            GroupContract::class,
            GroupRepository::class
        );
        $this->app->singleton(
            AnswerContract::class,
            AnswerRepository::class
        );
    }
}

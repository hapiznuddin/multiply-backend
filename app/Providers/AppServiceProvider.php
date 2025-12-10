<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Repositories\Contracts\MaterialRepositoryInterface::class,
            \App\Repositories\Eloquent\MaterialRepository::class,
        );

        $this->app->bind(
            \App\Repositories\Contracts\QuestionRepositoryInterface::class,
            \App\Repositories\Eloquent\QuestionRepository::class,
        );

        $this->app->bind(
            \App\Repositories\Contracts\QuestionSetRepositoryInterface::class,
            \App\Repositories\Eloquent\QuestionSetRepository::class,
        );

        $this->app->bind(
            \App\Repositories\Contracts\RoomRepositoryInterface::class,
            \App\Repositories\Eloquent\RoomRepository::class,
        );

        $this->app->bind(
            \App\Repositories\Contracts\ParticipantRepositoryInterface::class,
            \App\Repositories\Eloquent\ParticipantRepository::class,
        );

        $this->app->bind(
            \App\Repositories\Contracts\AnswerRepositoryInterface::class,
            \App\Repositories\Eloquent\AnswerRepository::class,
        );

        $this->app->bind(
            \App\Repositories\Contracts\LearningModuleRepositoryInterface::class,
            \App\Repositories\Eloquent\LearningModuleRepository::class,
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

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
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Laravel\Sanctum\Sanctum::usePersonalAccessTokenModel(\App\Models\CachedPersonalAccessToken::class);

        $clearCache = fn() => \Illuminate\Support\Facades\Cache::flush();

        \App\Models\Job::saved($clearCache);
        \App\Models\Job::deleted($clearCache);
        \App\Models\Application::saved($clearCache);
        \App\Models\Application::deleted($clearCache);
        \App\Models\Interview::saved($clearCache);
        \App\Models\Interview::deleted($clearCache);
        \App\Models\TechnicalTask::saved($clearCache);
        \App\Models\TechnicalTask::deleted($clearCache);
        \App\Models\Candidate::saved($clearCache);
        \App\Models\Candidate::deleted($clearCache);
        \App\Models\TaskSubmission::saved($clearCache);
        \App\Models\TaskSubmission::deleted($clearCache);
    }
}

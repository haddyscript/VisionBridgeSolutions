<?php

namespace App\Providers;

use App\Models\IntakeSubmission;
use Illuminate\Support\Facades\View;
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
        View::composer('layouts.admin', function ($view) {
            $view->with('newIntakeCount', IntakeSubmission::where('status', 'new')->count());
        });
    }
}

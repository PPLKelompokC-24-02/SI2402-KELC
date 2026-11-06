<?php

namespace App\Providers;

use App\Models\ActionPlan;
use App\Models\ActionPlanTemplate;
use App\Models\CareerEvent;
use App\Models\Profession;
use App\Models\SavedProfession;
use App\Models\User;
use App\Policies\ActionPlanPolicy;
use App\Policies\ActionPlanTemplatePolicy;
use App\Policies\CareerEventPolicy;
use App\Policies\ProfessionPolicy;
use App\Policies\SavedProfessionPolicy;
use App\Policies\UserPolicy;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
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
        Gate::policy(Profession::class, ProfessionPolicy::class);
        Gate::policy(CareerEvent::class, CareerEventPolicy::class);
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(ActionPlanTemplate::class, ActionPlanTemplatePolicy::class);
        Gate::policy(ActionPlan::class, ActionPlanPolicy::class);
        Gate::policy(SavedProfession::class, SavedProfessionPolicy::class);
        RateLimiter::for('login', fn (Request $request) => [
            Limit::perMinute(30)->by($request->ip()),
            Limit::perMinute(5)->by(hash('sha256', mb_strtolower(is_string($request->input('email')) ? trim($request->input('email')) : '').'|'.$request->ip())),
        ]);
        RateLimiter::for('register', fn (Request $request) => Limit::perMinute(5)->by($request->ip()));
    }
}

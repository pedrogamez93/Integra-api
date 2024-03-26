<?php

namespace App\Providers;

use App\Post;
use App\User;
use App\Survey;
use App\Release;
use App\EducationalMaterial;
use App\Observers\PostObserver;
use App\Observers\UserObserver;
use App\Observers\SurveyObserver;
use App\Observers\ReleaseObserver;
use Illuminate\Support\ServiceProvider;
use App\Observers\EducationalMaterialObserver;

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
        User::observe(UserObserver::class);
        Post::observe(PostObserver::class);
        Release::observe(ReleaseObserver::class);
        EducationalMaterial::observe(EducationalMaterialObserver::class);
        Survey::observe(SurveyObserver::class);

    }
}

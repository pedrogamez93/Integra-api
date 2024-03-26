<?php

namespace App\Providers;

use App\Nova\Faq;
use App\Nova\Rol;
use App\Nova\Link;
use App\Nova\Post;
use App\Nova\Benefits;
use App\Nova\User;
use App\Nova\Termn;
use App\Nova\Region;
use App\Nova\Survey;
use App\Nova\Release;
use App\Nova\Category;
use App\Nova\District;
use App\Nova\Helpdesk;
use App\Nova\Position;
use App\Nova\UserNova;
use Laravel\Nova\Nova;
use App\Nova\Onboarding;
use App\Nova\UserRutList;
use App\Nova\welcomePage;
use Meat\ListRut\ListRut;
use App\Nova\Notification;
use App\Nova\TutorialItem;
use App\Nova\Constribution;
use App\Nova\GeneralOption;
use Laravel\Nova\Cards\Help;
use App\Nova\UserContribution;
use App\Nova\EducationalMaterial;
use App\Nova\SegmentedNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\NovaApplicationServiceProvider;
use Meat\NovaPushNotification\NovaPushNotification;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
            ->withAuthenticationRoutes()
            ->withPasswordResetRoutes()
            ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            return $user->is_admin;
        });
    }

    /**
     * Get the cards that should be displayed on the default Nova dashboard.
     *
     * @return array
     */
    protected function cards()
    {
        return [
            new Help,
        ];
    }

    /**
     * Get the extra dashboards that should be displayed on the Nova dashboard.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        if (Auth::user()->rol_id == 1 || Auth::user()->rol_id == 2 || Auth::user()->rol_id == 7) {
            return [
                new ListRut,
                new NovaPushNotification,
            ];
        }
        return [];
    }

    protected function resources()
    {
        Nova::resources([
            User::class,
            UserNova::class,
            Position::class,
            District::class,
            Region::class,
            Post::class,
            Benefits::class,
            Release::class,
            Link::class,
            Termn::class,
            welcomePage::class,
            Onboarding::class,
            GeneralOption::class,
            Faq::class,
            TutorialItem::class,
            Rol::class,
            Helpdesk::class,
            Notification::class,
            SegmentedNotification::class,
            EducationalMaterial::class,
            UserContribution::class,
            Constribution::class,
            Category::class,
            Survey::class,
            UserRutList::class,
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Post' => 'App\Policies\PostPolicy',
        'App\Release' => 'App\Policies\ReleasePolicy',
        'App\GeneralOption' => 'App\Policies\GeneralOptionPolicy',
        'App\Helpdesk' => 'App\Policies\HelpdeskPolicy',
        'App\Onboarding' => 'App\Policies\OnboardingPolicy',
        'App\Position' => 'App\Policies\PositionPolicy',
        'App\Region' => 'App\Policies\RegionPolicy',
        'App\Release' => 'App\Policies\ReleasePolicy',
        'App\Termn' => 'App\Policies\TermnPolicy',
        'App\TutorialItem' => 'App\Policies\TutorialItemPolicy',
        'App\UserNova' => 'App\Policies\UserNovaPolicy',
        'App\User' => 'App\Policies\UserPolicy',
        'App\welcomePage' => 'App\Policies\welcomePagePolicy',
        'App\Rol' => 'App\Policies\RolPolicy',
        'App\Notification' => 'App\Policies\NotificationPolicy',
        'App\SegmentedNotification' => 'App\Policies\SegmentedNotificationPolicy',
        'App\EducationalMaterial' => 'App\Policies\EducationalMaterialActivityPolicy',
        'App\VideoEducational' => 'App\Policies\EducationalMaterialVideoPolicy',
        'App\EducationalMaterial' => 'App\Policies\EducationalMaterialWorkPolicy',
        'App\UserContribution' => 'App\Policies\UserContributionPolicy',
        'App\Constribution' => 'App\Policies\ConstributionPolicy',
        'App\Survey' => 'App\Policies\SurveyPolicy',
        'App\Category' => 'App\Policies\CategoryPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Passport::routes();
        Passport::personalAccessTokensExpireIn(now()->addMinutes(15));
    }
}

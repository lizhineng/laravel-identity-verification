<?php

namespace LiZhineng\IdentityVerification;

use AlibabaCloud\Client\AlibabaCloud;
use Illuminate\Support\ServiceProvider;

class IdentityVerificationServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerMigrations();
        $this->registerPublishing();
        $this->registerAliyunClient();
    }

    protected function registerMigrations()
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->loadMigrationsFrom(__DIR__.'/../migrations');
    }

    protected function registerPublishing()
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__.'/../migrations' => database_path('migrations'),
        ], 'identity-verification-migrations');

        $this->publishes([
            __DIR__.'/../config/identity-verification.php' => config_path('identity-verification.php'),
        ], 'identity-verification-config');
    }

    protected function registerAliyunClient()
    {
        AlibabaCloud::accessKeyClient(config('identity-verification.key'), config('identity-verification.secret'))
            ->regionId(config('identity-verification.region'))
            ->name('identity-verification');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/identity-verification.php', 'identity-verification'
        );
    }
}
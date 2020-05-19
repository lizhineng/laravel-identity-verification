<?php

namespace LiZhineng\IdentityVerification;

use AlibabaCloud\Client\AlibabaCloud;
use Illuminate\Support\ServiceProvider;

class IdentityVerificationServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerPublishing();
        $this->registerAliyunClient();
    }

    protected function registerPublishing()
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

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
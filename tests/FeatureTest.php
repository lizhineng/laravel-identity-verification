<?php

namespace LiZhineng\IdentityVerification\Tests;

use AlibabaCloud\Client\AlibabaCloud;
use Illuminate\Foundation\Auth\User;
use LiZhineng\IdentityVerification\IdentityVerificationServiceProvider;
use Orchestra\Testbench\TestCase;

abstract class FeatureTest extends TestCase
{
    protected User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->loadLaravelMigrations();

        $this->user = User::forceCreate([
            'email' => 'testing@example.org',
            'name' => 'Testing',
            'password' => '$2y$10$E4BoLxKS61MkkrWKl5RTNeJPErqT0DNbvVTEblk05kAC5UAmfyGCK', // password
        ]);

        $this->artisan('migrate')->run();
    }

    protected function getPackageProviders($app)
    {
        return [IdentityVerificationServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testing');
    }
}
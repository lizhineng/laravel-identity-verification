<?php

namespace LiZhineng\IdentityVerification\Tests;

use AlibabaCloud\Client\AlibabaCloud;
use Illuminate\Foundation\Auth\User;
use LiZhineng\IdentityVerification\IdentityVerificationServiceProvider;
use Orchestra\Testbench\TestCase;
use Mockery;

abstract class FeatureTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->mockAlibabaCloud();
    }

    protected function mockAlibabaCloud()
    {
        AlibabaCloud::mockResponse(200);
    }

    protected function user()
    {
        return Mockery::mock(User::class);
    }

    protected function getPackageProviders($app)
    {
        return [IdentityVerificationServiceProvider::class];
    }
}
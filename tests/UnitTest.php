<?php

namespace LiZhineng\IdentityVerification\Tests;

use AlibabaCloud\Client\AlibabaCloud;
use Illuminate\Foundation\Auth\User;
use PHPUnit\Framework\TestCase;
use Mockery;

abstract class UnitTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->mockAlibabaCloud();
    }

    protected function mockAlibabaCloud()
    {
        AlibabaCloud::accessKeyClient('fake-id', 'fake-secret')
            ->regionId('fake-region')
            ->name('identity-verification');

        AlibabaCloud::mockResponse(200);
    }

    protected function user()
    {
        return Mockery::mock(User::class);
    }
}
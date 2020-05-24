<?php

namespace LiZhineng\IdentityVerification\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use LiZhineng\IdentityVerification\IdentityVerification;
use LiZhineng\IdentityVerification\VerifyIdentity;

class VerifyIdentityTest extends FeatureTest
{
    public function testItWorks()
    {
        $this->assertTrue($this->mockResponse()->verify()->isSuccess());
    }

    public function testStoreRecord()
    {
        $this->mockResponse()->verify();

        $this->assertEquals(1, IdentityVerification::count());
    }

    protected function verify()
    {
        return VerifyIdentity::manually()
            ->in('registration')
            ->for($this->user())
            ->uuid(Str::uuid())
            ->name('Zhineng')
            ->idNumber('000')
            ->portrait('fake-photo-path')
            ->verify();
    }
}
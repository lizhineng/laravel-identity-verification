<?php

namespace LiZhineng\IdentityVerification\Tests;

use Illuminate\Support\Str;
use LiZhineng\IdentityVerification\IdentityVerification;
use LiZhineng\IdentityVerification\VerifyIdentity;

class VerifyIdentityTest extends FeatureTest
{
    public function testItWorks()
    {
        $result = VerifyIdentity::manually()
            ->in('registration')
            ->for($this->user())
            ->uuid(Str::uuid())
            ->name('Zhineng')
            ->idNumber('000')
            ->portrait('fake-photo-path')
            ->verify();

        $this->assertTrue($result->isSuccess());
    }

    public function testSupportsOCR()
    {
        $this->assertTrue($this->verify()->isSuccess());
    }

    public function testStoreRecord()
    {
        $this->verify();

        $this->assertEquals(IdentityVerification::count(), 1);
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
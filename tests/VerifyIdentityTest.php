<?php

namespace LiZhineng\IdentityVerification\Tests;

use Illuminate\Foundation\Auth\User;
use LiZhineng\IdentityVerification\VerifyIdentity;
use Mockery;

class VerifyIdentityTest extends UnitTest
{
    public function testItWorks()
    {
        VerifyIdentity::manually()
            ->for($this->user())
            ->name('Zhineng')
            ->idNumber('000')
            ->portrait('fake-photo-path')
            ->verify();
    }

    protected function user()
    {
        return Mockery::mock(User::class);
    }
}
<?php

namespace LiZhineng\IdentityVerification\Tests;

use Illuminate\Support\Str;
use LiZhineng\IdentityVerification\VerifyIdentity;

class VerifyIdentityTest extends FeatureTest
{
    public function testItWorks()
    {
        VerifyIdentity::manually()
            ->in('registration')
            ->for($this->user())
            ->uuid(Str::uuid())
            ->name('Zhineng')
            ->idNumber('000')
            ->portrait('fake-photo-path')
            ->verify();
    }

    public function testSupportsOCR()
    {
        VerifyIdentity::manually()
            ->in('registration')
            ->for($this->user())
            ->uuid(Str::uuid())
            ->name('Zhineng')
            ->idNumber('000')
            ->portrait('fake-photo-path')
            ->idCardPortrait('fake-id-card-portrait-path')
            ->idCardEmblem('fake-id-card-emblem-path')
            ->verify();
    }
}
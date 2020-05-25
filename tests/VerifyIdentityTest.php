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
        $this->assertTrue($this->mockResponse()->verification()->verify()->passed());
    }

    public function testStoreRecord()
    {
        $this->mockResponse()->verification()->verify();

        $this->assertEquals(1, IdentityVerification::count());
    }

    public function testHasLimit()
    {
        $verification = $this->verification()->limit(0)->verify();

        $this->assertTrue($verification->pending());
        $this->assertNull($verification->verified_at);
    }

    protected function verification()
    {
        return VerifyIdentity::manually()
            ->in('registration')
            ->for($this->user)
            ->uuid(Str::uuid())
            ->name('Testing')
            ->idNumber('000')
            ->portrait('fake-photo-path');
    }
}
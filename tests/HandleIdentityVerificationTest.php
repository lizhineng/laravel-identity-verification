<?php

namespace LiZhineng\IdentityVerification\Tests;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use LiZhineng\IdentityVerification\Tests\Fixtures\User;

class HandleIdentityVerificationTest extends FeatureTest
{
    use RefreshDatabase;

    protected ?User $user;

    public function setUp()
    {
        parent::setUp();

        $this->user = User::create([
            //
        ]);
    }

    public function testHasManyVerificationRecords()
    {
        $this->assertInstanceOf(Collection::class, $this->user()->identityVerifications);
    }

    public function testRetrieveIdentity()
    {
        $identity = $this->user()->identity('registration');

        $this->assertFalse($identity->isPassed());
    }

    public function testRetrieveDefaultIdentity()
    {
        $this->assertEquals(
            $this->user()->identity->scene,
            config('identity-verification.scene')
        );
    }

    protected function user()
    {
        return new User;
    }
}

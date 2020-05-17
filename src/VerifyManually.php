<?php

namespace LiZhineng\IdentityVerification;

use AlibabaCloud\Client\AlibabaCloud;
use Illuminate\Foundation\Auth\User;

class VerifyManually
{
    protected $user;

    protected $name;

    protected $idNumber;

    protected $portrait;

    public function for(User $user)
    {
        $this->user = $user;

        return $this;
    }

    public function name($name)
    {
        $this->name = $name;

        return $this;
    }

    public function idNumber($number)
    {
        $this->idNumber = $number;

        return $this;
    }

    public function portrait($path)
    {
        $this->portrait = $path;

        return $this;
    }

    public function verify()
    {
        AlibabaCloud::mockResponse(200);

        AlibabaCloud::accessKeyClient(
            env('IDENTITY_VERIFICATION_ACCESS_KEY_ID', '1'),
            env('IDENTITY_VERIFICATION_ACCESS_KEY_SECRET', '1')
        )->regionId('cn-shenzhen')->name('identity-verification');

        return AlibabaCloud::rpc()
            ->client('identity-verification')
            ->product('Cloudauth')
            ->version('2019-03-07')
            ->action('VerifyMaterial')
            ->method('POST')
            ->host('cloudauth.aliyuncs.com')
            ->request();
    }
}
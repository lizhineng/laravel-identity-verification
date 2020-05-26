<?php

namespace LiZhineng\IdentityVerification\Tests;

use AlibabaCloud\Client\AlibabaCloud;
use Illuminate\Support\Str;
use LiZhineng\IdentityVerification\VerifyIdentity;

trait VerifyIdentityManually
{
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

    protected function mockResponse(bool $withImages = false)
    {
        AlibabaCloud::cancelMock();

        AlibabaCloud::mockResponse(200, [], [
            'VerifyToken' => 'c302c0797679457685410ee51a5ba375',
            'VerifyStatus' => 1,
            'Material' => [
                'FaceImageUrl' => $withImages ? 'http://image-demo.img-cn-hangzhou.aliyuncs.com/example.jpg' : __DIR__.'/Fixtures/artifact.jpg',
                'IdCardName' => '张三',
                'IdCardNumber' => '023432189011233490',
                'IdCardInfo' => [
                    'FrontImageUrl' => $withImages ? 'http://image-demo.img-cn-hangzhou.aliyuncs.com/example2.jpg' : null,
                    'BackImageUrl' => $withImages ? 'http://image-demo.img-cn-hangzhou.aliyuncs.com/example3.jpg' : null,
                    'Name' => '张三',
                    'Number' => '023432189011233490',
                    'Sex' => '男',
                    'Nationality' => '汉',
                    'Birth' => '1990-01-01',
                    'Address' => '浙江省杭州市余杭区文一西路969号',
                    'StartDate' => '2010-11-01',
                    'EndDate' => '2020-11-01',
                    'Authority' => '杭州市公安局',
                ],
            ],
            'AuthorityComparisonScore' => 97,
        ]);

        return $this;
    }
}
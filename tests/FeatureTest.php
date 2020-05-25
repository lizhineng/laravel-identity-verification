<?php

namespace LiZhineng\IdentityVerification\Tests;

use AlibabaCloud\Client\AlibabaCloud;
use Illuminate\Foundation\Auth\User;
use LiZhineng\IdentityVerification\IdentityVerificationServiceProvider;
use Orchestra\Testbench\TestCase;

abstract class FeatureTest extends TestCase
{
    protected User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->loadLaravelMigrations();

        $this->user = User::forceCreate([
            'email' => 'testing@example.org',
            'name' => 'Testing',
            'password' => '$2y$10$E4BoLxKS61MkkrWKl5RTNeJPErqT0DNbvVTEblk05kAC5UAmfyGCK', // password
        ]);

        $this->artisan('migrate')->run();
    }

    protected function mockResponse()
    {
        AlibabaCloud::cancelMock();

        AlibabaCloud::mockResponse(200, [], [
            'VerifyToken' => 'c302c0797679457685410ee51a5ba375',
            'VerifyStatus' => 1,
            'Material' => [
                'FaceImageUrl' => 'http://image-demo.img-cn-hangzhou.aliyuncs.com/example.jpg',
                'IdCardName' => '张三',
                'IdCardNumber' => '023432189011233490',
                'IdCardInfo' => [
                    'FrontImageUrl' => 'http://image-demo.img-cn-hangzhou.aliyuncs.com/example2.jpg',
                    'BackImageUrl' => 'http://image-demo.img-cn-hangzhou.aliyuncs.com/example3.jpg',
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

    protected function getPackageProviders($app)
    {
        return [IdentityVerificationServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testing');
    }
}
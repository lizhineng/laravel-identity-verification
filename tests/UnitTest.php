<?php

namespace LiZhineng\IdentityVerification\Tests;

use AlibabaCloud\Client\AlibabaCloud;
use PHPUnit\Framework\TestCase;

abstract class UnitTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->mockAlibabaCloud();
    }

    protected function mockAlibabaCloud()
    {
        AlibabaCloud::mockResponse(200);
    }
}
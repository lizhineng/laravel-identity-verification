<?php

namespace LiZhineng\IdentityVerification;

class IdentityVerificationStatus
{
    const STATUS_PASSED = 'passed';
    const STATUS_FAILED = 'failed';
    const STATUS_PENDING = 'pending';
    const STATUS_DRAFT = 'draft';

    public static function fromVerifyStatus($status)
    {
        return $status == IdentityVerificationVerifyStatus::STATUS_PASSED ?
            self::STATUS_PASSED :
            self::STATUS_FAILED;
    }
}

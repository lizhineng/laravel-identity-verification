<?php

namespace LiZhineng\IdentityVerification;

class VerifyIdentity
{
    public static function manually(): VerifyManually
    {
        return new VerifyManually;
    }
}
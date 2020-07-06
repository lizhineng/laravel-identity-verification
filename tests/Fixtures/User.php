<?php

namespace LiZhineng\IdentityVerification\Tests\Fixtures;

use Illuminate\Foundation\Auth\User as Authenticatable;
use LiZhineng\IdentityVerification\HandleIdentityVerification;

class User extends Authenticatable
{
    use HandleIdentityVerification;
}

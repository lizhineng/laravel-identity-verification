<?php

namespace LiZhineng\IdentityVerification;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class IdentityVerification extends Model
{
    protected $casts = [
        'id_card' => 'json',
    ];

    public function auth()
    {
        return $this->morphTo();
    }

    public static function newFromApiResult($result)
    {
        $verification = new static;
        $verification->name = Arr::get($result, 'Material.IdCardName');
        $verification->id_number = Arr::get($result, 'Material.IdCardNumber');
        $verification->id_card = Arr::get($result, 'Material.IdCardInfo');
        $verification->portrait_path = Arr::get($result, 'Material.FaceImageUrl');
        $verification->status = Arr::get($result, 'VerifyStatus');
        $verification->authority_comparision_score = Arr::get($result, 'AuthorityComparisonScore');

        return $verification;
    }
}
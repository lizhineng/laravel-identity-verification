<?php

namespace LiZhineng\IdentityVerification;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class IdentityVerification extends Model
{
    protected $casts = [
        'id_card' => 'json',
        'verified_at' => 'timestamp',
    ];

    public function auth()
    {
        return $this->morphTo();
    }

    public function scopeScene(Builder $query, string $scene)
    {
        return $query->where('scene', $scene);
    }

    public function scopeFailed(Builder $query)
    {
        return $query->where('status', IdentityVerificationStatus::STATUS_FAILED);
    }

    public function passed()
    {
        return $this->status == IdentityVerificationStatus::STATUS_PASSED;
    }

    public function pending()
    {
        return $this->status == IdentityVerificationStatus::STATUS_PENDING;
    }

    public static function newFromApiResult($result)
    {
        $verification = new static;
        $verification->name = Arr::get($result, 'Material.IdCardName');
        $verification->id_number = Arr::get($result, 'Material.IdCardNumber');
        $verification->id_card = Arr::get($result, 'Material.IdCardInfo');
        $verification->portrait_path = Arr::get($result, 'Material.FaceImageUrl');
        $verification->verify_status = Arr::get($result, 'VerifyStatus');
        $verification->status = IdentityVerificationStatus::fromVerifyStatus($verification->verify_status);
        $verification->authority_comparision_score = Arr::get($result, 'AuthorityComparisonScore');
        $verification->verified_at = now();

        return $verification;
    }

    public static function createFromDraft(VerifyManually $draft)
    {
        $verification = new static;
        $verification->auth()->associate($draft->user);
        $verification->name = $draft->name;
        $verification->id_number = $draft->idNumber;
        $verification->portrait_path = $draft->portrait;
        $verification->scene = $draft->scene;
        $verification->uuid = $draft->uuid;
        $verification->status = IdentityVerificationStatus::STATUS_PENDING;
        $verification->save();

        return $verification;
    }
}
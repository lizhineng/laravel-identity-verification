<?php

namespace LiZhineng\IdentityVerification;

use AlibabaCloud\Client\Result\Result;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class IdentityVerification extends Model
{
    protected $casts = [
        'id_card' => 'json',
        'verified_at' => 'datetime',
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

    public function fillFromApiResult(Result $result)
    {
        $this->name = Arr::get($result, 'Material.IdCardName');
        $this->id_number = Arr::get($result, 'Material.IdCardNumber');
        $this->id_card = IdCard::make(Arr::get($result, 'Material.IdCardInfo'))->toArray();
        $this->portrait_path = Arr::get($result, 'Material.FaceImageUrl');
        $this->id_card_portrait_path = Arr::get($this->id_card, 'id_card_portrait_path');
        $this->id_card_emblem_path = Arr::get($this->id_card, 'id_card_emblem_path');
        $this->verify_status = Arr::get($result, 'VerifyStatus');
        $this->status = IdentityVerificationStatus::fromVerifyStatus($this->verify_status);
        $this->authority_comparision_score = Arr::get($result, 'AuthorityComparisonScore');
        $this->verified_at = now();

        return $this;
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
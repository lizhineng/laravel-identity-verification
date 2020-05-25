<?php

namespace LiZhineng\IdentityVerification;

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Result\Result;
use Illuminate\Foundation\Auth\User;
use LiZhineng\IdentityVerification\Exceptions\OnlyDraftCanBeRecovered;

class VerifyManually
{
    /**
     * The user to verify identity.
     *
     * @var User $user
     */
    protected User $user;

    /**
     * The sense to verify identity.
     *
     * @var string $scene
     */
    protected string $scene;

    /**
     * The uuid of the verification task.
     *
     * @var string $uuid
     */
    protected string $uuid;

    /**
     * The real name of the user.
     *
     * @var string $name
     */
    protected string $name;

    /**
     * Identity number of the user.
     *
     * @var string $idNumber
     */
    protected string $idNumber;

    /**
     * Portrait photo path to verify.
     *
     * @var string $portrait
     */
    protected string $portrait;

    /**
     * The photo path of id card portrait side.
     *
     * @var string $idCardPortraitPath
     */
    protected string $idCardPortraitPath;

    /**
     * The photo path of id card emblem side.
     *
     * @var string $idCardEmblemPath
     */
    protected string $idCardEmblemPath;

    /**
     * Throttle to limit the automatic verification.
     *
     * @var int|null $limit
     */
    protected ?int $limit = null;

    /**
     * The draft which recovered from.
     *
     * @var int $draftId
     */
    protected ?int $draftId = null;

    /**
     * Verify for the given user.
     *
     * @param  User  $user
     * @return $this
     */
    public function for(User $user)
    {
        $this->user = $user;

        return $this;
    }

    public function from(IdentityVerification $draft)
    {
        throw_if(! $draft->pending(), OnlyDraftCanBeRecovered::class, 'The draft you want to recover from is not in pending status.');

        $this->scene = $draft->scene;
        $this->uuid = $draft->uuid;
        $this->name = $draft->name;
        $this->user = $draft->auth;
        $this->idNumber = $draft->id_number;
        $this->portrait = $draft->portrait_path;
        $this->draftId = $draft->id;

        return $this;
    }

    /**
     * Set the current scene to verify identity.
     *
     * @param  string  $scene
     * @return $this
     */
    public function in(string $scene)
    {
        $this->scene = $scene;

        return $this;
    }

    /**
     * Set the uuid of the current verification task.
     *
     * @param  string  $uuid
     * @return $this
     */
    public function uuid(string $uuid)
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * Verify the real name of the user.
     *
     * @param  string  $name
     * @return $this
     */
    public function name(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Verify the identity card number of the user.
     *
     * @param  string  $number
     * @return $this
     */
    public function idNumber(string $number)
    {
        $this->idNumber = $number;

        return $this;
    }

    /**
     * Given the path of the portrait photo of the user.
     *
     * @param  string  $path
     * @return $this
     */
    public function portrait(string $path)
    {
        $this->portrait = $path;

        return $this;
    }

    public function idCardPortrait(string $path)
    {
        $this->idCardPortraitPath = $path;

        return $this;
    }

    public function idCardEmblem(string $path)
    {
        $this->idCardEmblemPath = $path;

        return $this;
    }

    /**
     * Limit the automatic verification.
     *
     * @param  int  $limit
     * @return $this
     */
    public function limit(int $limit)
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * Verify the given data.
     *
     * @return IdentityVerification
     * @throws \AlibabaCloud\Client\Exception\ClientException
     * @throws \AlibabaCloud\Client\Exception\ServerException
     */
    public function verify()
    {
        if ($this->shouldAutoVerify()) {
            return $this->verifyFromApi();
        }

        return IdentityVerification::createFromDraft($this);
    }

    public function verifyFromApi()
    {
        $result = AlibabaCloud::rpc()
            ->client('identity-verification')
            ->product('Cloudauth')
            ->version('2019-03-07')
            ->action('VerifyMaterial')
            ->method('POST')
            ->host('cloudauth.aliyuncs.com')
            ->options([
                'query' => [
                    'FaceImageUrl' => $this->portrait,
                    'BizType' => $this->scene,
                    'BizId' => $this->uuid,
                    'Name' => $this->name,
                    'IdCardNumber' => $this->idNumber,
                    'IdCardBackImageUrl' => $this->idCardPortraitPath ?? null,
                    'IdCardFrontImageUrl' => $this->idCardEmblemPath ?? null,
                ],
            ])
            ->request();

        return $this->persist($result);
    }

    /**
     * Determine if it should verify identity automatically.
     *
     * @return bool
     */
    public function shouldAutoVerify()
    {
        return $this->isRecoveredFromDraft() || ! $this->beyondLimit();
    }

    /**
     * Determine if the user is beyond the automatic verify limit.
     *
     * @return bool
     */
    public function beyondLimit()
    {
        return ! is_null($this->limit) && $this->failedCount() >= $this->limit;
    }

    /**
     * Determine the task is recovered from draft.
     *
     * @return bool
     */
    public function isRecoveredFromDraft()
    {
        return (bool) $this->draftId;
    }

    /**
     * The retry time of verifying identity.
     *
     * @return int
     */
    public function failedCount()
    {
        return IdentityVerification::failed()
            ->scene($this->scene)
            ->whereHasMorph('auth', [get_class($this->user)], fn ($query) => $query->where('id', $this->user->getKey()))
            ->count();
    }

    /**
     * Persist the verification result to database.
     *
     * @param  Result  $result
     * @return IdentityVerification
     */
    protected function persist(Result $result)
    {
        return tap($this->verification()->fillFromApiResult($result))->save();
    }

    /**
     * Build up or retrieve an identity verification record.
     *
     * @return IdentityVerification
     */
    protected function verification()
    {
        if ($this->isRecoveredFromDraft()) {
            return IdentityVerification::find($this->draftId);
        }

        return tap(new IdentityVerification, function (IdentityVerification $verification) {
            $verification->auth()->associate($this->user);
            $verification->scene = $this->scene;
            $verification->uuid = $this->uuid;
        });
    }

    /**
     * Dynamically retrieve attributes on the verification.
     *
     * @param  string  $name
     * @return mixed
     */
    public function __get($name)
    {
        if (isset($this->$name)) {
            return $this->$name;
        }

        return null;
    }
}
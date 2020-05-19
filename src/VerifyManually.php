<?php

namespace LiZhineng\IdentityVerification;

use AlibabaCloud\Client\AlibabaCloud;
use Illuminate\Foundation\Auth\User;

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
     * @var string $sense
     */
    protected string $sense;

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

    /**
     * Set the current sense to verify identity.
     *
     * @param  string  $sense
     * @return $this
     */
    public function in(string $sense)
    {
        $this->sense = $sense;

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
     * Verify the given data.
     *
     * @return \AlibabaCloud\Client\Result\Result
     * @throws \AlibabaCloud\Client\Exception\ClientException
     * @throws \AlibabaCloud\Client\Exception\ServerException
     */
    public function verify()
    {
        return AlibabaCloud::rpc()
            ->client('identity-verification')
            ->product('Cloudauth')
            ->version('2019-03-07')
            ->action('VerifyMaterial')
            ->method('POST')
            ->host('cloudauth.aliyuncs.com')
            ->options([
                'query' => [
                    'FaceImageUrl' => $this->portrait,
                    'BizType' => $this->sense,
                    'BizId' => $this->uuid,
                    'Name' => $this->name,
                    'IdCardNumber' => $this->idNumber,
                    'IdCardBackImageUrl' => $this->idCardPortraitPath ?? null,
                    'IdCardFrontImageUrl' => $this->idCardEmblemPath ?? null,
                ],
            ])
            ->request();
    }
}
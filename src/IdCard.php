<?php

namespace LiZhineng\IdentityVerification;

use Illuminate\Contracts\Support\Arrayable;

class IdCard implements Arrayable
{
    protected string $name;

    protected string $number;

    protected string $sex;

    protected string $birthDate;

    protected string $address;

    protected string $nationality;

    protected string $issuedBy;

    protected string $issuedOn;

    protected string $expiredOn;

    protected ?string $idCardPortraitPath;

    protected ?string $idCardEmblemPath;

    public function __construct(array $data)
    {
        $this->name = data_get($data, 'Name');
        $this->number = data_get($data, 'Number');
        $this->sex = data_get($data, 'Sex');
        $this->birthDate = data_get($data, 'Birth');
        $this->address = data_get($data, 'Address');
        $this->nationality = data_get($data, 'Nationality');
        $this->issuedBy = data_get($data, 'Authority');
        $this->issuedOn = data_get($data, 'StartDate');
        $this->expiredOn = data_get($data, 'EndDate');
        $this->idCardPortraitPath = data_get($data, 'BackImageUrl');
        $this->idCardEmblemPath = data_get($data, 'FrontImageUrl');
    }

    public static function make(array $data)
    {
        return new static($data);
    }

    public function toArray()
    {
        return [
            'name' => $this->name,
            'number' => $this->number,
            'sex' => $this->sex,
            'birth_date' => $this->birthDate,
            'address' => $this->address,
            'nationality' => $this->nationality,
            'issued_by' => $this->issuedBy,
            'issued_on' => $this->issuedOn,
            'expired_on' => $this->expiredOn,
            'id_card_portrait_path' => $this->idCardPortraitPath,
            'id_card_emblem_path' => $this->idCardEmblemPath,
        ];
    }
}
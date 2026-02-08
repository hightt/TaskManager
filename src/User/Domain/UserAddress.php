<?php

declare(strict_types=1);

namespace App\User\Domain;
use App\User\Domain\UserGeo;

class UserAddress
{
    public function __construct(
        private string $street,
        private string $suite,
        private string $city,
        private string $zipcode,
        private UserGeo $userGeo
    ){
    }

    public function userGeo(): UserGeo
    {
        return $this->userGeo;
    }

    public function zipcode(): string
    {
        return $this->zipcode;
    }

    public function city(): string
    {
        return $this->city;
    }

    public function suite(): string
    {
        return $this->suite;
    }

    public function street(): string
    {
        return $this->street;
    }
}

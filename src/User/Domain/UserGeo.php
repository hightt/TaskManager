<?php

declare(strict_types=1);

namespace App\User\Domain;

class UserGeo
{
    public function __construct(
        private string $lat,
        private string $lng,
    ){
    }

    public function lng(): string
    {
        return $this->lng;
    }

    public function lat(): string
    {
        return $this->lat;
    }
}

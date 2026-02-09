<?php

declare(strict_types=1);

namespace App\User\Domain;

class UserExternalId
{
    public function __construct(
        private int $externalId 
    ){
    }

    public function externalId(): int
    {
        return $this->externalId;
    }
}

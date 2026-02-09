<?php

declare(strict_types=1);

namespace App\User\Domain;

class UserCompany
{
    public function __construct(
        private string $name,
        private string $catchPhrase,
        private string $bs,
    ){
    }

    public function name(): string
    {
        return $this->name;
    }

    public function catchPhrase(): string
    {
        return $this->catchPhrase;
    }

    public function bs(): string
    {
        return $this->bs;
    }

}

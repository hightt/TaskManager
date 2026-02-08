<?php

declare(strict_types=1);

namespace App\User\Domain;
use InvalidArgumentException;

class UserEmail
{
    public function __construct(
        private string $email 
    ){
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException(sprintf('"%s" is not a valid e-mail.', $email));
        }

        $this->email = $email;
    }

    public function value(): string
    {
        return $this->email;
    }
}

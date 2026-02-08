<?php

declare(strict_types=1);

namespace App\User\Domain;
use Ramsey\Uuid\Uuid;
use InvalidArgumentException;

class UserId
{
    public function __construct(
        private string $id 
    ){
        if (!Uuid::isValid($id)) {
            throw new InvalidArgumentException(sprintf('"%s" is not a valid UUID.', $id));
        }

        $this->id = $id;
    }

    public function id(): string
    {
        return $this->id;
    }
}

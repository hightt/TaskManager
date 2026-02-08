<?php

declare(strict_types=1);

namespace App\User\Domain;

interface UserRepositoryInterface
{
    public function find(UserId $id): ?User;

    public function save(User $user): void;

    public function findByExternalId(UserExternalId $userId): ?User;

    public function findByUserIdentifier(string $userIdentifier): ?User;

    public function flush(): void;
    
}

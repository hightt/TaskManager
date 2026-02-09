<?php

declare(strict_types=1);

namespace App\User\Domain;

class User
{
    private function __construct(
        private UserId $userId,
        private UserExternalId $userExternalId,
        private string $name,
        private string $username,
        private UserEmail $userEmail,
        private UserAddress $userAddress,
        private string $phone,
        private string $website,
        private UserCompany $userCompany,
        private array $roles = [],
    ){
    }

    public static function create(
        UserId $userId,
        UserExternalId $userExternalId,
        string $name,
        string $username,
        UserEmail $userEmail,
        UserAddress $userAddress,
        string $phone,
        string $website,
        UserCompany $userCompany,
        array $roles,
    )
    {
        if (strlen($username) < 3) {
            throw new \DomainException(sprintf("Username %s is too short.", $username));
        }

        return new self($userId, $userExternalId, $name, $username, $userEmail, $userAddress, $phone, $website, $userCompany, $roles);
    }

    public function userId(): UserId
    {
        return $this->userId;
    }

    public function userExternalId(): UserExternalId
    {
        return $this->userExternalId;
    }
    
    public function name(): string
    {
        return $this->name;
    }

    public function username(): string
    {
        return $this->username;
    }

    public function userEmail(): UserEmail
    {
        return $this->userEmail;
    }

    public function userAddress(): UserAddress
    {
        return $this->userAddress;
    }

    public function phone(): string
    {
        return $this->phone;
    }

    public function website(): string
    {
        return $this->website;
    }

    public function userCompany(): UserCompany
    {
        return $this->userCompany;
    }

    public function roles(): array
    {
        return $this->roles;
    }

    public function isAdmin(): bool
    {
        return in_array('ROLE_ADMIN', $this->roles, true);
    }
}

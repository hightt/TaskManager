<?php

declare(strict_types=1);

namespace App\User\Application;

use App\User\Domain\User;
use App\User\Domain\UserAddress;
use App\User\Domain\UserCompany;
use App\User\Domain\UserEmail;
use App\User\Domain\UserExternalId;
use App\User\Domain\UserGeo;
use App\User\Domain\UserId;
use Ramsey\Uuid\Uuid;

class UserFromApiFactory
{
   public static function create(array $data): User 
{
    return User::create(
        new UserId(Uuid::uuid4()->toString()),
        new UserExternalId((int) $data['id']),
        $data['name'],
        $data['username'],
        new UserEmail($data['email']),
        new UserAddress(
            $data['address']['street'],
            $data['address']['suite'],
            $data['address']['city'],
            $data['address']['zipcode'],
            new UserGeo(
                $data['address']['geo']['lat'],
                $data['address']['geo']['lng']
            )
        ),
        $data['phone'],
        $data['website'],
        new UserCompany(
            $data['company']['name'],
            $data['company']['catchPhrase'],
            $data['company']['bs']
        ),
        ['USER_ROLE']
    );
}
}

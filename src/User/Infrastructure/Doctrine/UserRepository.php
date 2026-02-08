<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Doctrine;

use App\User\Domain\User;
use App\User\Domain\UserId;
use App\User\Domain\UserGeo;
use App\User\Domain\UserEmail;
use App\User\Domain\UserAddress;
use App\User\Domain\UserCompany;
use App\User\Domain\UserExternalId;
use Doctrine\ORM\EntityManagerInterface;
use App\User\Domain\UserRepositoryInterface;
use App\User\Infrastructure\Doctrine\UserORM;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserRepository implements UserRepositoryInterface
{

    public function __construct(
        private EntityManagerInterface  $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
    )
    {}

    public function find(UserId $userId): ?User
    {
        $userOrm = $this->entityManager->find(UserOrm::class, $userId->id());

        if (is_null($userOrm)) {
            return null;
        }

        return $this->fromOrm($userOrm);
    }

    public function findByExternalId(UserExternalId $userId): ?User
    {
        $userOrm = $this->entityManager
            ->getRepository(UserORM::class)
            ->findOneBy(['externalId' => $userId->externalId()])
        ;

        if (is_null($userOrm)) {
            return null;
        }

        return $this->fromOrm($userOrm);
    }

    public function findByUserIdentifier(string $userIdentifier): ?User
    {
        $userOrm = $this->entityManager
            ->getRepository(UserORM::class)
            ->findOneBy(['email' => $userIdentifier])
        ;

        if (is_null($userOrm)) {
            return null;
        }

        return $this->fromOrm($userOrm);
    }


    public function save(User $user): void
    {
        $userOrm = $this->toOrm($user);

        $this->entityManager->persist($userOrm);
    }

    public function toOrm(User $user)
    {
        $userOrm = new UserORM();
        $randomPlainPassword = bin2hex(random_bytes(8)); 
        $hashedPassword = $this->passwordHasher->hashPassword(
            $userOrm, 
            $randomPlainPassword
        );

        return $userOrm
            ->setId($user->userId()->id())
            ->setExternalId($user->userExternalId()->externalId())
            ->setName($user->name())
            ->setPassword($hashedPassword)
            ->setUsername($user->username())
            ->setEmail($user->userEmail()->value())
            ->setStreet($user->userAddress()->street())
            ->setSuite($user->userAddress()->suite())
            ->setCity($user->userAddress()->city())
            ->setZipcode($user->userAddress()->zipcode())
            ->setLat((float)$user->userAddress()->userGeo()->lat())
            ->setLng((float)$user->userAddress()->userGeo()->lng())
            ->setPhone($user->phone())
            ->setWebsite($user->website())
            ->setCompanyName($user->userCompany()->name())
            ->setCompanyCatchPhrase($user->userCompany()->catchPhrase())
            ->setCompanyBs($user->userCompany()->bs())
            ->setRoles($user->roles())
        ;
    }

    public function fromOrm(UserORM $userOrm): User
    {
        return User::create(
            new UserId($userOrm->getId()),
            new UserExternalId($userOrm->getExternalId()),
            $userOrm->getName(),
            $userOrm->getUsername(),
            new UserEmail($userOrm->getEmail()),
            new UserAddress(
                $userOrm->getStreet(),
                $userOrm->getSuite(),
                $userOrm->getCity(),
                $userOrm->getZipcode(),
                new UserGeo(
                    (string)$userOrm->getLat(),
                    (string)$userOrm->getLng()
                )
            ),
            $userOrm->getPhone(),
            $userOrm->getWebsite(),
            new UserCompany(
                $userOrm->getCompanyName(),
                $userOrm->getCompanyCatchPhrase(),
                $userOrm->getCompanyBs()
            ),
            $userOrm->getRoles(),
        );
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }
}

<?php

declare(strict_types=1);

namespace App\User\Application;

use Throwable;
use Psr\Log\LoggerInterface;
use App\User\Domain\UserRepositoryInterface;
use App\User\Infrastructure\JsonPlaceholderIntegration;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class UserImportHandler
{
    public function __construct(
        private JsonPlaceholderIntegration $jsonPlaceholderIntegration,
        private LoggerInterface $logger,
        private UserRepositoryInterface $userRepository,
    )
    {}

    public function __invoke(UserImportCommand $userImportCommand): void
    {
        try {
            $externalUsers = $this->jsonPlaceholderIntegration->import();
            foreach ($externalUsers as $externalUser) {
                $domainUser = UserFromApiFactory::create($externalUser);
                if (!is_null($this->userRepository->findByExternalId($domainUser->userExternalId()))) {
                    continue;
                }

                $this->userRepository->save($domainUser);
            }

            $this->userRepository->flush();
        } catch (Throwable $e) {
            $this->logger->critical($e->getMessage(), ['exception' => $e]);

            throw $e;
        }
    }
}

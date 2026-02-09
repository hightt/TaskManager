<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Console;

use Throwable;
use Psr\Log\LoggerInterface;
use App\User\Application\UserImportCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:users:import',
    description: 'Import users from external service JSONPlaceholder'
)]
class ImportUsersCommand extends Command
{
    public function __construct(
        private MessageBusInterface $messageBus,
        private LoggerInterface $loggerInterface,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->loggerInterface->info('Import users has been started');

        try {
            $command = new UserImportCommand();
            $this->messageBus->dispatch($command);

            $this->loggerInterface->info('Import users successfully finished');
        } catch (Throwable $e) {
            $this->loggerInterface->error('An error occurs during import users: ', [$e->getMessage(), $e->getCode(), $e->getTraceAsString()]);

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
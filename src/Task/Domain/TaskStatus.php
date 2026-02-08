<?php

declare(strict_types=1);

namespace App\Task\Domain;

enum TaskStatus: string
{
    case ACTIVE = 'active';
    case IN_PROGRESS = 'in_progress';
    case INACTIVE = 'inactive';
}

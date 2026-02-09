<?php

declare(strict_types=1);

namespace App\Task\Infrastructure\Form;

use App\Task\Domain\TaskStatus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;

class TaskChangeStatusType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('status', EnumType::class, [
                'class' => TaskStatus::class,
                'label' => 'Zmień status',
            ]);
    }
}
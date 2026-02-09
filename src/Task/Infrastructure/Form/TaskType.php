<?php

declare(strict_types=1);

namespace App\Task\Infrastructure\Form;

use App\Task\Domain\TaskStatus;
use App\User\Infrastructure\Doctrine\UserORM;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Nazwa zadania'])
            ->add('description', TextareaType::class, ['label' => 'Opis'])
            ->add('status', EnumType::class, [
                'class' => TaskStatus::class,
                'label' => 'Status'
            ])
            ->add('user', EntityType::class, [
                'class' => UserORM::class,
                'choice_label' => 'name',
                'label' => 'Przypisz użytkownika'
            ]);
    }
}
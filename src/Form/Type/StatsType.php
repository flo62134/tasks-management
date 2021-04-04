<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Entity\Project;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class StatsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'project',
                EntityType::class,
                [
                    'class' => Project::class,
                    'choice_label' => 'name',
                    'placeholder' => 'Choose a project',
                    'required' => false,
                ]
            )
            ->add('from', DateType::class)
            ->add('to', DateType::class)
            ->add('save', SubmitType::class)
        ;
    }
}

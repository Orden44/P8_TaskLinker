<?php

namespace App\Form;

use App\Entity\Employee;
use App\Entity\Project;
use App\Entity\Status;
use App\Entity\Task;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Titre de la tâche'])
            ->add('description', null, ['label' => 'Description'])
            ->add('deadline', null, [
                'widget' => 'single_text',
                'label' => 'Date',
            ])
            ->add('status', EntityType::class, [
                'label' => 'Statut',
                'class' => Status::class,
                'choice_label' => 'label',
                'choice_value' => 'id',
            ])
            ->add('employees', null, [
                'label' => 'Membre',
                'class' => Employee::class,
                'choice_label' => 'fullName',
                'choice_value' => 'id',
                'multiple' => true,
            ])
            ->add('project', EntityType::class, [
                'label' => 'Projet',
                'class' => Project::class,
                'choice_label' => 'name',
                'choice_value' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}

<?php
//src/Form/ProjectType.php
namespace App\Form;

use App\Entity\Employee;
use App\Entity\Project;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Tire du projet'])
            ->add('employees', EntityType::class, [
                'label' => 'Inviter des membres',
                'class' => Employee::class,
                'choice_label' => 'fullName',
                'choice_value' => 'id',
                'multiple' => true,
                'by_reference' => false,                
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}

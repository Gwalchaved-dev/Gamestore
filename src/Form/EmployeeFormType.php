<?php

namespace App\Form;

use App\Entity\Agence;
use App\Entity\Employee;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployeeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('adresse', TextType::class, [
                'label' => 'Adresse',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('codepostal', TextType::class, [
                'label' => 'Code Postal',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('ville', TextType::class, [
                'label' => 'Ville',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('agence', EntityType::class, [
                'class' => Agence::class,
                'choice_label' => 'nom', // Affiche le nom de l'agence dans le choix
                'label' => 'Agence',
                'attr' => ['class' => 'form-control'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employee::class,
        ]);
    }
}

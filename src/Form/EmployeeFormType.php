<?php

namespace App\Form;

use App\Entity\Employee;
use App\Entity\Agence; // N'oublie pas d'importer l'entité Agence
use Symfony\Bridge\Doctrine\Form\Type\EntityType; // Utiliser EntityType pour les entités dans le formulaire
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType; // Import du champ EmailType
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployeeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
            ])
            ->add('email', EmailType::class, [ // Ajout du champ email
                'label' => 'Adresse Email',
            ])
            ->add('adresse', TextType::class, [
                'label' => 'Adresse',
            ])
            ->add('codepostal', TextType::class, [
                'label' => 'Code Postal',
            ])
            ->add('ville', TextType::class, [
                'label' => 'Ville',
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => 'Mot de passe',
                'mapped' => false, // Le mot de passe ne doit pas être automatiquement lié à l'entité
                'required' => false, // Pas obligatoire si le formulaire sert à éditer
            ])
            ->add('agence', EntityType::class, [
                'class' => Agence::class,
                'choice_label' => 'nom', // Utilisation du champ nom pour représenter l'agence
                'label' => 'Agence', // Le label affiché dans le formulaire
                'placeholder' => 'Sélectionner une agence', // Optionnel, une première option vide
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employee::class,
        ]);
    }
}

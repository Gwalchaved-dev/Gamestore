<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'E-mail',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Merci de renseigner une adresse e-mail.',
                    ]),
                    new Assert\Email([
                        'message' => 'L\'adresse e-mail "{{ value }}" n\'est pas valide.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => 'Mot de passe',
                'mapped' => false,  // Indique que ce champ ne doit pas être mappé à l'entité
                'attr' => ['autocomplete' => 'new-password'],
                'help' => 'Le mot de passe doit contenir au moins 6 caractères',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Merci de renseigner un mot de passe.',
                    ]),
                    new Assert\Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères.',
                        'max' => 4096, // Symfony limite la longueur du mot de passe par sécurité
                    ]),
                ],
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Merci de renseigner votre nom.',
                    ]),
                ],
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Merci de renseigner votre prénom.',
                    ]),
                ],
            ])
            ->add('adressePostale', TextType::class, [
                'label' => 'Adresse postale',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Merci de renseigner votre adresse postale.',
                    ]),
                ],
            ])
            ->add('codePostal', TextType::class, [
                'label' => 'Code postal',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Merci de renseigner votre code postal.',
                    ]),
                ],
            ])
            ->add('ville', TextType::class, [
                'label' => 'Ville',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Merci de renseigner votre ville.',
                    ]),
                ],
            ])
            ->add('dateAnniversaire', DateType::class, [
                'label' => 'Date de naissance',
                'widget' => 'single_text',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Merci de renseigner votre date de naissance.',
                    ]),
                    new Assert\Date([
                        'message' => 'Merci de renseigner une date valide.',
                    ]),
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'Accepter les conditions',
                'mapped' => false,  // Pas mappé à l'entité car ce n'est pas une propriété persistée
                'constraints' => [
                    new Assert\IsTrue([
                        'message' => 'Vous devez accepter les conditions pour vous inscrire.',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
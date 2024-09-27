<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
                'mapped' => false, // Ce champ ne doit pas être mappé directement à l'entité User
                'attr' => [
                    'autocomplete' => 'new-password',
                    'required' => true,
                    'placeholder' => 'Entrez votre mot de passe',
                ],
                'help' => 'Le mot de passe doit contenir au moins 8 caractères',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Merci de renseigner un mot de passe.',
                    ]),
                    new Assert\Length([
                        'min' => 8,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères.',
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('confirmPassword', PasswordType::class, [
                'label' => 'Confirmez le mot de passe',
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'required' => true,
                    'placeholder' => 'Confirmez votre mot de passe',
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Merci de confirmer votre mot de passe.',
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
            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'Accepter les conditions',
                'mapped' => false,
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
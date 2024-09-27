<?php

namespace App\Form;

use App\Entity\JeuxVideos;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\File;

class JeuxVideosFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre du jeu',
                'attr' => [
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Le titre ne doit pas être vide.',
                    ]),
                ],
            ])
            ->add('description', TextType::class, [
                'label' => 'Description',
                'attr' => [
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'La description ne doit pas être vide.',
                    ]),
                ],
            ])
            ->add('prix', MoneyType::class, [
                'currency' => 'EUR', // ou autre devise
                'scale' => 2, // Pour forcer à accepter des valeurs avec deux décimales
                'attr' => [
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Le prix ne doit pas être vide.',
                    ]),
                    new Assert\Positive([
                        'message' => 'Le prix doit être un nombre positif.',
                    ]),
                ],
            ])
            ->add('image', FileType::class, [
                'label' => 'Image principale (JPEG, PNG)',
                'mapped' => false, // Ce champ ne doit pas être mappé directement à l'entité
                'required' => true, // L'image principale est obligatoire
                'attr' => [
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '2500k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger une image valide (JPEG ou PNG).',
                    ])
                ],
            ])
            ->add('images', FileType::class, [
                'label' => 'Autres images du jeu (JPEG, PNG)',
                'mapped' => false, // Ce champ ne doit pas être mappé directement à l'entité
                'required' => false,
                'multiple' => true, // Pour permettre plusieurs fichiers
                'attr' => [
                    'multiple' => 'multiple', // Permettre la sélection multiple dans le formulaire
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '2500k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger des images valides (JPEG ou PNG).',
                    ])
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => JeuxVideos::class,
        ]);
    }
}
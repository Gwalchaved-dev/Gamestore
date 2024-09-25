<?php

namespace App\Form;

use App\Entity\JeuxVideos;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JeuxVideosFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre du jeu',
            ])
            ->add('description', TextType::class, [
                'label' => 'Description',
            ])
            ->add('prix', MoneyType::class, [
                'label' => 'Prix',
                'currency' => 'EUR',
            ])
            ->add('image', FileType::class, [
                'label' => 'Image du jeu',
                'mapped' => false,
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => JeuxVideos::class,
        ]);
    }
}
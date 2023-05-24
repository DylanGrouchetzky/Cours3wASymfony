<?php

namespace App\Form;

use App\Entity\Menu;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('item', TextType::class, [
                'label' => 'Nom de l\'item',
                'attr' => ['placeholder' => 'Nom de l\'item' ]
            ])
            ->add('route', TextType::class, [
                'label' => 'Nom de la route',
                'attr' => ['placeholder' => 'Nom de la route' ],
                'required' => false,
            ])
            ->add('paramRoute', TextType::class, [
                'label' => 'Nom du paramétre',
                'attr' => ['placeholder' => 'Nom du paramétre' ],
                'required' => false,
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Menu::class,
        ]);
    }
}

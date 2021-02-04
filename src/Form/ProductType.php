<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code', TextType::class,  ['label' => 'Código', 'required' => true, 'attr' => ['maxlength' => 10, 'minlength' => 4]])
            ->add('name', TextType::class,  ['label' => 'Nombre del producto', 'required' => true, 'attr' => ['maxlength' => 100, 'minlength' => 4]])
            ->add('description', TextareaType::class,  ['label' => 'Descripción', 'required' => true])
            ->add('brand', TextType::class,  ['label' => 'Marca', 'required' => true, 'attr' => ['maxlength' => 100, 'minlength' => 4]])
            ->add('price', MoneyType::class,  ['label' => 'Precio', 'required' => true, 'attr' => ['maxlength' => 10, 'min' => 100]])
            ->add('category', EntityType::class, [
                    'label' => 'Nombre de la categoría',
                    'required' => true,
                    'class' => Category::class,
                    'placeholder' => 'Seleccionar...',
                    'choice_label' => 'name',
                ])
            ->add('Guardar', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}

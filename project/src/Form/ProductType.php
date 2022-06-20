<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Customer;
use App\Enum\StatusEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('issn', TextType::class)
            ->add('name', TextType::class)
            ->add('status', ChoiceType::class, [
                'choices' => StatusEnum::getAvailabelChoices(),
                'empty_data' => StatusEnum::STATUS_NEW
            ])
            ->add('customer', EntityType::class, [
                'class' => Customer::class
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
            'csrf_protection' => false,
        ]);
    }
}

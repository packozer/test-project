<?php

namespace App\Form;

use App\Entity\Customer;
use App\Entity\Product;
use App\Enum\StatusEnum;
use App\Form\DataTransformer\IdToProductsTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerType extends AbstractType
{
    private $transformer;

    public function __construct(IdToProductsTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class)
            ->add('lastName', TextType::class)
            ->add('dateOfBirth', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('status', ChoiceType::class, [
                'choices' => StatusEnum::getAvailabelChoices(),
                'empty_data' => StatusEnum::STATUS_NEW
            ])
            ->add('products', CollectionType::class, [
                'entry_type' => TextType::class,
                'allow_add' => true
            ]);

        $builder->get('products')
            ->addModelTransformer($this->transformer);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
            'csrf_protection' => false,
        ]);
    }
}

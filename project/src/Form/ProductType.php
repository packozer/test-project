<?php

namespace App\Form;

use App\Entity\Product;
use App\Enum\StatusEnum;
use App\Form\DataTransformer\UuidToCustomerTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ProductType extends AbstractType
{
    private $transformer;

    public function __construct(UuidToCustomerTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('issn', TextType::class)
            ->add('name', TextType::class)
            ->add('status', ChoiceType::class, [
                'choices' => StatusEnum::getAvailabelChoices(),
                'empty_data' => StatusEnum::STATUS_NEW
            ])
            ->add('customer', TextType::class, [
                'invalid_message' => 'That is not a valid customer uuid',
            ]);

        $builder->get('customer')->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
            'csrf_protection' => false,
        ]);
    }
}

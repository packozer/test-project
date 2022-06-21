<?php
namespace App\Form\DataTransformer;

use App\Entity\Customer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class UuidToCustomerTransformer implements DataTransformerInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function transform($customer): string
    {
        if (null === $customer) {
            return '';
        }

        return $customer->getUuid();
    }


    public function reverseTransform($uuid): ?Customer
    {
        // no issue number? It's optional, so that's ok
        if (!$uuid) {
            return null;
        }
        $customer = $this->entityManager
            ->getRepository(Customer::class)
            ->find($uuid)
        ;

        if (null === $customer) {
            throw new TransformationFailedException(sprintf(
                'An customer with uuid "%s" does not exist!',
                $uuid
            ));
        }

        return $customer;
    }
}
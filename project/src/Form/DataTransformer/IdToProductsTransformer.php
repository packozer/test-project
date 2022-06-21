<?php
namespace App\Form\DataTransformer;

use App\Entity\Product;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class IdToProductsTransformer implements DataTransformerInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function transform($collection)
    {
        if (empty($collection)) {
            return [];
        }
        if (!$collection instanceof Collection) {
            throw new TransformationFailedException(sprintf(
                '%s is not an instance of %s',
                gettype($collection),
                'Doctrine\Common\Collections\Collection'
            ));
        }
        return $collection->map(function ($entity) {
            return  $entity;
        })->toArray();
    }

    public function reverseTransform($collection)
    {
        //convert plain arrays to a doctrine collection
        if (is_array($collection)) {
            $collection = new ArrayCollection($collection);
        }

        if (!$collection instanceof Collection) {
            throw new TransformationFailedException(sprintf(
                '%s is not an instance of %s',
                gettype($collection),
                'Doctrine\Common\Collections\Collection'
            ));
        }

        if ($collection->isEmpty()) {
            return $collection;
        }
        return $collection->map(function ($id) {
            $entity = $this->entityManager->getRepository(Product::class)->find($id);

            if (null === $entity) {
                throw new TransformationFailedException(sprintf(
                    'A %s with id "%s" does not exist!',
                    $this->entityName,
                    $id
                ));
            }

            return $entity;
        });
    }
}
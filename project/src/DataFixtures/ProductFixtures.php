<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\Customer;
use App\Enum\StatusEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
    	$faker = Factory::create();

        for ($i=0; $i < CustomerFixtures::TOTAL_CUSTOMER; $i++) { 
        	for ($o=0; $o < 2 ; $o++) { 
        		
        	
	            foreach ((new StatusEnum())->getAvailableChoices() as $statusValue) {
	                $product = $this->createProduct(
	                    $faker->bothify('?#?#-#?#?'),
	                    $faker->company,
	                    $statusValue,
	                    $this->getReference(CustomerFixtures::CUSTOMER_REF_PREFIX.$i),
	                    $faker->dateTimeBetween('-90 years', '-18 years'),
	                );

	                $manager->persist($product);
	            }

	            $product = $this->createProduct(
	                    $faker->bothify('?#?#-#?#?'),
	                    $faker->company,
	                    StatusEnum::STATUS_DELETED,
	                    $this->getReference(CustomerFixtures::CUSTOMER_REF_PREFIX.$i),
	                    $faker->dateTimeBetween('-90 years', '-18 years'),
	                    $faker->dateTimeBetween('-2 years', '-1 hours')
	                );

	            $manager->persist($product);
	        }
        }

        $manager->flush();
    }

    private function createProduct(
        string $issn = null,
        string $name = null,
        string $status = null,
        Customer $customer = null,
        \DateTimeInterface $createdAt = null,
        \DateTimeInterface $deletedAt = null
    ): Product
    {
        $product = new Product();

        if ($issn) {
            $product->setIssn($issn);
        }

        if ($name) {
            $product->setName($name);
        }

        if ($status) {
            $product->setStatus($status);
        }

        if ($customer) {
            $product->setCustomer($customer);
        }

        if ($createdAt) {
            $product->setCreatedAt($createdAt);
        }

        if ($deletedAt) {
            $product->setDeletedAt($deletedAt);
        }

        return $product;
    }

    public function getDependencies()
    {
    	return [
    		ProductFixtures::class
    	];
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Enum\StatusEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CustomerFixtures extends Fixture
{
    public const TOTAL_CUSTOMER = 30;
    public const CUSTOMER_REF_PREFIX = 'CUSTOMER_';

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $customerRefIteration = 0;

        for ($i=0; $i < 5; $i++) { 
            foreach ((new StatusEnum())->getAvailableChoices() as $statusValue) {
                $customer = $this->createOneCustomer(
                    $faker->firstName,
                    $faker->lastName,
                    $faker->dateTimeBetween('-90 years', '-18 years'),
                    $statusValue,
                    $faker->dateTimeBetween('-2 years', '-1 hours')
                );

                $this->persistAndUpdateReference($manager, $customer, $customerRefIteration);
            }

            $customer = $this->createOneCustomer(
                $faker->firstName,
                $faker->lastName,
                $faker->dateTimeBetween('-90 years', '-18 years'),
                StatusEnum::STATUS_DELETED,
                $faker->dateTimeBetween('-2 years', '-1 hours'),
                $faker->dateTimeBetween('-2 years', '-1 hours')
            );

            $this->persistAndUpdateReference($manager, $customer, $customerRefIteration);
        }

        $manager->flush();
    }

    private function persistAndUpdateReference($manager, $customer, &$customerRefIteration)
    {
        $manager->persist($customer);
        $this->addReference(self::CUSTOMER_REF_PREFIX.$customerRefIteration++, $customer);
    }

    private function createOneCustomer(
        string $firstName = null,
        string $lastName = null,
        \DateTimeInterface $dateOfBirth = null,
        string $status = null,
        \DateTimeInterface $createdAt = null,
        \DateTimeInterface $deletedAt = null
    ): Customer
    {
        $customer = new Customer();

        if ($firstName) {
            $customer->setFirstName($firstName);
        }

        if ($lastName) {
            $customer->setLastName($lastName);
        }

        if ($dateOfBirth) {
            $customer->setDateOfBirth($dateOfBirth);
        }

        if ($status) {
            $customer->setStatus($status);
        }

        if ($createdAt) {
            $customer->setCreatedAt($createdAt);
        }

        if ($deletedAt) {
            $customer->setDeletedAt($deletedAt);
        }

        return $customer;
    }
}

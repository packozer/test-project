<?php

namespace App\Tests;

use App\Repository\UserRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CustomerTest extends WebTestCase
{
    protected $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient([], ['HTTP_HOST' => '127.0.0.1:8741']);
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('admin@admin.com');

        // simulate $testUser being logged in
        $this->client->loginUser($testUser);
    }

    public function testLoggedIn()
    {
        $this->client->request('POST', '/api/login');
        $this->assertResponseIsSuccessful();
    }

    public function testGetCustomers()
    {
        $this->client->request('GET', '/customers/');

        $this->assertResponseStatusCodeSame(200);
    }
}

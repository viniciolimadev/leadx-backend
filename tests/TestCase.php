<?php

namespace Tests;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class TestCase extends WebTestCase
{
    protected KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        $this->truncateTables();
    }

    private function truncateTables(): void
    {
        $em = static::getContainer()->get(EntityManagerInterface::class);
        $em->getConnection()->executeStatement('TRUNCATE TABLE "user" CASCADE');
    }

    protected function postJson(string $uri, array $data): void
    {
        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data),
        );
    }

    protected function getJson(string $uri, ?string $token = null): void
    {
        $headers = ['CONTENT_TYPE' => 'application/json'];

        if ($token !== null) {
            $headers['HTTP_AUTHORIZATION'] = 'Bearer '.$token;
        }

        $this->client->request('GET', $uri, [], [], $headers);
    }

    protected function responseData(): array
    {
        return json_decode($this->client->getResponse()->getContent(), true);
    }

    protected function registerUser(string $email = 'test@example.com', string $password = 'password123'): void
    {
        $this->postJson('/api/auth/register', compact('email', 'password'));
    }

    protected function loginAndGetToken(string $email = 'test@example.com', string $password = 'password123'): string
    {
        $this->postJson('/api/auth/login', compact('email', 'password'));

        return $this->responseData()['token'];
    }
}

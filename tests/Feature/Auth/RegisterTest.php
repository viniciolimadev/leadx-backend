<?php

namespace Tests\Feature\Auth;

use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    public function test_register_returns_201_with_user_data(): void
    {
        $this->postJson('/api/auth/register', [
            'email'    => 'user@example.com',
            'password' => 'password123',
        ]);

        $this->assertSame(Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());
        $data = $this->responseData();
        $this->assertArrayHasKey('id', $data);
        $this->assertSame('user@example.com', $data['email']);
    }

    public function test_register_fails_without_email(): void
    {
        $this->postJson('/api/auth/register', ['password' => 'password123']);

        $this->assertSame(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
    }

    public function test_register_fails_without_password(): void
    {
        $this->postJson('/api/auth/register', ['email' => 'user@example.com']);

        $this->assertSame(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
    }

    public function test_register_fails_with_duplicate_email(): void
    {
        $this->registerUser('dup@example.com');
        $this->postJson('/api/auth/register', ['email' => 'dup@example.com', 'password' => 'password123']);

        $this->assertSame(Response::HTTP_CONFLICT, $this->client->getResponse()->getStatusCode());
    }
}

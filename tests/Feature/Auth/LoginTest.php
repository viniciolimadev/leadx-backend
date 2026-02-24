<?php

namespace Tests\Feature\Auth;

use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class LoginTest extends TestCase
{
    public function test_login_returns_token_with_valid_credentials(): void
    {
        $this->registerUser('login@example.com', 'password123');
        $this->postJson('/api/auth/login', ['email' => 'login@example.com', 'password' => 'password123']);

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertArrayHasKey('token', $this->responseData());
    }

    public function test_login_fails_with_wrong_password(): void
    {
        $this->registerUser('login@example.com', 'password123');
        $this->postJson('/api/auth/login', ['email' => 'login@example.com', 'password' => 'wrong']);

        $this->assertSame(Response::HTTP_UNAUTHORIZED, $this->client->getResponse()->getStatusCode());
    }

    public function test_login_fails_with_unknown_email(): void
    {
        $this->postJson('/api/auth/login', ['email' => 'nobody@example.com', 'password' => 'password123']);

        $this->assertSame(Response::HTTP_UNAUTHORIZED, $this->client->getResponse()->getStatusCode());
    }
}

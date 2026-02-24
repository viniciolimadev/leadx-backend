<?php

namespace Tests\Feature\Api;

use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class MeTest extends TestCase
{
    public function test_me_returns_user_data_when_authenticated(): void
    {
        $this->registerUser('me@example.com', 'password123');
        $token = $this->loginAndGetToken('me@example.com', 'password123');
        $this->getJson('/api/me', $token);

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $data = $this->responseData();
        $this->assertSame('me@example.com', $data['email']);
        $this->assertContains('ROLE_USER', $data['roles']);
        $this->assertContains('ROLE_SELLER', $data['roles']);
    }

    public function test_me_returns_401_when_unauthenticated(): void
    {
        $this->getJson('/api/me');

        $this->assertSame(Response::HTTP_UNAUTHORIZED, $this->client->getResponse()->getStatusCode());
    }
}

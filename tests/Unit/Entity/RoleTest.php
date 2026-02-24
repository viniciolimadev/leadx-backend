<?php

namespace Tests\Unit\Entity;

use App\Entity\Role;
use PHPUnit\Framework\TestCase;

class RoleTest extends TestCase
{
    public function test_to_symfony_role_for_admin(): void
    {
        $this->assertSame('ROLE_ADMIN', (new Role('admin'))->toSymfonyRole());
    }

    public function test_to_symfony_role_for_manager(): void
    {
        $this->assertSame('ROLE_MANAGER', (new Role('manager'))->toSymfonyRole());
    }

    public function test_to_symfony_role_for_seller(): void
    {
        $this->assertSame('ROLE_SELLER', (new Role('seller'))->toSymfonyRole());
    }

    public function test_get_name_returns_constructor_value(): void
    {
        $this->assertSame('admin', (new Role('admin'))->getName());
    }
}

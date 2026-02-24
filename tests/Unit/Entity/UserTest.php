<?php

namespace Tests\Unit\Entity;

use App\Entity\Role;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function test_get_roles_always_includes_role_user(): void
    {
        $user = new User();

        $this->assertContains('ROLE_USER', $user->getRoles());
    }

    public function test_get_roles_returns_assigned_symfony_roles(): void
    {
        $user = new User();
        $user->setRoles(new Role('seller'));

        $roles = $user->getRoles();

        $this->assertContains('ROLE_SELLER', $roles);
        $this->assertContains('ROLE_USER', $roles);
    }

    public function test_set_roles_replaces_all_roles(): void
    {
        $user = new User();
        $user->setRoles(new Role('admin'));
        $user->setRoles(new Role('seller'));

        $roles = $user->getRoles();

        $this->assertContains('ROLE_SELLER', $roles);
        $this->assertNotContains('ROLE_ADMIN', $roles);
    }

    public function test_add_role_appends_without_duplicates(): void
    {
        $role = new Role('admin');
        $user = new User();
        $user->addRole($role);
        $user->addRole($role);

        $this->assertCount(2, $user->getRoles()); // ROLE_ADMIN + ROLE_USER
    }

    public function test_remove_role_detaches_role(): void
    {
        $role = new Role('admin');
        $user = new User();
        $user->addRole($role);
        $user->removeRole($role);

        $this->assertNotContains('ROLE_ADMIN', $user->getRoles());
    }

    public function test_get_user_identifier_returns_email(): void
    {
        $user = new User();
        $user->setEmail('test@example.com');

        $this->assertSame('test@example.com', $user->getUserIdentifier());
    }

    public function test_roles_are_unique(): void
    {
        $user = new User();
        $user->setRoles(new Role('admin'));

        $roles = $user->getRoles();

        $this->assertSame($roles, array_unique($roles));
    }
}

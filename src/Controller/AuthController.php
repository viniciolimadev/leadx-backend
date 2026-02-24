<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\RoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AuthController extends AbstractController
{
    public function login(): JsonResponse
    {
        // Handled automatically by LexikJWTAuthenticationBundle via json_login firewall.
        // This method is never reached for valid/invalid credentials.
        throw new \LogicException('This should not be called directly.');
    }

    public function register(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher,
        RoleRepository $roleRepository,
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (empty($data['email']) || empty($data['password'])) {
            return $this->json(['error' => 'Email and password are required.'], Response::HTTP_BAD_REQUEST);
        }

        if ($em->getRepository(User::class)->findOneBy(['email' => $data['email']])) {
            return $this->json(['error' => 'Email already in use.'], Response::HTTP_CONFLICT);
        }

        $role = $roleRepository->findByName('seller');

        $user = new User();
        $user->setEmail($data['email']);
        $user->setPassword($passwordHasher->hashPassword($user, $data['password']));
        $user->setRoles($role);

        $em->persist($user);
        $em->flush();

        return $this->json([
            'id'    => $user->getId(),
            'email' => $user->getEmail(),
        ], Response::HTTP_CREATED);
    }
}

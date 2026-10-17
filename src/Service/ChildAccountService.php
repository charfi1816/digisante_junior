<?php

namespace App\Service;

use App\Entity\Child;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class ChildAccountService
{
    public function __construct(
        private EntityManagerInterface      $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
        private ChildUsernameGenerator      $usernameGenerator
    )
    {
    }

    public function createAccount(
        Child  $child,
        User   $parent,
        string $password
    ): string
    {
        $childAccount = new User();

        $username = $this->usernameGenerator->generate($child->getFirstName());

        $childAccount->setUsername($username);
        $childAccount->setRoles([User::ROLE_CHILD]);

        $hashedPassword = $this->passwordHasher->hashPassword(
            $childAccount,
            $password
        );

        $childAccount->setPassword($hashedPassword);

        $child->setParent($parent);
        $child->setAccount($childAccount);

        $this->entityManager->persist($childAccount);
        $this->entityManager->persist($child);
        $this->entityManager->flush();

        return $username;
    }
}

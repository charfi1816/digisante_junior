<?php

namespace App\Service;

use App\Repository\UserRepository;
use Symfony\Component\String\Slugger\AsciiSlugger;

final class ChildUsernameGenerator
{
    public function __construct(
        private UserRepository $userRepository
    ) {
    }

    public function generate(string $firstName): string
    {
        $slugger = new AsciiSlugger();
        $pseudo = strtolower($slugger->slug($firstName)->toString());

        $username = $pseudo;
        $number = 2;

        $existingUser = $this->userRepository->findByUsername($username);

        while ($existingUser !== null) {
            $username = $pseudo . $number;
            $number++;

            $existingUser = $this->userRepository->findByUsername($username);
        }

        return $username;
    }
}

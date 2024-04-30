<?php

namespace Domain\User;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserRegistration
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function registerValidUser(User $user): void
    {
        $this->em->persist($user);
        $this->em->flush();
    }
}

<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->hasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('sample_user@localhost');
        $user->setRoles($user->getRoles());
        $hashedPassword = $this->hasher->hashPassword($user, '123456');
        $user->setPassword($hashedPassword);
        $manager->persist($user);
        $manager->flush();
    }
}

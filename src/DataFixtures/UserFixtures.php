<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserFixtures extends Fixture
{
    private $hasher;
        public function __construct(UserPasswordHasherInterface $hasher)
        {
            $this->hasher = $hasher;
        }

    public function load(ObjectManager $manager)
    {
        $admin = new User();
        $admin->setUsername("admin");
        $admin->setPassword($this->hasher->hashPassword($admin,"123"));
        $admin->setRoles(["ROLE_ADMIN"]);
        $manager->persist($admin);

        $user = new User();
        $user->setUsername("user");
        $user->setPassword($this->hasher->hashPassword($user,"123"));
        $user->setRoles(["ROLE_USER"]);
        $manager->persist($user);

        $user1 = new User();
        $user1->setUsername("user1");
        $user1->setPassword($this->hasher->hashPassword($user1,"123"));
        $user1->setRoles(["ROLE_USER"]);
        $manager->persist($user1);

        $manager->flush();
    }
}

<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

// use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\User;

class UserFixtures extends Fixture
{
    public const REFERENCE = 'user';

    public function __construct(
        private UserPasswordHasherInterface $hasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        $now = new \DateTime();

        $data = [
            [
                'identifier' => 1,
                'name' => "Admin",
                'lastname' => "Admin",
                'username' => "admin@titistamp.com",
                'role' => ["ROLE_ADMIN"],
            ],
            [
                'identifier' => 2,
                'name' => "Empresa",
                'lastname' => "Rubro uno",
                'username' => "empresa@titistamp.com",
                'role' => ["ROLE_COMMERCE"],
            ],
            [
                'identifier' => 3,
                'name' => "User",
                'lastname' => "Example",
                'username' => "user@titistamp.com",
                'role' => ["ROLE_USER"],
            ],
            [
                'identifier' => 4,
                'name' => "User 2",
                'lastname' => "Example",
                'username' => "user2@titistamp.com",
                'role' => ["ROLE_USER"],
            ]
        ];

        foreach ($data as $value) {
            $user = new User();

            $user->setName($value['name']);
            $user->setLastname($value['lastname']);
            $user->setUsername($value['username']);
            $user->setPassword(
                $this->hasher->hashPassword($user, '123456')
            );
            $user->setActive(true);
            $user->setCreatedAt($now);
            $user->setUpdatedAt($now);
            $user->setRoles($value['role']); 

            $manager->persist($user);

            $this->addReference(self::REFERENCE . $value['identifier'], $user);
        }

        $manager->flush();
    }
}
<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Entity\Stamp;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
// use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class StampFixtures extends Fixture implements DependentFixtureInterface
{
    public const REFERENCE = 'stamp';
    
    public function __construct()
    {
    }

    public function load(ObjectManager $manager): void
    {
        $firstUser = $this->getReference('user3', \App\Entity\User::class);
        $secondUser = $this->getReference('user4', \App\Entity\User::class);
        $commerce = $this->getReference('commerce1', \App\Entity\Commerce::class);
        
        $data = [
            [
                'identifier' => 1,   
                'user' => $firstUser,
                'commerce' => $commerce,
                'qty' => 5 // QTY STAMP TO GENERATE max 9   
            ],
            [
                'identifier' => 2,   
                'user' => $secondUser,
                'commerce' => $commerce,
                'qty' => 3 // QTY STAMP TO GENERATE max 9   
            ]
        ];

        foreach ($data as $value) {
            for ($i=0; $i < $value['qty']; $i++) {
                $new = new Stamp();
                $new->setUser($value['user']);
                $new->setCommerce($value['commerce']);
                $manager->persist($new);
                $this->addReference(self::REFERENCE . $value['identifier']. $i, $new);
            }
        }
        
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            CommerceFixtures::class
        ];
    }

}
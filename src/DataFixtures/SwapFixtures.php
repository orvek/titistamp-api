<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Entity\Swap;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
// use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class SwapFixtures extends Fixture implements DependentFixtureInterface
{
    public const REFERENCE = 'swap';
    
    public function __construct()
    {
    }

    public function load(ObjectManager $manager): void
    {
        $firstUser = $this->getReference('user3', \App\Entity\User::class);
        $secondUser = $this->getReference('user4', \App\Entity\User::class);
        $firstTicket = $this->getReference('ticket1', \App\Entity\Ticket::class);
        $secondTicket = $this->getReference('ticket2', \App\Entity\Ticket::class);
        
        $data = [
            [
                'identifier' => 1,
                'user' => $firstUser, 
                'ticket' => $firstTicket
            ],
            [
                'identifier' => 2,
                'user' => $secondUser, 
                'ticket' => $secondTicket
            ]
        ];

        foreach ($data as $value) {
            $new = new Swap();
            $new->setUser($value['user']);
            $new->setTicket($value['ticket']); 
            $manager->persist($new);
            $this->addReference(self::REFERENCE . $value['identifier'], $new);
        }
        
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            TicketFixtures::class
        ];
    }

}
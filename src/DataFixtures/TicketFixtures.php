<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Entity\Ticket;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
// use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class TicketFixtures extends Fixture implements DependentFixtureInterface
{
    public const REFERENCE = 'ticket';
    
    public function __construct()
    {
    }

    public function load(ObjectManager $manager): void
    {
        $commerce = $this->getReference('commerce1', \App\Entity\Commerce::class);
        
        $data = [
            [
                'identifier' => 1,  
                'qty' => 10,  
                'image' => "default/ticket.png",
                'discount' => 10,  
                'total' => 10,
                'commerce' => $commerce, 
            ],
            [
                'identifier' => 2,  
                'qty' => 5,  
                'image' => "default/ticket.png",
                'discount' => 5,  
                'total' => 5,
                'commerce' => $commerce, 
            ]
        ];

        foreach ($data as $value) {
            $new = new Ticket();
            $new->setQty($value['qty']);
            $new->setImage($value['image']);
            $new->setDiscount($value['discount']);
            $new->setTotal($value['total']);
            $new->setCommerce($value['commerce']); 
            $manager->persist($new);
            $this->addReference(self::REFERENCE . $value['identifier'], $new);
        }
        
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CommerceFixtures::class
        ];
    }

}
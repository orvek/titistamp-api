<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Entity\CommerceCategory;

class CommerceCategoryFixtures extends Fixture
{
    public const REFERENCE = 'commercecategory';
    
    public function __construct()
    {
    }

    public function load(ObjectManager $manager): void
    {
        $data = [
            [
                'identifier' => 1,  
                'name' => "Categoria 1",
            ],
            [
                'identifier' => 2,  
                'name' => "Categoria 2",
            ]
        ];

        foreach ($data as $value) {
            $new = new CommerceCategory();
            $new->setName($value['name']);
            $manager->persist($new);
            $this->addReference(self::REFERENCE . $value['identifier'], $new);
        }
        
        $manager->flush();
    }

}
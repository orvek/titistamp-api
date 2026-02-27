<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Entity\Commerce;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
// use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class CommerceFixtures extends Fixture implements DependentFixtureInterface
{
    public const REFERENCE = 'commerce';
    
    public function __construct()
    {
    }

    public function load(ObjectManager $manager): void
    {
        $owner = $this->getReference('user2', \App\Entity\User::class);
        $commerceCategory = $this->getReference('commercecategory1', \App\Entity\CommerceCategory::class);
        
        $data = [
            [
                'identifier' => 1,  
                'name' => "Empresa Rubro uno",  
                'description' => "Descricipn de empresa ...",  
                'logo' => "default/logo.png",
                'stamp' => "default/stamp.png",
                'address' => "Av. Siempre Viva 123",
                'lat' => "-34.603722",
                'lng' => "-58.381557",
                'owner' => $owner, 
                'category' => $commerceCategory, 
            ]
        ];

        foreach ($data as $value) {
            $new = new Commerce();
            $new->setName($value['name']);
            $new->setDescription($value['description']);
            $new->setLogo($value['logo']);
            $new->setStamp($value['stamp']);
            $new->setAddress($value['address']);
            $new->setLat($value['lat']);
            $new->setLng($value['lng']);
            $new->setOwner($value['owner']);
            $new->setCategory($value['category']);
            $manager->persist($new);
            $this->addReference(self::REFERENCE . $value['identifier'], $new);
        }
        
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            CommerceCategoryFixtures::class,
        ];
    }

}
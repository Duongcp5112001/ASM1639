<?php

namespace App\DataFixtures;

use App\Entity\Type;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TypeFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i=0;$i<=10;$i++){
            $type = new Type();
            $type->setName("Type $i");

            $manager->persist($type);
            }
    
            $manager->flush();
    }
}

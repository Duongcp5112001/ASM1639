<?php

namespace App\DataFixtures;

use App\Entity\Book;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BookFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($i=1;$i<10;$i++){
            $book = new Book();
            $book->setTitle("Book title $i");
            $manager->persist($book);
        }

        $manager->flush();
    }
}

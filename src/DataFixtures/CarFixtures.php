<?php

namespace App\DataFixtures;

use App\Entity\Car;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\ByteString; 

class CarFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($k=0; $k<=5; $k++) {
            $car = new Car();
            $car->setName(ByteString::fromRandom(20)->toString());
            $car->setCreatedAt(new \DateTime());
            $car->setUpdatedAt(new \DateTime());
            $manager->persist($car);
        }

        $manager->flush();
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\Track;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\ByteString;

class TrackFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($k = 0; $k <= 5; $k++) {
            $track = new Track();
            $track->setName(ByteString::fromRandom(20)->toString());
            $track->setCreatedAt(new DateTime());
            $track->setUpdatedAt(new DateTime());
            $manager->persist($track);
        }

        $manager->flush();
    }
}

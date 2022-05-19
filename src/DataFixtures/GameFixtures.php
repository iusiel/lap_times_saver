<?php

namespace App\DataFixtures;

use App\Entity\Game;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\ByteString;

class GameFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $maxEntries = 5;
        for ($k = 0; $k <= $maxEntries; $k++) {
            $game = new Game();
            $game->setName(ByteString::fromRandom(20)->toString());
            $game->setCreatedAt(new DateTime());
            $game->setUpdatedAt(new DateTime());
            $manager->persist($game);
        }

        $manager->flush();
    }
}

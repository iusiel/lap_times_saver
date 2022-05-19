<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class GroupFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @SuppressWarnings(PHPMD)
     */
    public function load(ObjectManager $manager)
    {
        // ...
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            GameFixtures::class,
            CarFixtures::class,
            TrackFixtures::class,
            LapTimeFixtures::class,
        ];
    }
}

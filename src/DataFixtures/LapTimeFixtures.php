<?php

namespace App\DataFixtures;

use App\Entity\LapTime;
use App\Repository\CarRepository;
use App\Repository\GameRepository;
use App\Repository\TrackRepository;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\ByteString;

class LapTimeFixtures extends Fixture
{
    private $gameRepository;
    private $carRepository;
    private $trackRepository;

    public function __construct(GameRepository $gameRepository, CarRepository $carRepository, TrackRepository $trackRepository)
    {
        $this->gameRepository = $gameRepository;
        $this->carRepository = $carRepository;
        $this->trackRepository = $trackRepository;
    }

    public function load(ObjectManager $manager): void
    {
        $car = $this->carRepository->find(1);
        $game = $this->gameRepository->find(1);
        $track = $this->trackRepository->find(1);

        for ($k = 0; $k <= 5; $k++) {
            $laptime = new LapTime();
            $laptime->setDate(new DateTime());
            $laptime->setCar($car);
            $laptime->setGame($game);
            $laptime->setTrack($track);
            $laptime->setTime("02:00");
            $laptime->setIsPractice(true);
            $laptime->setExtraNotes(ByteString::fromRandom(20)->toString());
            $laptime->setCreatedAt(new DateTime());
            $laptime->setUpdatedAt(new DateTime());
            $manager->persist($laptime);
        }

        $manager->flush();
    }
}

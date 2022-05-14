<?php

namespace App\Services;

use App\Repository\CarRepository;
use App\Repository\GameRepository;
use App\Repository\TrackRepository;

class LapTimeDashboardAccess
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

    public function allowAccess()
    {
        return (!empty($this->gameRepository->findOne()) && !empty($this->carRepository->findOne()) && !empty($this->trackRepository->findOne()));
    }
}
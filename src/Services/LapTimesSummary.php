<?php

namespace App\Services;

use App\Repository\LapTimeRepository;
use DateTime;

class LapTimesSummary
{
    private $lapTimeRepository;

    public function __construct(LapTimeRepository $lapTimeRepository)
    {
        $this->lapTimeRepository = $lapTimeRepository;
    }

    public function getSummaryFor(array $filter)
    {
        return $this->getSummary($this->lapTimeRepository->findBy($filter));
    }

    public function getSummaryForAll()
    {
        return $this->getSummary($this->lapTimeRepository->findAll());
    }

    private function getSummary($laptimeEntities)
    {
        $groupedLapTimes = [];
        foreach ($laptimeEntities as $lapTime) {
            $groupName =
                $lapTime->getGame()->getName() .
                '-' .
                $lapTime->getTrack()->getName() .
                '-' .
                $lapTime->getCar()->getName();
            if (empty($groupedLapTimes[$groupName])) {
                $groupedLapTimes[$groupName] = [
                    'game' => $lapTime->getGame(),
                    'track' => $lapTime->getTrack(),
                    'car' => $lapTime->getCar(),
                    'lap_times' => [],
                ];
            }
            $groupedLapTimes[$groupName]['lap_times'][] = $lapTime;
        }

        foreach ($groupedLapTimes as &$group) {
            $group['averageLapTime'] = $this->getAverageLapTime(
                $group['lap_times']
            );
            $group['medianLapTime'] = $this->getMedianLap($group['lap_times']);
            $group['personalBestLapTime'] = $this->getPersonalBest(
                $group['lap_times']
            );
        }

        return $groupedLapTimes;
    }

    private function getAverageLapTime(array $lapTimes)
    {
        $total = 0;
        $microSecondsTotal = 0;
        foreach ($lapTimes as $lapTime) {
            $date = new DateTime($lapTime->getTime());
            $microSecondsTotal += floatval('0.' . $date->format('u'));
            $total += $date->getTimestamp() + $microSecondsTotal;
        }

        $parts = explode('.', $total / count($lapTimes));
        $microSeconds = $parts[1] ?? 0;
        $averageMicroSeconds = number_format(
            round('.' . $microSeconds, 3),
            3,
            '.',
            ''
        );
        $averageMicroSeconds = str_replace('0.', '', $averageMicroSeconds);

        return date('H:i:s', $parts[0]) . '.' . $averageMicroSeconds;
    }

    private function getSortedArray(array $lapTimes)
    {
        $arrayToSort = [];
        foreach ($lapTimes as $lapTime) {
            $explodedTime = explode('.', $lapTime->getTime()); // try to get microseconds in time. 2nd element should contain microseconds if it exists
            $microSecondsTotal = !empty($explodedTime[1])
                ? floatval($explodedTime[1] / 1000)
                : 0;
            $arrayToSort[$lapTime->getTime()] =
                strtotime($explodedTime[0]) + $microSecondsTotal;
        }
        uasort($arrayToSort, function ($a, $b) {
            if ($a == $b) {
                return 0;
            }
            return $a < $b ? -1 : 1;
        });
        return $arrayToSort; //return array sorted by fastest lap time. Readable lap time is stored in the index of the array
    }

    private function getMedianLap(array $lapTimes)
    {
        $sortedArray = $this->getSortedArray($lapTimes);
        return key(
            array_slice($sortedArray, floor(count($sortedArray) / 2), 1)
        ); // get middle element in the array
    }

    private function getPersonalBest(array $lapTimes)
    {
        $sortedArray = $this->getSortedArray($lapTimes);
        return key(array_slice($sortedArray, 0, 1)); // return index of first element in array
    }
}

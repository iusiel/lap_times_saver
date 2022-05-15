<?php

namespace App\Services;

use App\Repository\LapTimeRepository;

class LapTimesSummary
{
    private $lapTimeRepository;

    public function __construct(LapTimeRepository $lapTimeRepository)
    {
        $this->lapTimeRepository = $lapTimeRepository;    
    }

    public function getSummaryForAll()
    {
        $allLapTimes = $this->lapTimeRepository->findAll();
        $groupedLapTimes = [];
        foreach ($allLapTimes as $lapTime) {
            $groupName = $lapTime->getGame()->getName() . "-" . $lapTime->getTrack()->getName() . "-" . $lapTime->getCar()->getName();
            if (empty($groupedLapTimes[$groupName])) {
                $groupedLapTimes[$groupName] = [
                    'game' => $lapTime->getGame()->getName(),
                    'track' => $lapTime->getTrack()->getName(),
                    'car' => $lapTime->getCar()->getName(),
                    'lap_times' => [],
                ];
            }
            $groupedLapTimes[$groupName]['lap_times'][] = $lapTime->getTime();
        }

        foreach ($groupedLapTimes as &$group) {
            $group['averageLapTime'] = $this->getAverageLapTime($group['lap_times']);
            $group['medianLapTime'] = $this->getMedianLap($group['lap_times']);
            $group['personalBestLapTime'] = $this->getPersonalBest($group['lap_times']);
        }

        return $groupedLapTimes;
    }

    private function getAverageLapTime(array $lapTimes)
    {
        $total = 0;
        $milliSecondsTotal = 0;
        foreach ($lapTimes as $lapTime) {
            $explodedTime = explode(".", $lapTime); // try to get millisecond in time. 2nd element should contain milliseconds if it exists
            $milliSecondsTotal += (!empty($explodedTime[1])) ? floatval($explodedTime[1]) : 0;
            $total += strtotime($lapTime);
        }
        $averageMilliseconds = round($milliSecondsTotal / 1000 / count($lapTimes), 3);
        $averageMilliseconds = array_sum(explode(".", $averageMilliseconds));
        return date("H:i:s", $total / count($lapTimes)) . "." . $averageMilliseconds;
    }

    private function getSortedArray(array $lapTimes)
    {
        $arrayToSort = [];
        foreach ($lapTimes as $lapTime) {
            $explodedTime = explode(".", $lapTime); // try to get millisecond in time. 2nd element should contain milliseconds if it exists
            $milliSecondsTotal = (!empty($explodedTime[1])) ? floatval($explodedTime[1] / 1000) : 0;
            $arrayToSort[$lapTime] = strtotime($explodedTime[0]) + $milliSecondsTotal;
        }
        uasort($arrayToSort, function ($a, $b) {
            if ($a == $b) {
                return 0;
            }
            return ($a < $b) ? -1 : 1;
        });
        return $arrayToSort; //return array sorted by fastest lap time. Readable lap time is stored in the index of the array
    }

    private function getMedianLap(array $lapTimes)
    {
        $sortedArray = $this->getSortedArray($lapTimes);
        return key(array_slice($sortedArray, floor(count($sortedArray) / 2), 1)); // get middle element in the array
    }

    private function getPersonalBest(array $lapTimes)
    {
        $sortedArray = $this->getSortedArray($lapTimes);
        return key(array_slice($sortedArray, 0, 1)); // return index of first element in array
    }
}
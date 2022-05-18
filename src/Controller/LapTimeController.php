<?php

namespace App\Controller;

use App\Entity\LapTime;
use App\Form\LapTimeType;
use App\Repository\LapTimeRepository;
use App\Services\LapTimeDashboardAccess;
use App\Services\LapTimesSummary;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

#[IsGranted('ROLE_ADMIN')]
#[Route('/lap_time')]
class LapTimeController extends AbstractController
{
    private $lapTimeDashboardAccess;

    public function __construct(LapTimeDashboardAccess $lapTimeDashboardAccess)
    {
        $this->lapTimeDashboardAccess = $lapTimeDashboardAccess;
    }

    #[Route('/', name: 'app_lap_time_index', methods: ['GET'])]
    public function index(LapTimeRepository $lapTimeRepository): Response
    {
        if ($this->lapTimeDashboardAccess->allowAccess() === false) {
            return $this->redirectToRoute('app_home');
        }

        return $this->render(
            'lap_time/index.html.twig',
            [
            'lap_times' => $lapTimeRepository->findAll(),
            ]
        );
    }

    #[Route('/new', name: 'app_lap_time_new', methods: ['GET', 'POST'])]
    public function new(Request $request, LapTimeRepository $lapTimeRepository): Response
    {
        if ($this->lapTimeDashboardAccess->allowAccess() === false) {
            return $this->redirectToRoute('app_home');
        }

        $lapTime = new LapTime();
        $form = $this->createForm(LapTimeType::class, $lapTime);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $lapTime->setCreatedAt(new DateTime());
            $lapTime->setUpdatedAt(new DateTime());
            $lapTimeRepository->add($lapTime);
            return $this->redirectToRoute('app_lap_time_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm(
            'lap_time/new.html.twig',
            [
            'lap_time' => $lapTime,
            'form' => $form,
            ]
        );
    }

    #[Route('/{id}/edit', name: 'app_lap_time_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, LapTime $lapTime, LapTimeRepository $lapTimeRepository): Response
    {
        if ($this->lapTimeDashboardAccess->allowAccess() === false) {
            return $this->redirectToRoute('app_home');
        }

        $form = $this->createForm(LapTimeType::class, $lapTime);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $lapTime->setUpdatedAt(new DateTime());
            $lapTimeRepository->add($lapTime);
            return $this->redirectToRoute('app_lap_time_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm(
            'lap_time/edit.html.twig',
            [
            'lap_time' => $lapTime,
            'form' => $form,
            ]
        );
    }

    #[Route('/{id}', name: 'app_lap_time_delete', methods: ['POST'])]
    public function delete(Request $request, LapTime $lapTime, LapTimeRepository $lapTimeRepository): Response
    {
        if ($this->lapTimeDashboardAccess->allowAccess() === false) {
            return $this->redirectToRoute('app_home');
        }

        if ($this->isCsrfTokenValid('delete' . $lapTime->getId(), $request->request->get('_token'))) {
            $lapTimeRepository->remove($lapTime);
        }

        return $this->redirectToRoute('app_lap_time_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/summary', name: 'app_lap_time_summary', methods: ['GET'])]
    public function showSummary(LapTimesSummary $lapTimesSummary): Response
    {
        if ($this->lapTimeDashboardAccess->allowAccess() === false) {
            return $this->redirectToRoute('app_home');
        }

        return $this->renderForm(
            'lap_time/summary.html.twig',
            [
            'summary' => $lapTimesSummary->getSummaryForAll(),
            ]
        );
    }

    #[Route('/chart', name: 'app_lap_time_chart', methods: ['GET'])]
    public function showChart(Request $request, ChartBuilderInterface $chartBuilder, LapTimesSummary $lapTimesSummary): Response
    {
        if ($this->lapTimeDashboardAccess->allowAccess() === false) {
            return $this->redirectToRoute('app_home');
        }

        $filter = [
            'Game' => $request->query->get('game'),
            'Car' => $request->query->get('car'),
            'Track' => $request->query->get('track'),
        ];

        $summary = $lapTimesSummary->getSummaryFor($filter);
        if (empty($summary)) {
            return $this->redirectToRoute('app_lap_time_summary');
        }

        // prepare labels and datasets
        $summaryLabelsAndData = $this->getLabelsAndDatasets($summary);
        $label = $summaryLabelsAndData['label'];
        $xAxis = $summaryLabelsAndData['xAxis'];
        $yAxis = $summaryLabelsAndData['yAxis'];
        // end prepare labels and datasets

        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);
        $chart->setData(
            [
            'labels' => $xAxis,
            'datasets' => [
                [
                    'label' => $label,
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => $yAxis,
                ],
            ],
            ]
        );

        return $this->renderForm(
            'lap_time/chart.html.twig',
            [
                'chart' => $chart,
            ]
        );
    }

    private function getLabelsAndDatasets($summary)
    {
        foreach ($summary as $group) {
            $label = "Lap times for " . $group['game']->getName() . " - " . $group['car']->getName() . " - " . $group['track']->getName();

            $xAxis = [];
            $yAxis = [];
            foreach ($group['lap_times'] as $lapTime) {
                $xAxis[] = $lapTime->getDate()->format("F d, Y");
                $exploded = explode(".", $lapTime->getTime());
                $milliSeconds = (!empty($exploded[1])) ? "." . $exploded[1] : "";
                $yAxis[] = strtotime($lapTime->getTime()) . $milliSeconds;
            }
        }

        return [
            'label' => $label,
            'yAxis' => $yAxis,
            'xAxis' => $xAxis,
        ];
    }
}

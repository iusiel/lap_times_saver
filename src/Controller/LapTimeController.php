<?php

namespace App\Controller;

use App\Entity\LapTime;
use App\Form\LapTimeType;
use App\Repository\LapTimeRepository;
use App\Services\LapTimeDashboardAccess;
use App\Services\LapTimesSummary;
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

        return $this->render('lap_time/index.html.twig', [
            'lap_times' => $lapTimeRepository->findAll(),
        ]);
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
            $lapTime->setCreatedAt(new \DateTime());
            $lapTime->setUpdatedAt(new \DateTime());
            $lapTimeRepository->add($lapTime);
            return $this->redirectToRoute('app_lap_time_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('lap_time/new.html.twig', [
            'lap_time' => $lapTime,
            'form' => $form,
        ]);
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
            $lapTime->setUpdatedAt(new \DateTime());
            $lapTimeRepository->add($lapTime);
            return $this->redirectToRoute('app_lap_time_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('lap_time/edit.html.twig', [
            'lap_time' => $lapTime,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_lap_time_delete', methods: ['POST'])]
    public function delete(Request $request, LapTime $lapTime, LapTimeRepository $lapTimeRepository): Response
    {
        if ($this->lapTimeDashboardAccess->allowAccess() === false) {
            return $this->redirectToRoute('app_home');
        }

        if ($this->isCsrfTokenValid('delete'.$lapTime->getId(), $request->request->get('_token'))) {
            $lapTimeRepository->remove($lapTime);
        }

        return $this->redirectToRoute('app_lap_time_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/summary', name: 'app_lap_time_summary', methods: ['GET'])]
    public function showSummary(Request $request, LapTimesSummary $lapTimesSummary): Response
    {
        if ($this->lapTimeDashboardAccess->allowAccess() === false) {
            return $this->redirectToRoute('app_home');
        }

        return $this->renderForm('lap_time/summary.html.twig', [
            'summary' => $lapTimesSummary->getSummaryForAll(),
        ]);

    }

    #[Route('/chart', name: 'app_lap_time_chart', methods: ['GET'])]
    public function showChart(Request $request, ChartBuilderInterface $chartBuilder): Response
    {
        if ($this->lapTimeDashboardAccess->allowAccess() === false) {
            return $this->redirectToRoute('app_home');
        }

        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);

        $chart->setData([
            'labels' => ['May 1, 2022', 'May 2, 2022', 'May 3, 2022', 'May 4, 2022', 'May 5, 2022', 'May 6, 2022', 'May 7, 2022'],
            'datasets' => [
                [
                    'label' => 'My First dataset',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => [strtotime("03:00:00"), strtotime("03:00:00") . ".123", strtotime("03:00:00"), strtotime("03:00:00"), strtotime("03:00:00"), strtotime("03:00:00"), strtotime("03:00:00")],
                ],
            ],
        ]);

        $chart->setOptions([
            'scales' => [
                'y' => [
                    // 'ticks' => [
                    //     'callback' => 'Test',
                    // ],
                    // 'suggestedMin' => 0,
                    // 'suggestedMax' => 100,
                ],
            ],
        ]);

        return $this->renderForm('lap_time/chart.html.twig', [
            'chart' => $chart,
        ]);

    }
}

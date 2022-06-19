<?php

namespace App\Controller;

use App\Entity\Track;
use App\Form\TrackType;
use App\Repository\TrackRepository;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_ADMIN')]
#[Route('/track')]
class TrackController extends AbstractController
{
    #[Route('/', name: 'app_track_index', methods: ['GET'])]
    public function index(TrackRepository $trackRepository): Response {
        return $this->render('track/index.html.twig', [
            'tracks' => $trackRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_track_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        TrackRepository $trackRepository
    ): Response {
        $track = new Track();
        $form = $this->createForm(TrackType::class, $track);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $track->setCreatedAt(new DateTime());
            $track->setUpdatedAt(new DateTime());
            $trackRepository->add($track);
            return $this->redirectToRoute(
                'app_track_index',
                [],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->renderForm('track/new.html.twig', [
            'track' => $track,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_track_show', methods: ['GET'])]
    public function show(Track $track): Response {
        return $this->render('track/show.html.twig', [
            'track' => $track,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_track_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Track $track,
        TrackRepository $trackRepository
    ): Response {
        $form = $this->createForm(TrackType::class, $track);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $track->setUpdatedAt(new DateTime());
            $trackRepository->add($track);
            return $this->redirectToRoute(
                'app_track_index',
                [],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->renderForm('track/edit.html.twig', [
            'track' => $track,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_track_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Track $track,
        TrackRepository $trackRepository
    ): Response {
        if (
            $this->isCsrfTokenValid(
                'delete' . $track->getId(),
                $request->request->get('_token')
            )
        ) {
            $trackRepository->remove($track);
        }

        return $this->redirectToRoute(
            'app_track_index',
            [],
            Response::HTTP_SEE_OTHER
        );
    }
}

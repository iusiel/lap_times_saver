<?php

namespace App\Controller;

use App\Entity\Game;
use App\Form\GameType;
use App\Repository\GameRepository;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_ADMIN')]
#[Route('/game')]
class GameController extends AbstractController
{
    #[Route('/', name: 'app_game_index', methods: ['GET'])]
    public function index(GameRepository $gameRepository): Response
    {
        return $this->render(
            'game/index.html.twig',
            [
            'games' => $gameRepository->findAll(),
            ]
        );
    }

    #[Route('/new', name: 'app_game_new', methods: ['GET', 'POST'])]
    public function new(Request $request, GameRepository $gameRepository): Response
    {
        $game = new Game();
        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $game->setCreatedAt(new DateTime());
            $game->setUpdatedAt(new DateTime());
            $gameRepository->add($game);
            return $this->redirectToRoute('app_game_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm(
            'game/new.html.twig',
            [
            'game' => $game,
            'form' => $form,
            ]
        );
    }

    #[Route('/{id}/edit', name: 'app_game_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Game $game, GameRepository $gameRepository): Response
    {
        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $gameRepository->add($game);
            return $this->redirectToRoute('app_game_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm(
            'game/edit.html.twig',
            [
            'game' => $game,
            'form' => $form,
            ]
        );
    }

    #[Route('/{id}', name: 'app_game_delete', methods: ['POST'])]
    public function delete(Request $request, Game $game, GameRepository $gameRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $game->getId(), $request->request->get('_token'))) {
            $gameRepository->remove($game);
        }

        return $this->redirectToRoute('app_game_index', [], Response::HTTP_SEE_OTHER);
    }
}

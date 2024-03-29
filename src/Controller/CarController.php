<?php

namespace App\Controller;

use App\Entity\Car;
use App\Form\CarType;
use App\Repository\CarRepository;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_ADMIN')]
#[Route('/car')]
class CarController extends AbstractController
{
    #[Route('/', name: 'app_car_index', methods: ['GET'])]
    public function index(CarRepository $carRepository): Response {
        return $this->render('car/index.html.twig', [
            'cars' => $carRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_car_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        CarRepository $carRepository
    ): Response {
        $car = new Car();
        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $car->setCreatedAt(new DateTime());
            $car->setUpdatedAt(new DateTime());
            $carRepository->add($car);
            return $this->redirectToRoute(
                'app_car_index',
                [],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->renderForm('car/new.html.twig', [
            'car' => $car,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_car_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Car $car,
        CarRepository $carRepository
    ): Response {
        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $car->setUpdatedAt(new DateTime());
            $carRepository->add($car);
            return $this->redirectToRoute(
                'app_car_index',
                [],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->renderForm('car/edit.html.twig', [
            'car' => $car,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_car_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Car $car,
        CarRepository $carRepository
    ): Response {
        if (
            $this->isCsrfTokenValid(
                'delete' . $car->getId(),
                $request->request->get('_token')
            )
        ) {
            $carRepository->remove($car);
        }

        return $this->redirectToRoute(
            'app_car_index',
            [],
            Response::HTTP_SEE_OTHER
        );
    }

    #[Route('/last', name: 'app_car_last', methods: ['GET'])]
    public function getLast(CarRepository $carRepository): Response {
        $car = $carRepository->findOneBy([], ['id' => 'desc']);
        return $this->json([
            'id' => $car->getId(),
            'name' => $car->getName(),
        ]);
    }
}

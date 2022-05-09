<?php

namespace App\Tests;

use App\Entity\Game;
use App\Form\GameType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\UrlHelper;

class GamesDashboardTest extends WebTestCase
{
    public function testNotLoggedIn(): void
    {
        $client = static::createClient();
        $container = static::getContainer();
        $urlGenerator = $container->get(UrlHelper::class);
        $crawler = $client->request('GET', "/game/");
        $this->assertResponseRedirects($urlGenerator->getAbsoluteUrl('/login'));
    }


    public function testGameDashboard(): void
    {
        $client = static::createClient();
        $container = static::getContainer();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->find(1);
        $client->loginUser($testUser);
        $urlGenerator = $container->get(UrlHelper::class);
        $crawler = $client->request('GET', "/game/");
        $this->assertResponseIsSuccessful();
    }

    public function testShowAddNewGameScreen(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->find(1);
        $client->loginUser($testUser);
        $crawler = $client->request('GET', "/game/new");
        $this->assertResponseIsSuccessful();
    }

    public function testAddNewGame(): void
    {
        $client = static::createClient();
        $container = static::getContainer();
        $game = new Game();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->find(1);
        $client->loginUser($testUser);
        $crawler = $client->request('POST', "/game/new", [
            'game' => [
                'Name' => 'Test'
            ],
        ]);
        $urlGenerator = $container->get(UrlHelper::class);
        $this->assertResponseRedirects('/game/');
    }
}

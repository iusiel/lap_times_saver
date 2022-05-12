<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\UrlHelper;

class TracksDashboardTest extends WebTestCase
{
    public function testNotLoggedIn(): void
    {
        $client = static::createClient();
        $container = static::getContainer();
        $urlGenerator = $container->get(UrlHelper::class);
        $crawler = $client->request('GET', "/track/");
        $this->assertResponseRedirects($urlGenerator->getAbsoluteUrl('/login'));
    }


    public function testTrackDashboard(): void
    {
        $client = static::createClient();
        $container = static::getContainer();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->find(1);
        $client->loginUser($testUser);
        $crawler = $client->request('GET', "/track/");
        $this->assertResponseIsSuccessful();
    }

    public function testSShowTrackDashboardPage(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->find(1);
        $client->loginUser($testUser);
        $crawler = $client->request('GET', "/track/new");
        $this->assertResponseIsSuccessful();
    }

    public function testAddNewTrackFunction(): void
    {
        $client = static::createClient();
        $container = static::getContainer();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->find(1);
        $client->loginUser($testUser);
        $crawler = $client->request('POST', "/track/new", [
            'track' => [
                'Name' => 'Test'
            ],
        ]);
        $urlGenerator = $container->get(UrlHelper::class);
        $this->assertResponseRedirects('/track/');
    }

    public function testShowEditTrackPage(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->find(1);
        $client->loginUser($testUser);
        $crawler = $client->request('GET', "/track/1/edit");
        $this->assertResponseIsSuccessful();
    }


    public function testEditTrackFunction(): void
    {
        $client = static::createClient();
        $container = static::getContainer();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->find(1);
        $client->loginUser($testUser);
        $crawler = $client->request('POST', "/track/1/edit", [
            'track' => [
                'Name' => 'Edited title'
            ],
        ]);
        $urlGenerator = $container->get(UrlHelper::class);
        $this->assertResponseRedirects('/track/');
    }

    public function testDeleTrackFunction(): void
    {
        $client = static::createClient();
        $container = static::getContainer();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->find(1);
        $client->loginUser($testUser);
        $crawler = $client->request('POST', "/track/1");
        $this->assertResponseRedirects('/track/');
    }
}

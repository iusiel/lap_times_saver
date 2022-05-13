<?php

namespace App\Tests;

use App\Entity\Car;
use App\Form\CarType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\UrlHelper;

class CarsDashboardTest extends WebTestCase
{
    public function testNotLoggedIn(): void
    {
        $client = static::createClient();
        $container = static::getContainer();
        $urlGenerator = $container->get(UrlHelper::class);
        $crawler = $client->request('GET', "/car/");
        $this->assertResponseRedirects($urlGenerator->getAbsoluteUrl('/login'));
    }


    public function testCarDashboard(): void
    {
        $client = static::createClient();
        $container = static::getContainer();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOne();
        $client->loginUser($testUser);
        $urlGenerator = $container->get(UrlHelper::class);
        $crawler = $client->request('GET', "/car/");
        $this->assertResponseIsSuccessful();
    }

    public function testShowAddCarPage(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOne();
        $client->loginUser($testUser);
        $crawler = $client->request('GET', "/car/new");
        $this->assertResponseIsSuccessful();
    }

    public function testAddNewCarFunction(): void
    {
        $client = static::createClient();
        $container = static::getContainer();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOne();
        $client->loginUser($testUser);
        $crawler = $client->request('POST', "/car/new", [
            'car' => [
                'Name' => 'Test'
            ],
        ]);
        $urlGenerator = $container->get(UrlHelper::class);
        $this->assertResponseRedirects('/car/');
    }

    public function testShowEditCarPage(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOne();
        $client->loginUser($testUser);
        $crawler = $client->request('GET', "/car/1/edit");
        $this->assertResponseIsSuccessful();
    }


    public function testEditGameFunction(): void
    {
        $client = static::createClient();
        $container = static::getContainer();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOne();
        $client->loginUser($testUser);
        $crawler = $client->request('POST', "/car/1/edit", [
            'car' => [
                'Name' => 'Edited title'
            ],
        ]);
        $urlGenerator = $container->get(UrlHelper::class);
        $this->assertResponseRedirects('/car/');
    }

    public function testDeleteGameFunction(): void
    {
        $client = static::createClient();
        $container = static::getContainer();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOne();
        $client->loginUser($testUser);
        $crawler = $client->request('POST', "/car/1");
        $urlGenerator = $container->get(UrlHelper::class);
        $this->assertResponseRedirects('/car/');
    }
}

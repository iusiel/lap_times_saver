<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\String\ByteString;

class LapTimesDashboardTest extends WebTestCase
{
    public function testNotLoggedIn(): void
    {
        $client = static::createClient();
        $container = static::getContainer();
        $urlGenerator = $container->get(UrlHelper::class);
        $crawler = $client->request('GET', '/lap_time/');
        $this->assertResponseRedirects($urlGenerator->getAbsoluteUrl('/login'));
    }

    public function testLapTimeDashboard(): void
    {
        $client = static::createClient();
        $container = static::getContainer();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOne();
        $client->loginUser($testUser);
        $crawler = $client->request('GET', '/lap_time/');
        $this->assertResponseIsSuccessful();
    }

    public function testShowAddNewLapTimePage(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOne();
        $client->loginUser($testUser);
        $crawler = $client->request('GET', '/lap_time/new');
        $this->assertResponseIsSuccessful();
    }

    public function testAddNewLapTimeFunction(): void
    {
        $client = static::createClient();
        $container = static::getContainer();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOne();
        $client->loginUser($testUser);
        $crawler = $client->request('POST', '/lap_time/new', [
            'lap_time' => [
                'Date' => date('Y-m-d'),
                'Game' => '1',
                'Car' => '1',
                'Track' => '1',
                'Time' => '02:00',
                'IsPractice' => '1',
                'ExtraNotes' => ByteString::fromRandom(30)->toString(),
            ],
        ]);
        $this->assertResponseRedirects('/lap_time/');
    }

    public function testShowEditLapTimePage(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOne();
        $client->loginUser($testUser);
        $crawler = $client->request('GET', '/lap_time/1/edit');
        $this->assertResponseIsSuccessful();
    }

    public function testEditLapTimeFunction(): void
    {
        $client = static::createClient();
        $container = static::getContainer();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOne();
        $client->loginUser($testUser);
        $crawler = $client->request('POST', '/lap_time/1/edit', [
            'lap_time' => [
                'Date' => date('Y-m-d'),
                'Game' => '1',
                'Car' => '1',
                'Track' => '1',
                'Time' => '02:00',
                'IsPractice' => '1',
                'ExtraNotes' => ByteString::fromRandom(30)->toString(),
            ],
        ]);
        $this->assertResponseRedirects('/lap_time/');
    }

    public function testDeleteLapTimeFunction(): void
    {
        $client = static::createClient();
        $container = static::getContainer();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOne();
        $client->loginUser($testUser);
        $crawler = $client->request('POST', '/lap_time/1');
        $this->assertResponseRedirects('/lap_time/');
    }

    public function testShowSummaryPage(): void
    {
        $client = static::createClient();
        $container = static::getContainer();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOne();
        $client->loginUser($testUser);
        $crawler = $client->request('GET', '/lap_time/summary');
        $this->assertResponseIsSuccessful();
    }

    public function testShowChartWithFilterPage(): void
    {
        $client = static::createClient();
        $container = static::getContainer();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOne();
        $client->loginUser($testUser);
        $crawler = $client->request(
            'GET',
            '/lap_time/chart?game=1&car=1&track=1'
        );
        $this->assertResponseIsSuccessful();
    }

    public function testShowChartWithNoFilterPage(): void
    {
        $client = static::createClient();
        $container = static::getContainer();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOne();
        $client->loginUser($testUser);
        $crawler = $client->request('GET', '/lap_time/chart');
        $this->assertResponseRedirects('/lap_time/summary');
    }
}

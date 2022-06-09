<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\UrlHelper;

class LoginTest extends WebTestCase
{
    public function testRedirectToLoginPageIfUserIsNotYetLoggedIn(): void
    {
        $client = static::createClient();
        self::bootKernel();
        $container = static::getContainer();
        $urlGenerator = $container->get(UrlHelper::class);

        $crawler = $client->request('GET', '/');
        $this->assertResponseRedirects($urlGenerator->getAbsoluteUrl('/login'));
    }

    public function testSuccessfulLogin()
    {
        $client = static::createClient();
        self::bootKernel();
        $container = static::getContainer();
        $urlGenerator = $container->get(UrlHelper::class);

        $client->request('GET', '/login');
        $crawler = $client->submitForm('Login', [
            '_username' => 'sample_user@localhost',
            '_password' => '123456',
        ]);

        $this->assertResponseRedirects($urlGenerator->getAbsoluteUrl('/'));
    }

    public function testFailedLogin()
    {
        $client = static::createClient();
        self::bootKernel();
        $container = static::getContainer();
        $urlGenerator = $container->get(UrlHelper::class);

        $client->request('GET', '/login');
        $crawler = $client->submitForm('Login', [
            '_username' => 'sample_user@localhost',
            '_password' => '12345677',
        ]);
        $this->assertResponseRedirects($urlGenerator->getAbsoluteUrl('/login'));

        $crawler = $client->followRedirect();
        $errorMessage = $crawler
            ->filter('.alert-danger')
            ->first()
            ->text();
        $this->assertEquals($errorMessage, 'Invalid credentials.');
    }
}

<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\UrlHelper;

class LogoutTest extends WebTestCase
{
    public function testSuccessfulLgout()
    {
        $client = static::createClient();
        self::bootKernel();
        $container = static::getContainer();
        $urlGenerator = $container->get(UrlHelper::class);

        $client->request('GET', '/logout');
        $this->assertResponseRedirects($urlGenerator->getAbsoluteUrl('/'));
    }
}

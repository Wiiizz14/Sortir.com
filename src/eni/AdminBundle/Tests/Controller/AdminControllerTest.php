<?php

namespace eni\AdminBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerTest extends WebTestCase
{
    public function testCreateuser()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/createUser');
    }

    public function testListuser()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/listUser');
    }

    public function testDetailuser()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/detailUser');
    }

    public function testUpdateuser()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/updateUser');
    }

    public function testDeleteuser()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/deleteUser');
    }

}

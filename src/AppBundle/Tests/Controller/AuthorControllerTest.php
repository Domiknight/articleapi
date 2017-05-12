<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpCache\ResponseCacheStrategy;

class AuthorControllerTest extends WebTestCase
{
    public function testCreate()
    {
        $client = static::createClient();

        $client->request(Request::METHOD_POST, '/api/authors', ['asdf']);

        $this->assertJson(json_encode(['thing']), 'JSON match');
        $this->assertEquals(Response::HTTP_CREATED, $client->getResponse()->getStatusCode(), 'yay success');
        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            ),
            'the "Content-Type" header is "application/json"'
        );

    }

    public function testUpdate(){

        $client = static::createClient();
        $client->request(Request::METHOD_PUT, '/api/authors', ['asdf']);
        $this->assertEquals(Response::HTTP_METHOD_NOT_ALLOWED, $client->getResponse()->getStatusCode(), 'PUT not supported');
    }

}

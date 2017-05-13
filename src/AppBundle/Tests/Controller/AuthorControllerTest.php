<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpCache\ResponseCacheStrategy;

class AuthorControllerTest extends WebTestCase
{
    const authorData = ['name' => 'me'];

    public function testCreate()
    {
        $client = static::createClient();

        $client->request(Request::METHOD_POST, '/api/authors', self::authorData);

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertJson(json_encode(self::authorData), 'JSON match');
        $this->assertEquals(Response::HTTP_CREATED, $client->getResponse()->getStatusCode(), 'yay success');
        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            ),
            'the "Content-Type" header is "application/json"'
        );

        $this->assertTrue($client->getResponse()->headers->has('Location'), 'Location header set');
        $this->assertContains('api/authors/'.$data['id'], $client->getResponse()->headers->__toString(), 'the "Location" header matches the authors GET route');

        $client->request(Request::METHOD_POST, '/api/authors', []);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode(), 'bad request');

        $client->request(Request::METHOD_POST, '/api/authors', ['name' => '']);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode(), 'name must not be blank');

        $client->request(Request::METHOD_POST, '/api/authors', ['name' => 'asldfhjl akdflkasfhlasdjkhfaskjasldfhjl akdflkasfhlasdjkhfaskjasldfhjl akdflkasfhlasdjkhfaskjasldfhjl akdflkasfhlasdjkhfaskjasldfhjl akdflkasfhlasdjkhfaskjasldfhjl akdflkasfhlasdjkhfaskjasldfhjl akdflkasfhlasdjkhfaskjasldfhjl akdflkasfhlasdjkhfaskjasldfhjl akdflkasfhlasdjkhfaskj']);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode(), 'can\'t be longer than 255 chars');
    }

    public function testUpdate(){

        $client = static::createClient();
        $client->request(Request::METHOD_PUT, '/api/authors');
        $this->assertEquals(Response::HTTP_METHOD_NOT_ALLOWED, $client->getResponse()->getStatusCode(), 'PUT not supported');
    }

    public function testDelete(){

        $client = static::createClient();
        $client->request(Request::METHOD_DELETE, '/api/authors');
        $this->assertEquals(Response::HTTP_METHOD_NOT_ALLOWED, $client->getResponse()->getStatusCode(), 'DELETE not supported');
    }

}

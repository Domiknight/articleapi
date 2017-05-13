<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ArticleControllerTest extends WebTestCase
{
    public function testCreate()
    {
        $client = static::createClient();

        $client->request(Request::METHOD_POST, '/api/authors', ['name' => 'me']);
        $author = json_decode($client->getResponse()->getContent(), true);

        $articleData = [
            'title' => 'title',
            'url' => 'url '.microtime(true),
            'author' => $author['id'],
            'content' => 'asdf asdf adsf asdfa sdfasdfasdfasdfasdfasdfds',
        ];

        $client->request(Request::METHOD_POST, '/api/articles', $articleData);

        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertJson(json_encode($articleData), 'JSON match');
        $this->assertArrayHasKey('id', $data, 'id key exists');
        $this->assertArrayHasKey('created_at', $data, 'has createdAt field');
        $this->assertArrayHasKey('updated_at', $data, 'has updatedAt field');
        $this->assertEquals(Response::HTTP_CREATED, $client->getResponse()->getStatusCode(), 'yay success');
        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            ),
            'the "Content-Type" header is "application/json"'
        );

        $this->assertTrue($client->getResponse()->headers->has('Location'), 'Location header set');
        $this->assertContains('api/articles/'.$data['id'], $client->getResponse()->headers->__toString(), 'the "Location" header matches the articles GET route');
    }

    /**
     * @depends testCreate
     *
     */
    public function testShow()
    {
        $client = static::createClient();

        $client->request(Request::METHOD_POST, '/api/authors', ['name' => 'me']);
        $author = json_decode($client->getResponse()->getContent(), true);

        $articleData = [
            'title' => 'title',
            'url' => 'url '.microtime(true),
            'author' => $author['id'],
            'content' => 'asdf asdf adsf asdfa sdfasdfasdfasdfasdfasdfds',
        ];

        $client->request(Request::METHOD_POST, '/api/articles', $articleData);
        $data = json_decode($client->getResponse()->getContent(), true);

        $client->request(Request::METHOD_GET, $client->getResponse()->headers->get('Location'));
        $getData = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode(), 'GET success');
        $this->assertEquals($data, $getData, 'it\'s a match');

        $client->request(Request::METHOD_GET, '/api/articles/asdf-2412');
        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode(), 'GET correctly not found');
    }

    /**
     * @depends testCreate
     * @depends testShow
     */
    public function testUpdate()
    {
        $client = static::createClient();

        $client->request(Request::METHOD_POST, '/api/authors', ['name' => 'me']);
        $author = json_decode($client->getResponse()->getContent(), true);
        $articleData = [
            'title' => 'title',
            'url' => 'url '.microtime(true),
            'author' => $author['id'],
            'content' => 'asdf asdf adsf asdfa sdfasdfasdfasdfasdfasdfds',
        ];

        $client->request(Request::METHOD_POST, '/api/articles', $articleData);
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals($data['updated_at'], $data['created_at'], 'update and create times are the same');

        // reformat for update request
        $updateData = $data;
        $updateData['title'] = 'title updated';
        $updateData['author']= $data['author']['id'];
        unset($updateData['id']);
        unset($updateData['created_at']);
        unset($updateData['updated_at']);

        sleep(1);

        $client->request(Request::METHOD_PUT, '/api/articles/'.$data['id'], $updateData);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $client->getResponse()->getStatusCode(), 'yay success');

        $client->request(Request::METHOD_GET, '/api/articles/'.$data['id']);
        $updatedResponseData = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals('title updated', $updatedResponseData['title'], 'title changed');

        $this->assertNotEquals($updatedResponseData['updated_at'], $updatedResponseData['created_at'], 'update and create times are different');
    }

    /**
     * @depends testCreate
     */
    public function testList() {

        $client = static::createClient();

        $client->request(Request::METHOD_GET, '/api/articles');
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode(), 'list success');
        $currentList = json_decode($client->getResponse()->getContent(), true);

        $client->request(Request::METHOD_POST, '/api/authors', ['name' => 'me']);
        $author = json_decode($client->getResponse()->getContent(), true);

        $articleData = [
            'title' => 'title',
            'url' => 'url '.microtime(true),
            'author' => $author['id'],
            'content' => 'asdf asdf adsf asdfa sdfasdfasdfasdfasdfasdfds',
        ];

        $client->request(Request::METHOD_POST, '/api/articles', $articleData);

        $client->request(Request::METHOD_GET, '/api/articles');
        $updatedList = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(count($currentList)+1, count($updatedList), 'List is returning everything');
    }

    /**
     * @depends testCreate
     * @depends testShow
     */
    public function testDelete() {

        $client = static::createClient();

        $client->request(Request::METHOD_POST, '/api/authors', ['name' => 'me']);
        $author = json_decode($client->getResponse()->getContent(), true);

        $articleData = [
            'title' => 'title',
            'url' => 'url '.microtime(true),
            'author' => $author['id'],
            'content' => 'asdf asdf adsf asdfa sdfasdfasdfasdfasdfasdfds',
        ];

        $client->request(Request::METHOD_POST, '/api/articles', $articleData);

        $data = json_decode($client->getResponse()->getContent(), true);

        $client->request(Request::METHOD_DELETE, '/api/articles/'.$data['id']);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $client->getResponse()->getStatusCode(), 'delete success');

        $client->request(Request::METHOD_GET, '/api/articles/'.$data['id']);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode(), 'article not found');

        // still return a 204 if we delete something that doesn't exist
        $client->request(Request::METHOD_DELETE, '/api/articles/'.$data['id']);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $client->getResponse()->getStatusCode(), 'obfuscated delete success');
    }
}

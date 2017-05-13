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
        $this->assertEquals(Response::HTTP_CREATED, $client->getResponse()->getStatusCode(), 'yay success');

        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertJson(json_encode($articleData), 'JSON match');
        $this->assertArrayHasKey('id', $data, 'id key exists');
        $this->assertArrayHasKey('createdAt', $data, 'has createdAt field');
        $this->assertArrayHasKey('content', $data, 'has content field');
        $this->assertArrayHasKey('url', $data, 'has url field');
        $this->assertArrayHasKey('title', $data, 'has title field');
        $this->assertArrayNotHasKey('summary', $data, 'does not have the summary field');
        $this->assertArrayHasKey('author', $data, 'has author field');
        $this->assertEquals('me', $data['author'], 'returned author is the name');

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

        $this->assertArrayHasKey('id', $data, 'id key exists');
        $this->assertArrayHasKey('createdAt', $data, 'has createdAt field');
        $this->assertArrayHasKey('content', $data, 'has content field');
        $this->assertArrayHasKey('url', $data, 'has url field');
        $this->assertArrayHasKey('title', $data, 'has title field');
        $this->assertArrayNotHasKey('summary', $data, 'does not have the summary field');
        $this->assertArrayHasKey('author', $data, 'has author field');
        $this->assertEquals('me', $data['author'], 'returned author is the name');

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

        // reformat for update request
        $updateData = $data;
        $updateData['title'] = 'title updated';
        $updateData['author']= $author['id'];
        unset($updateData['id']);
        unset($updateData['createdAt']);

        $client->request(Request::METHOD_PUT, '/api/articles/'.$data['id'], $updateData);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $client->getResponse()->getStatusCode(), 'yay success');

        $client->request(Request::METHOD_GET, '/api/articles/'.$data['id']);
        $updatedResponseData = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals('title updated', $updatedResponseData['title'], 'title changed successfully');
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

        $last = array_pop($updatedList);
        $this->assertArrayHasKey('id', $last, 'id key exists');
        $this->assertArrayHasKey('createdAt', $last, 'has createdAt field');
        $this->assertArrayNotHasKey('content', $last, 'does not have the content field');
        $this->assertArrayHasKey('url', $last, 'has url field');
        $this->assertArrayHasKey('title', $last, 'has title field');
        $this->assertArrayHasKey('summary', $last, 'has the summary field');
        $this->assertArrayHasKey('author', $last, 'has author field');
        $this->assertEquals('me', $last['author'], 'returned author is the name');


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

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

        $client->request(Request::METHOD_PUT, '/api/articles/'.$data['id'], $updateData);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $client->getResponse()->getStatusCode(), 'yay success');

        $client->request(Request::METHOD_GET, '/api/articles/'.$data['id']);
        $updatedResponseData = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals('title updated', $updatedResponseData['title'], 'title changed');

        $this->assertNotEquals($updatedResponseData['updated_at'], $updatedResponseData['created_at'], 'update and create times are different');
    }

    /**
     * @depends testCreate
     *
     */
    public function testShow()
    {
        // get and check that details are the same
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @depends testCreate
     */
    public function testList() {
        // get list
        // add one
        // get list again - find exists - list size increases
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @depends testList
     */
    public function testDelete() {
        // can't find resource
        // one less in list
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /*
    public function testCompleteScenario()
    {
        // Create a new client to browse the application
        $client = static::createClient();

        // Create a new entry in the database
        $crawler = $client->request('GET', '/api/articles/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /api/articles/");
        $crawler = $client->click($crawler->selectLink('Create a new entry')->link());

        // Fill in the form and submit it
        $form = $crawler->selectButton('Create')->form(array(
            'appbundle_article[field_name]'  => 'Test',
            // ... other fields to fill
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check data in the show view
        $this->assertGreaterThan(0, $crawler->filter('td:contains("Test")')->count(), 'Missing element td:contains("Test")');

        // Edit the entity
        $crawler = $client->click($crawler->selectLink('Edit')->link());

        $form = $crawler->selectButton('Update')->form(array(
            'appbundle_article[field_name]'  => 'Foo',
            // ... other fields to fill
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check the element contains an attribute with value equals "Foo"
        $this->assertGreaterThan(0, $crawler->filter('[value="Foo"]')->count(), 'Missing element [value="Foo"]');

        // Delete the entity
        $client->submit($crawler->selectButton('Delete')->form());
        $crawler = $client->followRedirect();

        // Check the entity has been delete on the list
        $this->assertNotRegExp('/Foo/', $client->getResponse()->getContent());
    }

    */
}

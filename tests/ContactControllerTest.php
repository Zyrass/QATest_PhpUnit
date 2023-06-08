<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ContactControllerTest extends WebTestCase
{

    private $testData;

    protected function setUp(): void
    {
        parent::setUp();
        $jsonData = file_get_contents(__DIR__ . '/DonneesBrut/donneeBrut.json');
        $this->testData = json_decode($jsonData, true);
    }

    public function testStatus200()
    {
        $client = static::createClient();
        $attendu = $client->request('GET', '/contact');
        $this->assertEquals(
            $this->testData['status200']['expectedStatusCode'],
            $client->getResponse()->getStatusCode(),
            "❌ - Le code d'erreur doit être 200"
        );
    }

    public function testTagForm()
    {
        $client = WebTestCase::createClient();
        $attendu = $client->request('GET', '/contact');
        $this->assertCount(1, $attendu->filter('form'), "❌ - Doit disposer d'un tag form");
    }

    public function testFormInputName()
    {
        $client = WebTestCase::createClient();
        $attendu = $client->request('GET', '/contact');
        $this->assertCount(1, $attendu->filter('input[name="contact[name]"]'), "❌ - Doit disposer d'un tag input avec un attribut name avec pour contenu name");
    }

    public function testFormInputEmail()
    {
        $client = WebTestCase::createClient();
        $attendu = $client->request('GET', '/contact');
        $this->assertCount(1, $attendu->filter('input[name="contact[email]"]'), "❌ - Doit disposer d'un tag input avec un attribut name avec pour contenu email");
    }

    public function testFormInputPassword()
    {
        $client = WebTestCase::createClient();
        $attendu = $client->request('GET', '/contact');
        $this->assertCount(1, $attendu->filter('input[name="contact[password]"]'), "❌ - Doit disposer d'un tag input avec un attribut name avec pour contenu password");
    }

    public function testFormButtonSubmit()
    {
        $client = WebTestCase::createClient();
        $attendu = $client->request('GET', '/contact');
        $this->assertCount(1, $attendu->filter('button[type="submit"]'), "❌ - Doit disposer d'un button de type submit");
    }
}

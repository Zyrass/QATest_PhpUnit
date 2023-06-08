<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ContactControllerTest extends WebTestCase
{
    private $testData;

    protected function setUp(): void
    {
        parent::setUp();
        $jsonData = file_get_contents(__DIR__ . '/JeuxDeDonnees/jeuDonneesPageContact.json');
        $this->testData = json_decode($jsonData, true);
    }

    /**
     * REG-CONTACT-01
     * Ce test permet de tester que la page /contact existe bien avec un status 200
     * URL de connexion: /contact
     * 
     * @return void OK si requête HTTP retourne le status 200
     */
    public function testExistencePageContactOK()
    {
        $client = static::createClient();
        $attendu = $client->request('GET', '/contact');
        $this->assertEquals(
            $this->testData['testExistencePageContactOK']['expectedStatusCode'],
            $client->getResponse()->getStatusCode(),
            "❌ - La requête sur /contact doit retourner un status 200"
        );
    }

    /**
     * REG-CONTACT-02
     * Ce test permet de tester que la page /contacts n'existe pas
     * URL de connexion: /contacts
     * 
     * @return void OK si requête HTTP retourne le status 404
     */
    public function testExistencePageContactsKO()
    {
        $client = static::createClient();
        $attendu = $client->request('GET', '/contacts');
        $this->assertEquals(
            $this->testData['testExistencePageContactsKO']['expectedStatusCode'],
            $client->getResponse()->getStatusCode(),
            "❌ - La requête sur /contacts doit retourner un status 404"
        );
    }

    /**
     * REG-CONTACT-03
     * Ce test permet de tester l'existence dun tag "form" dans la page /contact
     * URL de connexion: /contact
     * 
     * @return void OK si dans la page /contact il y a bien un tag form
     */
    public function testExistenceOneTagFormOK()
    {
        $client = WebTestCase::createClient();
        $attendu = $client->request('GET', '/contact');
        $this->assertCount(
            $this->testData['testExistenceOneTagFormOK']['expectedTagCount'],
            $attendu->filter('form'),
            "❌ - La page contact doit impérativement avoir un tag form"
        );
    }

    /**
     * REG-CONTACT-04
     * Ce test permet de tester la non existence dun tag "form" dans la page /contact
     * URL de connexion: /contact
     * 
     * @return void OK si dans la page /contact il n'y a pas de tag form
     */
    public function testExistenceOneTagFormKO()
    {
        $client = static::createClient();
        $attendu = $client->request('GET', '/contact');
        $this->assertCount(
            $this->testData['testExistenceOneTagFormKO']['expectedTagCount'],
            $attendu->filter($this->testData['testExistenceOneTagFormKO']['tagSelector']),
            "❌ - La page contact ne doit pas avoir de tag form"
        );
    }


    /**
     * REG-CONTACT-05
     * Ce test permet de tester l'existence obligatoirement de quatre tags "input" dans la page /contact
     * URL de connexion: /contact
     * 
     * @return void OK si dans la page /contact il y a bien quatre tags input
     */
    public function testExistenceDeQuatreInputOK()
    {
        $client = static::createClient();
        $attendu = $client->request('GET', '/contact');
        $this->assertCount(
            $this->testData['testExistenceDeQuatreInputOK']['expectedTagCount'],
            $attendu->filter('input'),
            "❌ - Doit retourner obligatoirement quatre (4) tags input dans le formulaire"
        );
    }

    /**
     * REG-CONTACT-06
     * Ce test permet de tester l'existence uniquement de trois tags "input" dans la page /contact
     * URL de connexion: /contact
     * 
     * @return void OK si dans la page /contact il n'y a que trois tags input
     */
    public function testExistenceDeQuatreInputKO()
    {
        $client = static::createClient();
        $attendu = $client->request('GET', '/contact');
        $filteredInputs = $attendu->filter('input')->reduce(function ($node) {
            return $node->attr('type') !== 'hidden';
        });
        $this->assertCount(
            $this->testData['testExistenceDeQuatreInputKO']['expectedTagCount'],
            $filteredInputs,
            "❌ - S'il n'y a que 3 tags input dans le formulaire, il faudra relever une erreur"
        );
    }

    /**
     * REG-CONTACT-07
     * Ce test permet de tester l'existence de l'attribut name de l'input dont le contenu doit être égal à "nom" dans la page /contact
     * URL de connexion: /contact
     * 
     * @return void OK si dans la page /contact il y a bien un attribut name dont le contenu est "nom"
     */
    public function testExistenceDuContenuDansInputNameEgaleNomOK()
    {
        $client = static::createClient();
        $attendu = $client->request('GET', '/contact');
        $filteredInput = $attendu->filter($this->testData['testExistenceDuContenuDansInputNameEgaleNomOK']['tagSelector'])->first();

        if ($filteredInput->count() > 0) {
            $this->assertEquals(
                $this->testData['testExistenceDuContenuDansInputNameEgaleNomOK']['expectedCheckTagContentName'],
                $filteredInput->attr('name'),
                "❌ - Doit disposer d'un tag input avec un attribut name avec pour contenu contact[name]"
            );
        } else {
            $this->fail("❌ - L'élément input avec le sélecteur spécifié n'existe pas");
        }
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

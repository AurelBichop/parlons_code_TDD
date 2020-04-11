<?php

namespace App\Tests\Framework;

use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;

class WebTestCase extends BaseWebTestCase
{

    protected $client;

    protected $em;

    protected $crawler;
    
    protected $response;

    protected $responseContent;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();

        $this->em = $this->getDoctrine()->getManager();

        //Autre technique avec les transactions 
        // $this->em->getConnection()->beginTransaction();
        // $this->em->getConnection()->setAutoCommit(false);


        static $metadata = null;

        if (is_null($metadata)) {
            $metadata = $this->em->getMetadataFactory()->getAllMetadata();
        }

        $schemaTool = new SchemaTool($this->em);
        $schemaTool->dropDatabase();

        if (!empty($metadata)) {
            $schemaTool->createSchema($metadata);
        }
    }


    protected function visit(string $url): self
    {
        $this->crawler = $this->client->request('GET', $url);

        $this->response = $this->client->getResponse();

        $this->responseContent = $this->client->getResponse()->getContent();

        return $this;
    }

    protected function seeStatusCode(int $expectedStatusCode): self
    {
        $this->assertResponseStatusCodeSame($expectedStatusCode);

        return $this;
    }

    protected function assertResponseOk(): self
    {
        return $this->seeStatusCode(200);
    }

    protected function seeText(string $text): self
    {
        $this->assertStringContainsString($text, $this->responseContent);

        return $this;
    }

    protected function dontSeeText(string $text): self
    {
        if (!empty($text)) {
            $this->assertStringNotContainsString($text, $this->responseContent);
        }

        return $this;
    }

    protected function clickLink(string $text): self
    {
        $link = $this->crawler->selectLink($text);

        if ($link->count() === 0) {
            $link = $this->crawler->filter($text);
            if ($link->count() === 0) {
                throw new \InvalidArgumentException("Aucun lien n'a été trouvé avec cette demande \"{$text}\" ");
            }
        }

        return $this->visit($link->link()->getUri());
    }

    protected function seePageIs(string $url): self
    {
        $url = str_replace('/', '\/', $url);

        $this->assertRegExp(sprintf('/%s$/', $url), $this->client->getRequest()->getUri());

        return $this;
    }

    //************* Other methode with path name
    // protected function seePageIs(string $route, array $parameters = []): self
    // {
    //     $this->assertRouteSame($route, $parameters);

    //     return $this;
    // }

    protected function dump(): void
    {
        $content = $this->responseContent;

        $json = json_decode($content);

        if (json_last_error() === JSON_ERROR_NONE) {
            $content = $json;
        }

        dd($content);
    }

    protected function getDoctrine()
    {
        return static::$container->get('doctrine');
    }

    protected function getParameter($name)
    {
        return static::$container->getParameter($name);
    }

    /**
     * This method is called when a test method did not execute successfully.
     *
     * @throws \Throwable
     */
    protected function onNotSuccessfulTest(\Throwable $t): void
    {
        if ($this->crawler && $this->crawler->filter('h1.exception-message')->count() > 0) {
            $trowableClass = get_class($t);

            $expliciteEceptionMessage = $this->crawler->filter('h1.exception-message')->eq(0)->text();

            throw new $trowableClass(
                $t->getMessage() . ' | ' . $this->response->getStatusCode() . ' - ' .$expliciteEceptionMessage
            );
        }

        throw $t;
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // $this->em->getConnection()->rollback();
        $this->em->close();
        $this->em = null;
    }
}

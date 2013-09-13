<?php

namespace Geocoder\Tests\Functional;

class FrontendTest extends WebTestCase
{
    public function testHome()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/');

        $this->assertTrue($client->getResponse()->isOk());
        $this->assertCount(1, $crawler->filter('h1:contains("Geocoder as a Service")'));
    }
}

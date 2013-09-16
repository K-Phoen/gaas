<?php

namespace Geocoder\Tests\Functional;

class ApiTest extends WebTestCase
{
    /**
     * @dataProvider invalidParametersProvider
     */
    public function testInvalidQuery(array $parameters)
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/api/location', $parameters);

        $this->assertSame(400, $client->getResponse()->getStatusCode());
    }

    public function invalidParametersProvider()
    {
        return array(
            array( array() ),
            array( array('foo' => 'bar') ),
            array( array('ip' => '88.122') ),
        );
    }

    /**
     * @dataProvider validParametersProvider
     */
    public function testValidQuery(array $parameters)
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/api/location', $parameters);

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function validParametersProvider()
    {
        return array(
            array( array('ip' => '88.122.1.1') ),
            array( array('address' => '36 Quai des Orfèvres, Paris, France') ),
            array( array('latitude' => 48.8552897, 'longitude' => 2.3433325) ),
        );
    }
}

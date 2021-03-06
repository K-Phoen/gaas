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
        $crawler = $client->request('GET', '/api/locations', $parameters);

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
        $crawler = $client->request('GET', '/api/locations', $parameters);

        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertSame('faker', $client->getResponse()->headers->get('X-Provider'));
    }

    public function validParametersProvider()
    {
        return array(
            array( array('ip' => '88.122.1.1') ),
            array( array('address' => '36 Quai des Orfèvres, Paris, France') ),
            array( array('latitude' => 48.8552897, 'longitude' => 2.3433325) ),
        );
    }

    /**
     * @dataProvider allAcceptHeadersProvider
     */
    public function testValidAcceptHeaders($header, $expectedContentType)
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/api/locations?ip=127.0.0.1',
            $parameters = array(),
            $files = array(),
            $server = array('HTTP_ACCEPT' => $header)
        );

        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertSame($expectedContentType, $client->getResponse()->headers->get('Content-Type'));
        $this->assertSame('faker', $client->getResponse()->headers->get('X-Provider'));
    }

    /**
     * @dataProvider classicAcceptHeadersProvider
     */
    public function testExceptionsAreSerialized($header, $expectedContentType)
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/api/locations',
            $parameters = array(),
            $files = array(),
            $server = array('HTTP_ACCEPT' => $header)
        );

        $this->assertSame(400, $client->getResponse()->getStatusCode());
        $this->assertSame($expectedContentType, $client->getResponse()->headers->get('Content-Type'));
    }

    public function classicAcceptHeadersProvider()
    {
        return array(
            array('application/json',       'application/json'),
            array('text/xml',               'text/xml; charset=UTF-8'),
            array('application/xml',        'text/xml; charset=UTF-8'),
        );
    }

    public function dumpersAcceptHeadersProvider()
    {
        return array(
            array('application/geo+json',                   'application/geo+json'),
            array('application/gpx+xml',                    'application/gpx+xml'),
            array('application/vnd.google-earth.kml+xml',   'application/vnd.google-earth.kml+xml'),
            array('application/vnd.google-earth.kmz',       'application/vnd.google-earth.kml+xml'),
            array('application/octet-stream+wkb',           'application/octet-stream+wkb'),
            array('text/plain+wkt',                         'text/plain+wkt; charset=UTF-8'),
        );
    }

    public function allAcceptHeadersProvider()
    {
        return array_merge($this->classicAcceptHeadersProvider(), $this->dumpersAcceptHeadersProvider());
    }

    /**
     * @dataProvider invalidAcceptHeadersProvider
     */
    public function testInvalidAcceptHeaders($header)
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/api/locations?ip=127.0.0.1',
            $parameters = array(),
            $files = array(),
            $server = array('HTTP_ACCEPT' => $header)
        );

        $this->assertEquals(406, $client->getResponse()->getStatusCode());
    }

    public function invalidAcceptHeadersProvider()
    {
        return array(
            array('text/html'),
            array('application/foo'),
        );
    }

    /**
     * @dataProvider validProvidersProvider
     */
    public function testProviderCanBeSpecified($provider, $expectedProvider)
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/api/locations', array(
            'ip'        => '127.0.0.1',
            'provider'  => $provider
        ));

        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertSame($expectedProvider, $client->getResponse()->headers->get('X-Provider'));
    }

    public function validProvidersProvider()
    {
        return array(
            array('',           'faker'),
            array('faker',      'faker'),
            array('other_faker', 'other_faker'),
        );
    }

    public function testInvalidProviderReturnsAnError()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/api/locations', array(
            'ip'        => '127.0.0.1',
            'provider'  => 'invalid_provider'
        ));

        $this->assertEquals(406, $client->getResponse()->getStatusCode());
    }
}

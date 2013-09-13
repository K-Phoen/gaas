<?php

require_once __DIR__.'/../../vendor/autoload.php';

$app = new Silex\Application();
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));
$app->register(new Silex\Provider\ServiceControllerServiceProvider());
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Geocoder\Provider\GeocoderServiceProvider());
$app->register(new Silex\Provider\SerializerServiceProvider());
$app->register(new KPhoen\Provider\NegotiationServiceProvider(array(
    'geo_json'  => array('application/geo+json'),
    'gpx'       => array('application/gpx+xml'),
    'kml'       => array('application/vnd.google-earth.kml+xml', 'application/vnd.google-earth.kmz'),
    'wkb'       => array('application/octet-stream+wkb'),
    'wkt'       => array('text/plain+wkt'),
)));

// Debug?
$app['debug'] = 'dev' === getenv('APPLICATION_ENV') || (!empty($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] === '10.0.2.2');

if ($app['debug']) {
    $app->register(new Silex\Provider\WebProfilerServiceProvider(), array(
        'profiler.cache_dir'    => __DIR__.'/../cache/profiler',
        'profiler.mount_prefix' => '/_profiler', // this is the default
    ));
}

return $app;

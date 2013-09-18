<?php

require_once __DIR__.'/../../vendor/autoload.php';

$app = new Silex\Application();

// Debug?
$env = getenv('APPLICATION_ENV');
$app['env'] = !empty($env) ? $env : 'dev';
$app['debug'] = 'prod' !== $app['env'] ;

// service providers
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));
$app->register(new Silex\Provider\ServiceControllerServiceProvider());
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\SerializerServiceProvider());
$app->register(new KPhoen\Provider\NegotiationServiceProvider(array(
    'geo_json'  => array('application/geo+json'),
    'gpx'       => array('application/gpx+xml'),
    'kml'       => array('application/vnd.google-earth.kml+xml', 'application/vnd.google-earth.kmz'),
    'wkb'       => array('application/octet-stream+wkb'),
    'wkt'       => array('text/plain+wkt'),
)));

if ($app['debug']) {
    $app->register(new Silex\Provider\WebProfilerServiceProvider(), array(
        'profiler.cache_dir'    => __DIR__.'/../cache/profiler',
        'profiler.mount_prefix' => '/_profiler', // this is the default
    ));
}

$app->register(new Geocoder\Provider\GeocoderServiceProvider());

// load services
$app = require __DIR__.'/services.php';
if (file_exists($servicesFile = sprintf(__DIR__.'/services_%s.php', $app['env']))) {
    $app = require $servicesFile;
}

return $app;

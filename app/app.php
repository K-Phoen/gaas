<?php

$app = require __DIR__ . '/config/config.php';

/*************
 * Services
 ************/

$app['geocoder.dumpers'] = $app->share(function() {
    return array(
        'geo_json'  => new \Geocoder\Dumper\GeoJsonDumper(),
        'gpx'       => new \Geocoder\Dumper\GpxDumper(),
        'kml'       => new \Geocoder\Dumper\KmlDumper(),
        'wkb'       => new \Geocoder\Dumper\WkbDumper(),
        'wkt'       => new \Geocoder\Dumper\WktDumper(),
    );
});

$app['format.listener'] = $app->share(function($app) {
    return new \Geocoder\EventListener\FormatListener($app['format.negotiator'], array_merge(array(
        'json', 'xml'
    ), array_keys($app['geocoder.dumpers'])));
});

$app['response.listener'] = $app->share(function($app) {
    return new \Geocoder\EventListener\ResponseListener($app['serializer'], $app['geocoder.dumpers']);
});

$app['exception.listener'] = $app->share(function($app) {
    return new \Geocoder\EventListener\ExceptionListener($app['serializer']);
});

$app['geocoder'] = $app->share(function($app) {
    $geocoder = new \Geocoder\Geocoder();

    $geocoder->registerProvider(new \Geocoder\Provider\FreeGeoIpProvider($app['geocoder.adapter']));
    $geocoder->registerProvider(new \Geocoder\Provider\HostIpProvider($app['geocoder.adapter']));
    $geocoder->registerProvider(new \Geocoder\Provider\GeoPluginProvider($app['geocoder.adapter']));
    $geocoder->registerProvider(new \Geocoder\Provider\IpGeoBaseProvider($app['geocoder.adapter']));

    $geocoder->registerProvider(new \Geocoder\Provider\OIORestProvider($app['geocoder.adapter']));
    $geocoder->registerProvider(new \Geocoder\Provider\GeocoderCaProvider($app['geocoder.adapter']));
    $geocoder->registerProvider(new \Geocoder\Provider\GeocoderUsProvider($app['geocoder.adapter']));
    $geocoder->registerProvider(new \Geocoder\Provider\GoogleMapsProvider($app['geocoder.adapter']));
    $geocoder->registerProvider(new \Geocoder\Provider\OpenStreetMapsProvider($app['geocoder.adapter']));
    $geocoder->registerProvider(new \Geocoder\Provider\MapQuestProvider($app['geocoder.adapter']));
    $geocoder->registerProvider(new \Geocoder\Provider\ArcGISOnlineProvider($app['geocoder.adapter']));
    $geocoder->registerProvider(new \Geocoder\Provider\DataScienceToolkitProvider($app['geocoder.adapter']));

    $geocoder->registerProvider(new \Geocoder\Provider\CustomChainProvider($geocoder->getProviders()));

    return $geocoder;
});

/*************
 * Routes
 ************/

$app->mount('/', new \Geocoder\Controller\FrontendController());
$app->mount('/api', new \Geocoder\Controller\ApiController());

return $app;

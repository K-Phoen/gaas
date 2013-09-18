<?php

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

$app['provider.subscriber'] = $app->share(function($app) {
    return new \Geocoder\EventListener\ProviderSubscriber($app['geocoder'], $app['geocoder.providers.factory']);
});

/****************************
 * Geocoder related services
 ***************************/

// providers factory
$app['geocoder.providers.factory'] = $app->share(function($app) {
    $factory = new \Geocoder\Factory\ProvidersFactory();

    // authentication-free providers
    $factory->registerFactories(array(
        'free_geo_ip'           => new \Geocoder\Factory\Provider\GenericProviderFactory('\Geocoder\Provider\FreeGeoIpProvider', $app['geocoder.adapter']),
        'host_ip'               => new \Geocoder\Factory\Provider\GenericProviderFactory('\Geocoder\Provider\HostIpProvider', $app['geocoder.adapter']),
        'geo_plugin'            => new \Geocoder\Factory\Provider\GenericProviderFactory('\Geocoder\Provider\GeoPluginProvider', $app['geocoder.adapter']),
        'ip_geo_base'           => new \Geocoder\Factory\Provider\GenericProviderFactory('\Geocoder\Provider\IpGeoBaseProvider', $app['geocoder.adapter']),

        'oio_rest'              => new \Geocoder\Factory\Provider\GenericProviderFactory('\Geocoder\Provider\OIORestProvider', $app['geocoder.adapter']),
        'geocoder_ca'           => new \Geocoder\Factory\Provider\GenericProviderFactory('\Geocoder\Provider\GeocoderCaProvider', $app['geocoder.adapter']),
        'geocoder_us'           => new \Geocoder\Factory\Provider\GenericProviderFactory('\Geocoder\Provider\GeocoderUsProvider', $app['geocoder.adapter']),
        'google_maps'           => new \Geocoder\Factory\Provider\GenericProviderFactory('\Geocoder\Provider\GoogleMapsProvider', $app['geocoder.adapter']),
        'openstreetmaps'        => new \Geocoder\Factory\Provider\GenericProviderFactory('\Geocoder\Provider\OpenStreetMapsProvider', $app['geocoder.adapter']),
        'map_quest'             => new \Geocoder\Factory\Provider\GenericProviderFactory('\Geocoder\Provider\MapQuestProvider', $app['geocoder.adapter']),
        'arcgis_online'         => new \Geocoder\Factory\Provider\GenericProviderFactory('\Geocoder\Provider\ArcGISOnlineProvider', $app['geocoder.adapter']),
        'data_science_toolkit'  => new \Geocoder\Factory\Provider\GenericProviderFactory('\Geocoder\Provider\DataScienceToolkitProvider', $app['geocoder.adapter']),
        'yandex'                => new \Geocoder\Factory\Provider\GenericProviderFactory('\Geocoder\Provider\YandexProvider', $app['geocoder.adapter']),

        'chain'                 => new \Geocoder\Factory\Provider\ChainProviderFactory('\Geocoder\Provider\CustomChainProvider', $factory),
    ));

    // authentication providers
    $factory->registerFactories(array(
        'ip_info_db'    => new \Geocoder\Factory\Provider\ApiKeyProviderFactory('\Geocoder\Provider\IpInfoDbProvider', $app['geocoder.adapter']),
        'bing_maps'     => new \Geocoder\Factory\Provider\ApiKeyProviderFactory('\Geocoder\Provider\BingMapsProvider', $app['geocoder.adapter']),
        'cloudmade'     => new \Geocoder\Factory\Provider\ApiKeyProviderFactory('\Geocoder\Provider\CloudMadeProvider', $app['geocoder.adapter']),
        'ign_openls'    => new \Geocoder\Factory\Provider\ApiKeyProviderFactory('\Geocoder\Provider\IGNOpenLSProvider', $app['geocoder.adapter']),
        'geo_ips'       => new \Geocoder\Factory\Provider\ApiKeyProviderFactory('\Geocoder\Provider\GeoIPsProvider', $app['geocoder.adapter']),
        'baidu'         => new \Geocoder\Factory\Provider\ApiKeyProviderFactory('\Geocoder\Provider\BaiduProvider', $app['geocoder.adapter']),
        'tomtom'        => new \Geocoder\Factory\Provider\ApiKeyProviderFactory('\Geocoder\Provider\TomTomProvider', $app['geocoder.adapter']),
    ));

    return $factory;
});

// known providers

// we override geocoder's definition because we don't want to add providers yet
$app['geocoder'] = $app->share(function($app) {
    return new \Geocoder\Geocoder();

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


return $app;

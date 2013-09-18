<?php

/*************************
 * Test service providers
 *************************/

$app->register(new \KPhoen\Provider\FakerServiceProvider('\Faker\Factory', false));


/****************************
 * Geocoder related services
 ***************************/

// providers factory
$app['geocoder.providers.factory'] = $app->share(function($app) {
    $factory = new \Geocoder\Factory\ProvidersFactory();

    $faker_factory = new \Geocoder\Factory\Provider\FakerProviderFactory('\Geocoder\Tests\Provider\FakerProvider', $app['faker']);

    // authentication-free providers
    $factory->registerFactories(array(
        'faker'         => $faker_factory,
        'other_faker'   => $faker_factory,
        'chain'         => new \Geocoder\Factory\Provider\ChainProviderFactory('\Geocoder\Provider\CustomChainProvider', $factory),
    ));

    return $factory;
});

return $app;

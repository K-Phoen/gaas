<?php

namespace Geocoder\Tests\Functional;

use Silex\WebTestCase as BaseWebTestCase;

abstract class WebTestCase extends BaseWebTestCase
{
    public function createApplication()
    {
        $app = require __DIR__.'/../../../../app/app.php';

        $app['debug'] = true;
        $app['exception_handler']->disable();

        $this->overrideGeocoder($app);

        return $app;
    }

    protected function overrideGeocoder($app)
    {
        $app->register(new \KPhoen\Provider\FakerServiceProvider('\Faker\Factory', false));

        $app['geocoder'] = $app->share(function($app) {
            $geocoder = new \Geocoder\Geocoder();

            $geocoder->registerProvider(new \Geocoder\Tests\Provider\FakerProvider($app['faker']));

            $geocoder->registerProvider(new \Geocoder\Provider\CustomChainProvider($geocoder->getProviders()));

            return $geocoder;
        });
    }
}

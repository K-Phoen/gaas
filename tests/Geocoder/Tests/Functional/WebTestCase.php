<?php

namespace Geocoder\Tests\Functional;

use Silex\WebTestCase as BaseWebTestCase;

abstract class WebTestCase extends BaseWebTestCase
{
    public function createApplication()
    {
        putenv('APPLICATION_ENV=test');

        $app = require __DIR__.'/../../../../app/app.php';

        $app['debug'] = true;
        $app['exception_handler']->disable();
        $app['profiler']->disable();

        return $app;
    }
}

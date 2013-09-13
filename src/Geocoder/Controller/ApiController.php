<?php

namespace Geocoder\Controller;

use Geocoder\Request\Handler\GeocoderRequestHandler;
use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelEvents;

class ApiController implements ControllerProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers
            ->get('/location', array($this, 'getLocation'))
            ->bind('api_location')
            ->before(array($app['format.listener'], 'onRequest'));

        // will automagically generate a response from the data returned by
        // the controllers
        $app->on(KernelEvents::VIEW, array($app['response.listener'], 'onResponse'));

        return $controllers;
    }

    public function getLocation(Request $request, Application $app)
    {
        $handler = new GeocoderRequestHandler($app['geocoder']);
        if (!$handler->handle($request)) {
            return new Response('Invalid request', 400);
        }

        // and send the result
        return $handler->getResult();
    }
}

<?php

namespace Geocoder\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;

class FrontendController implements ControllerProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers
            ->get('/', array($this, 'indexAction'))
            ->bind('homepage');

        return $controllers;
    }

    public function indexAction(Application $app)
    {
        return $app['twig']->render('index.html.twig');
    }
}

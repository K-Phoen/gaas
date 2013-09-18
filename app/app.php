<?php

$app = require __DIR__ . '/config/config.php';

/*************
 * Routes
 ************/

$app->mount('/', new \Geocoder\Controller\FrontendController());
$app->mount('/api', new \Geocoder\Controller\ApiController());

return $app;

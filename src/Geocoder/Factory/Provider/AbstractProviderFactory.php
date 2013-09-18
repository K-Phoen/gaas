<?php

namespace Geocoder\Factory\Provider;

use Geocoder\Factory\ProviderFactoryInterface;
use Geocoder\HttpAdapter\HttpAdapterInterface;

abstract class AbstractProviderFactory implements ProviderFactoryInterface
{
    protected $class;
    protected $httpAdapter;

    public function __construct($class, HttpAdapterInterface $httpAdapter)
    {
        $this->class = $class;
        $this->httpAdapter = $httpAdapter;
    }
}

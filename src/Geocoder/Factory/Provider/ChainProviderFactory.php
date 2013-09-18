<?php

namespace Geocoder\Factory\Provider;

use Geocoder\Factory\ProvidersFactory;
use Geocoder\Factory\ProviderFactoryInterface;

class ChainProviderFactory implements ProviderFactoryInterface
{
    protected $class;
    protected $factory;

    public function __construct($class, ProvidersFactory $factory)
    {
        $this->class = $class;
        $this->factory = $factory;
    }

    public function newInstance(array $args = array())
    {
        if (!empty($args)) {
            throw new \InvalidArgumentException('No arguments are needed for this provider (got: %s)', var_export($args, true));
        }

        $class = $this->class;
        $chainProvider = new $class();

        foreach ($this->factory->getFactories() as $name => $factory) {
            if ($name === 'chain') {
                continue;
            }

            try {
                $chainProvider->addProvider($factory->newInstance());
            } catch (\InvalidArgumentException $e) {
            }
        }

        return $chainProvider;
    }
}

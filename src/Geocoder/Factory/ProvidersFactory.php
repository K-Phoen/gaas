<?php

namespace Geocoder\Factory;

class ProvidersFactory
{
    protected $providersFactories = array();

    public function registerFactory($providerName, ProviderFactoryInterface $factory)
    {
        $this->providersFactories[$providerName] = $factory;
    }

    public function registerFactories(array $factories)
    {
        foreach ($factories as $providerName => $factory) {
            $this->registerFactory($providerName, $factory);
        }
    }

    public function getFactories()
    {
        return $this->providersFactories;
    }

    public function getProvider($name, array $providerArgs = array())
    {
        if (empty($this->providersFactories[$name])) {
            throw new \InvalidArgumentException(sprintf('Provider "%s" does not exist', $name));
        }

        $factory = $this->providersFactories[$name];
        return $factory->newInstance($providerArgs);
    }
}

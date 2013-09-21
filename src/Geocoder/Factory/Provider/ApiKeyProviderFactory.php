<?php

namespace Geocoder\Factory\Provider;

class ApiKeyProviderFactory extends AbstractProviderFactory
{
    public function newInstance(array $args = array())
    {
        if (empty($args['apiKey'])) {
            throw new \InvalidArgumentException(sprintf('The apiKey argument is missing. Got: %s', var_export($args, true)));
        }

        $class = $this->class;

        return new $class($this->httpAdapter, $args['apiKey']);
    }
}

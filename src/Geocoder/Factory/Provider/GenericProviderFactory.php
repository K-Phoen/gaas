<?php

namespace Geocoder\Factory\Provider;

class GenericProviderFactory extends AbstractProviderFactory
{
    public function newInstance(array $args = array())
    {
        if (!empty($args)) {
            throw new \InvalidArgumentException(sprintf('No arguments are needed for this provider (got: %s)', var_export($args, true)));
        }

        $class = $this->class;
        return new $class($this->httpAdapter);
    }
}

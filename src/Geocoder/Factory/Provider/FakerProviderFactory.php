<?php

namespace Geocoder\Factory\Provider;

use Faker\Generator as Faker;
use Geocoder\Factory\ProviderFactoryInterface;

class FakerProviderFactory implements ProviderFactoryInterface
{
    protected $class;
    protected $faker;

    public function __construct($class, Faker $faker)
    {
        $this->class = $class;
        $this->faker = $faker;
    }

    public function newInstance(array $args = array())
    {
        if (!empty($args)) {
            throw new \InvalidArgumentException(sprintf('No arguments are needed for this provider (got: %s)', var_export($args, true)));
        }

        $class = $this->class;
        return new $class($this->faker);
    }
}

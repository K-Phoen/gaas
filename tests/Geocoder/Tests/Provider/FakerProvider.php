<?php

namespace Geocoder\Tests\Provider;

use Geocoder\Provider\AbstractProvider;
use Geocoder\Provider\ProviderInterface;

class FakerProvider extends AbstractProvider implements ProviderInterface
{
    protected $faker;

    public function __construct($faker)
    {
        $this->faker = $faker;
    }

    public function getGeocodedData($address)
    {
        return array_merge($this->getDefaults(), array(
            'latitude'    => $this->faker->latitude,
            'longitude'   => $this->faker->longitude,
            'city'        => $this->faker->city,
            'zipcode'     => $this->faker->postcode,
            'country'     => $this->faker->country,
            'timezone'    => $this->faker->timezone,
        ));
    }

    public function getReversedData(array $coordinates)
    {
        return array_merge($this->getDefaults(), array(
            'latitude'    => $coordinates[0],
            'longitude'   => $coordinates[1],
            'city'        => $this->faker->city,
            'zipcode'     => $this->faker->postcode,
            'country'     => $this->faker->country,
            'timezone'    => $this->faker->timezone,
        ));
    }

    public function getName()
    {
        return 'faker';
    }

    public function setMaxResults($limit)
    {
    }
}

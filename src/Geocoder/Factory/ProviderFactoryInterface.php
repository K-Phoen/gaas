<?php

namespace Geocoder\Factory;

interface ProviderFactoryInterface
{
    public function newInstance(array $args = array());
}

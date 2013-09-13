<?php

namespace Geocoder\Request\Handler;

use Geocoder\GeocoderInterface;
use Symfony\Component\HttpFoundation\Request;

class GeocoderRequestHandler
{
    protected $geocoder;
    protected $result;
    protected $provider;

    public function __construct(GeocoderInterface $geocoder)
    {
        $this->geocoder = $geocoder;
    }

    public function handle(Request $request)
    {
        // choose the provider
        $this->provider = $request->query->get('provider', 'chain');
        $this->geocoder->using($this->provider);

        // limit the results
        if (($limit = $request->query->get('limit')) && (int) $limit > 0) {
            $this->geocoder->limit((int) $limit);
        }

        // inspect the request to decide what to geocode/reverse
        if (($input = $request->query->get('address')) && !empty($input)) {
            $this->result = $this->geocoder->geocode($input);
        } elseif (($input = $request->query->get('ip')) && !empty($input) && filter_var($input, FILTER_VALIDATE_IP) !== false) {
            $this->result = $this->geocoder->geocode($input);
        } elseif (($latitude = $request->query->get('latitude')) && !empty($latitude) && ($longitude = $request->query->get('longitude')) && !empty($longitude)) {
            $this->result = $this->geocoder->reverse($latitude, $longitude);
        }

        return $this->result !== null;
    }

    public function getProvider()
    {
        return $this->provider;
    }

    public function getResult()
    {
        return $this->result;
    }
}

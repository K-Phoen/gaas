<?php

namespace Geocoder\Request\Handler;

use Geocoder\GeocoderInterface;
use Symfony\Component\HttpFoundation\Request;

class GeocoderRequestHandler
{
    protected $geocoder;

    public function __construct(GeocoderInterface $geocoder)
    {
        $this->geocoder = $geocoder;
    }

    public function handle(Request $request)
    {
        // limit the results
        if (($limit = $request->query->get('limit')) && (int) $limit > 0) {
            $this->geocoder->limit((int) $limit);
        }

        // inspect the request to decide what to geocode/reverse
        if (($input = $request->query->get('address')) && !empty($input)) {
            return $this->geocoder->geocode($input);
        } elseif (($input = $request->query->get('ip')) && !empty($input) && filter_var($input, FILTER_VALIDATE_IP) !== false) {
            return $this->geocoder->geocode($input);
        } elseif (($latitude = $request->query->get('latitude')) && !empty($latitude) && ($longitude = $request->query->get('longitude')) && !empty($longitude)) {
            return $this->geocoder->reverse($latitude, $longitude);
        }

        return null;
    }
}

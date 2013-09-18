<?php

namespace Geocoder\Result;

class ApiResult
{
    protected $geocodedResult;
    protected $extraData = array();

    public function __construct(ResultInterface $geocodedResult, array $extraData = array())
    {
        $this->geocodedResult = $geocodedResult;
        $this->extraData = $extraData;
    }

    public function getGeocodedResult()
    {
        return $this->geocodedResult;
    }

    public function getExtraData()
    {
        return $this->extraData;
    }
}

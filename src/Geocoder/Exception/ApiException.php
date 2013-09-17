<?php

namespace Geocoder\Exception;

class ApiException extends \RuntimeException
{
    protected $data;

    public function __construct($data, $code)
    {
        parent::__construct('', $code);

        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }
}

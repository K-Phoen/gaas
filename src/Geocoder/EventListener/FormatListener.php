<?php

namespace Geocoder\EventListener;

use Negotiation\FormatNegotiator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FormatListener
{
    protected $negotiator, $allowedFormats;

    public function __construct(FormatNegotiator $negotiator, array $allowedFormats)
    {
        $this->negotiator = $negotiator;
        $this->allowedFormats = $allowedFormats;
    }

    public function onRequest(Request $request)
    {
        $acceptData = $request->headers->get('accept');
        $format = $this->negotiator->getBestFormat($acceptData, $this->allowedFormats);

        if ($format === null) {
            return new Response('No acceptable format found', 406);
        }

        $request->setRequestFormat($format);
    }
}

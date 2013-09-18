<?php

namespace Geocoder\EventListener;

use Geocoder\Result\ApiResult;
use Geocoder\Result\ResultInterface as GeocodedResultInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;

class ResponseListener
{
    protected $serializer, $dumpers;

    public function __construct($serializer, array $dumpers)
    {
        $this->serializer = $serializer;
        $this->dumpers = $dumpers;
    }

    public function onResponse(GetResponseForControllerResultEvent $event)
    {
        $result = $event->getControllerResult();

        if (!$result instanceof ApiResult) {
            return;
        }

        $response = $this->format($event->getRequest(), $result);
        $event->setResponse($response);
    }

    protected function format(Request $request, ApiResult $result)
    {
        $format = $request->getRequestFormat();

        if ($this->useGeocoderDumper($format)) {
            $content = $this->formatWithDumper($result->getGeocodedResult(), $format);
        } else {
            $content = $this->formatWithSerializer($result, $format);
        }

        return new Response($content, 200, array(
            'Content-Type' => $request->getMimeType($format)
        ));
    }

    protected function formatWithDumper(GeocodedResultInterface $result, $format)
    {
        return $this->dumpers[$format]->dump($result);
    }

    protected function formatWithSerializer(ApiResult $result, $format)
    {
        return $this->serializer->serialize(array_merge($result->getExtraData(), array(
            'result' => $result->getGeocodedResult()->toArray(),
        )), $format);
    }

    protected function useGeocoderDumper($format)
    {
        return array_key_exists($format, $this->dumpers);
    }
}

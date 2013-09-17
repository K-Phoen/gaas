<?php

namespace Geocoder\EventListener;

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
        $data = $event->getControllerResult();

        if (!is_array($data) || empty($data['result']) || !$data['result'] instanceof GeocodedResultInterface) {
            return;
        }

        $response = $this->format($event->getRequest(), $data);
        $event->setResponse($response);
    }

    protected function format(Request $request, array $data)
    {
        $format = $request->getRequestFormat();

        if ($this->useGeocoderDumper($format)) {
            $content = $this->formatWithDumper($data['result'], $format);
        } else {
            $content = $this->formatWithSerializer($data, $format);
        }

        return new Response($content, 200, array(
            'Content-Type' => $request->getMimeType($format)
        ));
    }

    protected function formatWithDumper(GeocodedResultInterface $result, $format)
    {
        return $this->dumpers[$format]->dump($result);
    }

    protected function formatWithSerializer(array $data, $format)
    {
        return $this->serializer->serialize(array_merge($data, array(
            'result' => $data['result']->toArray(),
        )), $format);
    }

    protected function useGeocoderDumper($format)
    {
        return array_key_exists($format, $this->dumpers);
    }
}

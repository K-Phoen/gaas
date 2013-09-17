<?php

namespace Geocoder\EventListener;

use Geocoder\Exception\ApiException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

class ExceptionListener
{
    protected $serializer;

    public function __construct($serializer)
    {
        $this->serializer = $serializer;
    }

    public function onException(GetResponseForExceptionEvent $event)
    {
        if (!$event->getException() instanceof ApiException) {
            return;
        }

        $response = $this->format($event->getRequest(), $event->getException());
        $event->setResponse($response);
    }

    protected function format(Request $request, ApiException $exception)
    {
        $format = $request->getRequestFormat();
        $content = $this->serializer->serialize($exception->getData(), $format);

        return new Response($content, $exception->getCode(), array(
            'Content-Type' => $request->getMimeType($format)
        ));
    }
}

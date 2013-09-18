<?php

namespace Geocoder\EventListener;

use Geocoder\GeocoderInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class ProviderSubscriber implements EventSubscriberInterface
{
    protected $geocoder;

    public function __construct(GeocoderInterface $geocoder)
    {
        $this->geocoder = $geocoder;
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST   => array('onKernelRequest', 10),
            KernelEvents::RESPONSE  => array('onKernelResponse', 10),
        );
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $provider = $request->query->get('provider', 'chain');

        // @todo validate the provider's name

        $request->attributes->set('_provider', $provider);
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        $provider = $event->getRequest()->attributes->get('_provider');

        // extract the real provider name that was used for this request
        if ($provider === 'chain') {
            $providers = $this->geocoder->getProviders();
            $provider = $providers[$provider]->getLastUsedProvider() ? $providers[$provider]->getLastUsedProvider()->getName() : '';
        }

        $event->getResponse()->headers->set('X-Provider', $provider);
    }
}

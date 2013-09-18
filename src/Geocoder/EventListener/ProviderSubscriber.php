<?php

namespace Geocoder\EventListener;

use Geocoder\GeocoderInterface;
use Geocoder\Factory\ProvidersFactory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class ProviderSubscriber implements EventSubscriberInterface
{
    protected $geocoder;
    protected $providersFactory;
    protected $provider;

    public function __construct(GeocoderInterface $geocoder, ProvidersFactory $providersFactory)
    {
        $this->geocoder = $geocoder;
        $this->providersFactory = $providersFactory;
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
        $providerArgs = $request->query->get('providerArgs', array());
        $this->provider = $request->query->get('provider', '');
        $this->provider = empty($this->provider) ? 'chain' : $this->provider;

        try {
            $providerObject = $this->providersFactory->getProvider($this->provider, $providerArgs);
        } catch (\InvalidArgumentException $e) {
            $event->setResponse(new Response($e->getMessage(), 406));
            return;
        }

        $this->geocoder->registerProvider($providerObject);
        $this->geocoder->using($this->provider);
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        // extract the real provider name that was used for this request
        if ($this->provider === 'chain') {
            $providers = $this->geocoder->getProviders();
            $this->provider = $providers[$this->provider]->getLastUsedProvider() ? $providers[$this->provider]->getLastUsedProvider()->getName() : '';
        }

        $event->getResponse()->headers->set('X-Provider', $this->provider);
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: Mr
 * Date: 19/08/2018
 * Time: 10:48
 */

namespace App\Events;


use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class CacheSubscriber implements EventSubscriberInterface
{
    private $cache;

    private $key = false;

    public function __construct(AdapterInterface $cache)
    {
        $this->cache = $cache;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        /*
         * $controller passed can be either a class or a Closure.
         * This is not usual in Symfony but it may happen.
         * If it is a class, it comes in array format
         *
         * || !in_array("App\CacheControllerInterface", class_implements($controller[0]))
         * 
         */
        //TODO Limit caching by route
        if (!is_array($event->getController())) {
            return;
        }
        $request = $event->getRequest();
        $uri = $request->getUri();
        $this->key = 'markdown_' . md5($uri);
        $cacheItem = $this->cache->getItem($this->key);
        if ($cacheItem->isHit()) {
            $cacheResponse = $cacheItem->get();
            $this->key = false;
            //Create controller and send response
            $event->setController(
                function () use ($cacheResponse) {
                    return new Response($cacheResponse);
                });
        }
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        if (false === $this->key) {
            return;
        }
        $cacheItem = $this->cache->getItem($this->key);
        $response = $event->getResponse();
        if (!$cacheItem->isHit() && $response->isOk()) {
            $cacheItem->set($response->getContent());
            $this->cache->save($cacheItem);
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::CONTROLLER => 'onKernelController',
            KernelEvents::RESPONSE => 'onKernelResponse',
        );
    }
}
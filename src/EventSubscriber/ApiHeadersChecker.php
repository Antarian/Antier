<?php
namespace App\EventSubscriber;

use App\Controller\PublicApiInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class ApiHeadersChecker implements EventSubscriberInterface
{
    private $requiredHeaders;

    public function __construct($requiredHeaders)
    {
        $this->requiredHeaders = $requiredHeaders;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();

        /*
         * $controller passed can be either a class or a Closure.
         * This is not usual in Symfony but it may happen.
         * If it is a class, it comes in array format
         */
        if (!is_array($controller)) {
            return;
        }

        if ($controller[0] instanceof PublicApiInterface) {
            $this->checkRequiredHeaders($event->getRequest()->headers);
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::CONTROLLER => 'onKernelController',
        );
    }

    protected function checkRequiredHeaders(HeaderBag $requestHeaders)
    {
        foreach ($this->requiredHeaders as $headerName => $headerValues) {
            $headerValues = is_array($headerValues) ? $headerValues : [$headerValues];
            foreach ($headerValues as $headerValue) {
                if (!$requestHeaders->contains($headerName, $headerValue)) {
                    throw new AccessDeniedHttpException('Header ' . $headerName . ' with value ' . $headerValue . ' is not present in request.');
                }
            }
        }
    }
}
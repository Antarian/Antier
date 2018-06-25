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
    private $headers;

    public function __construct($headers)
    {
        $this->headers = $headers;
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
            $this->checkHeaders(
                $event->getRequest()->headers,
                strtolower($event->getRequest()->getMethod())
            );
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::CONTROLLER => 'onKernelController',
        );
    }

    /**
     * @param HeaderBag $requestHeaders
     * @param string $requestMethod
     *
     * @throws AccessDeniedHttpException
     */
    protected function checkHeaders(HeaderBag $requestHeaders, string $requestMethod)
    {
        foreach ($this->headers[$requestMethod] as $headerName => $headerValues) {
            $headerValues = is_array($headerValues) ? $headerValues : [$headerValues];
            foreach ($headerValues as $headerValue) {
                if (!$requestHeaders->contains($headerName, $headerValue)) {
                    throw new AccessDeniedHttpException('Header ' . $headerName . ' with value ' . $headerValue . ' is not present in request.');
                }
            }
        }
    }
}
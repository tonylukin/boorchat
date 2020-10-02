<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class BeforeActionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'convertJsonStringToArray',
        ];
    }

    public function convertJsonStringToArray(ControllerEvent $event): void
    {
        $request = $event->getRequest();

        if (!$request->getContent()) {
            return;
        }

        $data = \simplexml_load_string($request->getContent());
        $data = \json_decode(\json_encode($data), true);

        if (\json_last_error() !== JSON_ERROR_NONE) {
            throw new BadRequestHttpException('invalid json body: ' . \json_last_error_msg());
        }

        $request->request->replace(\is_array($data) ? $data : []);
    }
}

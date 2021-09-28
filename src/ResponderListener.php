<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Responder;

use Symfony\Component\HttpKernel\Event\ViewEvent;
use Tuzex\Responder\Responder;
use Tuzex\Responder\Response\ResponseResource;

final class ResponderListener
{
    public function __construct(
        private Responder $responder,
    ) {}

    public function __invoke(ViewEvent $event): void
    {
        $controllerResult = $event->getControllerResult();
        if (! $controllerResult instanceof ResponseResource) {
            return;
        }

        $event->setResponse(
            $this->responder->process($controllerResult)
        );
    }
}

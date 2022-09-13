<?php

declare(strict_types=1);

namespace Codea\Bundle\Responder;

use Codea\Responder\Responder;
use Codea\Responder\Response\Resource;
use Symfony\Component\HttpKernel\Event\ViewEvent;

final class ResponderListener
{
    public function __construct(
        private Responder $responder,
    ) {
    }

    public function __invoke(ViewEvent $event): void
    {
        $controllerResult = $event->getControllerResult();
        if (! $controllerResult instanceof Resource) {
            return;
        }

        $event->setResponse(
            $this->responder->process($controllerResult)
        );
    }
}

<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Responder;

use Symfony\Component\HttpKernel\Event\ViewEvent;
use Tuzex\Responder\Responder;
use Tuzex\Responder\Result;

final class ResponderListener
{
    public function __construct(
        private Responder $responder,
    ) {}

    public function __invoke(ViewEvent $event): void
    {
        $result = $event->getControllerResult();
        if (! $result instanceof Result) {
            return;
        }

        $event->setResponse($this->responder->process($result));
    }
}

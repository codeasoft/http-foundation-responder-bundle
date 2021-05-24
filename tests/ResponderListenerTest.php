<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Responder\Test;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Tuzex\Bundle\Responder\ResponderListener;
use Tuzex\Responder\FlexResponder;
use Tuzex\Responder\Middleware\CreateResponseMiddleware;
use Tuzex\Responder\Responder;
use Tuzex\Responder\Response\ContentResponseFactory;
use Tuzex\Responder\Result\Payload\PlainText;

final class ResponderListenerTest extends TestCase
{
    /**
     * @dataProvider provideEvents
     */
    public function testItCreatesResponseFromControllerResult(ViewEvent $viewEvent, bool $expectResponse): void
    {
        $responderListener = new ResponderListener($this->initResponder());
        $responderListener($viewEvent);

        $this->assertSame($expectResponse, $viewEvent->hasResponse());
    }

    public function provideEvents(): array
    {
        $httpKernel = $this->createMock(HttpKernelInterface::class);
        $request = $this->createMock(Request::class);

        $controllerResults = [
            'result' => [
                'controllerResult' => PlainText::define('Hello World!'),
                'expectResponse' => true,
            ],
            'response' => [
                'controllerResult' => new Response(),
                'expectResponse' => false,
            ],
            'scalar' => [
                'controllerResult' => '',
                'expectResponse' => false,
            ],
        ];

        return array_map(
            fn (array $definition) => [
                'viewEvent' => new ViewEvent($httpKernel, $request, 1, $definition['controllerResult']),
                'expectResponse' => $definition['expectResponse'],
            ],
            $controllerResults
        );
    }

    private function initResponder(): Responder
    {
        $responseFactory = new ContentResponseFactory();
        $responseMiddleware = new CreateResponseMiddleware($responseFactory);

        return new FlexResponder($responseMiddleware);
    }
}

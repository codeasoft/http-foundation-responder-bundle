<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Responder\Test;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Tuzex\Bundle\Responder\ResponderListener;
use Tuzex\Responder\Bridge\HttpFoundation\Response\ResponseFactory;
use Tuzex\Responder\Middleware\TransformResultMiddleware;
use Tuzex\Responder\Middlewares;
use Tuzex\Responder\Responder;
use Tuzex\Responder\Result\Payload\PlainText;
use Tuzex\Responder\Result\Payload\TextTransformer;

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
                'controllerResult' => PlainText::send('Hello World!'),
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
            ], $controllerResults
        );
    }

    private function initResponder(): Responder
    {
        $transformer = new TextTransformer(new ResponseFactory());

        return new Responder(
            new Middlewares(new TransformResultMiddleware($transformer))
        );
    }
}

<?php

declare(strict_types=1);

namespace Codea\Bundle\SmartReply\Test;

use Codea\Bundle\SmartReply\ResponderListener;
use Codea\SmartReply\Middleware\ResponseProducer;
use Codea\SmartReply\MiddlewareResponder;
use Codea\SmartReply\Response\Resource\Text\PlainText;
use Codea\SmartReply\Response\ResponseFactory\TextResponseFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

final class ResponderListenerTest extends TestCase
{
    /**
     * @dataProvider provideEvents
     */
    public function testItCreatesResponseFromControllerResult(ViewEvent $viewEvent, bool $expectResponse): void
    {
        $responder = new MiddlewareResponder(
            new ResponseProducer(
                new TextResponseFactory()
            )
        );

        $responderListener = new ResponderListener($responder);
        $responderListener($viewEvent);

        $this->assertSame($expectResponse, $viewEvent->hasResponse());
    }

    public function provideEvents(): array
    {
        $httpKernel = $this->createMock(HttpKernelInterface::class);
        $request = $this->createMock(Request::class);

        $controllerResults = [
            'result' => [
                'controllerResult' => new PlainText('Hello World!'),
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
}

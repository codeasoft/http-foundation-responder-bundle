<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Responder\Test;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Tuzex\Bundle\Responder\ResponderListener;
use Tuzex\Responder\Middleware\CreateResponseMiddleware;
use Tuzex\Responder\Responder;
use Tuzex\Responder\Response\Factory\TextResponseFactory;
use Tuzex\Responder\Response\Resource\PlainText;

final class ResponderListenerTest extends TestCase
{
    private Responder $responder;

    protected function setUp(): void
    {
        $responseFactory = new TextResponseFactory();
        $responseMiddleware = new CreateResponseMiddleware($responseFactory);

        $this->responder = new Responder($responseMiddleware);

        parent::setUp();
    }

    /**
     * @dataProvider provideEvents
     */
    public function testItCreatesResponseFromControllerResult(ViewEvent $viewEvent, bool $expectResponse): void
    {
        $responderListener = new ResponderListener($this->responder);
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
}

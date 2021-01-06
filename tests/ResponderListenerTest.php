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
    public function testItCreatesResponseFromControllerResult(ViewEvent $viewEvent, ?Response $response): void
    {
        $responderListener = new ResponderListener($this->initResponder());
        $responderListener($viewEvent);

        $this->assertEquals($response, $viewEvent->getResponse());
    }

    public function provideEvents(): array
    {
        $transformer = $this->initTransformer();
        $result = PlainText::send('Hello World!');

        $results = [
            'result' => [
                'result' => $result,
                'response' => $transformer->transform($result),
            ],
            'response' => [
                'result' => new Response(),
                'response' => null,
            ],
            'string' => [
                'result' => '',
                'response' => null,
            ],
        ];

        return array_map(function (array $data): array {
            $event = new ViewEvent(
                $this->createMock(HttpKernelInterface::class),
                $this->createMock(Request::class),
                1,
                $data['result']
            );

            return [
                'viewEvent' => $event,
                'expectedResponse' => $data['response'],
            ];
        }, $results);
    }

    private function initResponder(): Responder
    {
        $middlewares = new Middlewares(
            new TransformResultMiddleware($this->initTransformer())
        );

        return new Responder($middlewares);
    }

    private function initTransformer(): TextTransformer
    {
        return new TextTransformer(new ResponseFactory());
    }
}

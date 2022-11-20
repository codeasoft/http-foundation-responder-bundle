<?php

declare(strict_types=1);

namespace Termyn\Bundle\SmartReply\Test\DependencyInjection\Compiler;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Termyn\Bundle\SmartReply\DependencyInjection\Compiler\RegisterMiddlewareResponderCompilerPas;
use Termyn\Bundle\SmartReply\Test\FakeContainerBuilderFactory;
use Termyn\SmartReply\Middleware\ResponseProducer;
use Termyn\SmartReply\MiddlewareResponder;
use Termyn\SmartReply\Service\FlashMessagePublisher;

final class RegisterMiddlewareResponderCompilerPassTest extends TestCase
{
    private RegisterMiddlewareResponderCompilerPas $compilerPass;

    protected function setUp(): void
    {
        parent::setUp();
        $this->compilerPass = new RegisterMiddlewareResponderCompilerPas();
    }

    public function testItRegistersWithResponseProducer(): void
    {
        $containerBuilder = FakeContainerBuilderFactory::withMiddlewares(
            ResponseProducer::class,
        );

        $this->runCompilerPass($containerBuilder);

        $this->assertTrue(
            $containerBuilder->hasDefinition($this->getResponderId())
        );
    }

    /**
     * @dataProvider provideResponderArguments
     */
    public function testItResponseProducerHasResponseProducerAsFirstArgument(array $argumentIds, int $expectedCount): void
    {
        $containerBuilder = FakeContainerBuilderFactory::withMiddlewares(...$argumentIds);

        $this->runCompilerPass($containerBuilder);

        $responderArguments = $containerBuilder->getDefinition($this->getResponderId())->getArguments();

        $this->assertCount($expectedCount, $responderArguments);
        $this->assertEqualsFirstValue($this->getResponseProducerId(), $responderArguments);
    }

    public function provideResponderArguments(): iterable
    {
        $arguments = [
            'only-required' => [
                $this->getResponseProducerId(),
            ],
            'required-and-optional' => [
                FlashMessagePublisher::class,
                $this->getResponseProducerId(),
            ],
        ];

        foreach ($arguments as $scope => $ids) {
            yield $scope => [
                'argumentIds' => $ids,
                'expectedCount' => count($ids),
            ];
        }
    }

    private function runCompilerPass(ContainerBuilder $container): void
    {
        $this->compilerPass->process($container);
    }

    private function getResponderId(): string
    {
        return MiddlewareResponder::class;
    }

    private function getResponseProducerId(): string
    {
        return ResponseProducer::class;
    }

    private function assertEqualsFirstValue(string $expected, array $arguments): void
    {
        $this->assertSame($expected, (string) current($arguments));
    }
}

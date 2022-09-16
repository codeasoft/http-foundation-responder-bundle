<?php

declare(strict_types=1);

namespace Codea\Bundle\SmartReply\Test\DependencyInjection\Compiler;

use Codea\Bundle\SmartReply\DependencyInjection\Compiler\RegisterMiddlewareResponderCompilerPas;
use Codea\Bundle\SmartReply\Test\FakeContainerBuilderFactory;
use Codea\SmartReply\Middleware\ResponseProducer;
use Codea\SmartReply\MiddlewareResponder;
use Codea\SmartReply\Service\FlashMessagePublisher;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

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

<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Responder\Test\DependencyInjection\Compiler;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tuzex\Bundle\Responder\DependencyInjection\Compiler\RegisterFlexibleResponderCompilerPas;
use Tuzex\Bundle\Responder\Test\FakeContainerBuilderFactory;
use Tuzex\Responder\FlexibleResponder;
use Tuzex\Responder\Middleware\ResponseProducer;
use Tuzex\Responder\Service\FlashMessagePublisher;

final class RegisterFlexibleResponderCompilerPassTest extends TestCase
{
    private RegisterFlexibleResponderCompilerPas $compilerPass;

    protected function setUp(): void
    {
        parent::setUp();
        $this->compilerPass = new RegisterFlexibleResponderCompilerPas();
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
        $this->assertEqualsFirstKey($this->getResponseProducerId(), $responderArguments);
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
        return FlexibleResponder::class;
    }

    private function getResponseProducerId(): string
    {
        return ResponseProducer::class;
    }

    private function assertEqualsFirstKey(string $expected, array $arguments): void
    {
        $this->assertSame($expected, array_key_first($arguments));
    }
}

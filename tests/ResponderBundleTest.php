<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Responder\Test;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tuzex\Bundle\Responder\DependencyInjection\Compilation\RegisterMiddlewaresCompilerPass;
use Tuzex\Bundle\Responder\DependencyInjection\Compilation\RegisterTransformResultMiddlewareCompilerPass;
use Tuzex\Bundle\Responder\DependencyInjection\ResponderExtension;
use Tuzex\Bundle\Responder\ResponderBundle;

final class ResponderBundleTest extends TestCase
{
    private ResponderBundle $responderBundle;

    protected function setUp(): void
    {
        $this->responderBundle = new ResponderBundle();

        parent::setUp();
    }

    /**
     * @dataProvider provideCompilerPasses
     */
    public function testItRegistersCompilerPasses(string $classOfCompilerPass): void
    {
        $this->responderBundle->build($containerBuilder = new ContainerBuilder());

        $this->assertCount(1, array_filter(
            $containerBuilder->getCompilerPassConfig()->getPasses(),
            fn (CompilerPassInterface $compilerPass): bool => $compilerPass instanceof $classOfCompilerPass
        ));
    }

    public function provideCompilerPasses(): array
    {
        $compilerPasses = [
            RegisterMiddlewaresCompilerPass::class,
            RegisterTransformResultMiddlewareCompilerPass::class,
        ];

        return array_map(fn (string $compilerPass): array => [
            'classOfCompilerPass' => $compilerPass,
        ], $compilerPasses);
    }

    public function testItReturnsResponderExtension(): void
    {
        $this->assertInstanceOf(ResponderExtension::class, $this->responderBundle->getContainerExtension());
    }
}

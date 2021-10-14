<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Responder\Test\DependencyInjection\Compiler;

use PHPUnit\Framework\TestCase;
use Tuzex\Bundle\Responder\DependencyInjection\Compiler\RegisterContextResponderCompilerPas;
use Tuzex\Bundle\Responder\Test\FakeContainerBuilderFactory;
use Tuzex\Responder\ContextResponder;
use Tuzex\Responder\Middleware\ResponseProducer;

final class RegisterContextResponderCompilerPassTest extends TestCase
{
    public function testItRegistersWithResponseProducer(): void
    {
        $compilerPass = new RegisterContextResponderCompilerPas();
        $containerBuilder = FakeContainerBuilderFactory::withMiddlewares(
            ResponseProducer::class
        );

        $compilerPass->process($containerBuilder);

        $this->assertTrue($containerBuilder->hasDefinition(ContextResponder::class));
    }
}

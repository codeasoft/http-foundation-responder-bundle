<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Responder\Test\DependencyInjection\Helper;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Reference;
use Tuzex\Bundle\Responder\DependencyInjection\Helper\DefinitionFactory;
use Tuzex\Responder\Middleware\ResponseProducer;
use Tuzex\Responder\Response\ResponseFactory\JsonResponseFactory;
use Tuzex\Responder\Response\ResponseFactory\TextResponseFactory;

final class DefinitionFactoryTest extends TestCase
{
    public function testItCreatesDefinitionWithName(): void
    {
        $class = ResponseProducer::class;
        $definition = DefinitionFactory::create($class);

        $this->assertSame($class, $definition->getClass());
    }

    public function testItCreatesDefinitionWithArguments(): void
    {
        $class = ResponseProducer::class;
        $arguments = [
            TextResponseFactory::class,
            JsonResponseFactory::class,
        ];

        $definition = DefinitionFactory::create($class, $arguments);

        $this->assertCount(count($arguments), $definition->getArguments());
        $this->assertContainsOnlyInstancesOf(Reference::class, $definition->getArguments());
    }
}

<?php

declare(strict_types=1);

namespace Termyn\Bundle\SmartReply\Test\DependencyInjection\Helper;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Reference;
use Termyn\Bundle\SmartReply\DependencyInjection\Helper\DefinitionFactory;
use Termyn\SmartReply\Middleware\ResponseProducer;
use Termyn\SmartReply\Response\ResponseFactory\JsonResponseFactory;
use Termyn\SmartReply\Response\ResponseFactory\TextResponseFactory;

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

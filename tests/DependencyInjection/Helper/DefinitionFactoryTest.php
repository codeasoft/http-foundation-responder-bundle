<?php

declare(strict_types=1);

namespace Codea\Bundle\SmartReply\Test\DependencyInjection\Helper;

use Codea\Bundle\SmartReply\DependencyInjection\Helper\DefinitionFactory;
use Codea\SmartReply\Middleware\ResponseProducer;
use Codea\SmartReply\Response\ResponseFactory\JsonResponseFactory;
use Codea\SmartReply\Response\ResponseFactory\TextResponseFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Reference;

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

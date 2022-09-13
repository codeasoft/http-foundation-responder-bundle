<?php

declare(strict_types=1);

namespace Codea\Bundle\Responder\Test\DependencyInjection\Helper;

use Codea\Bundle\Responder\DependencyInjection\Helper\DefinitionFactory;
use Codea\Responder\Middleware\ResponseProducer;
use Codea\Responder\Response\ResponseFactory\JsonResponseFactory;
use Codea\Responder\Response\ResponseFactory\TextResponseFactory;
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

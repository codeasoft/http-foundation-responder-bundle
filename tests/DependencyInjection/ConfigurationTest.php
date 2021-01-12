<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Responder\Test\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;
use Tuzex\Bundle\Responder\DependencyInjection\Configuration;

final class ConfigurationTest extends TestCase
{
    private Configuration $configuration;
    private string $rootName = 'tuzex';

    protected function setUp(): void
    {
        $this->configuration = new Configuration();

        parent::setUp();
    }

    public function testItContainsRootNodeName(): void
    {
        $this->assertSame($this->rootName, $this->configuration->getConfigTreeBuilder()->buildTree()->getName());
    }

    public function testItContainsValidChildrenNodes(): void
    {
        $processor = new Processor();
        $configs[$this->rootName] = [
            'responder' => [
                'middlewares' => [],
            ],
        ];

        $this->assertSame($configs[$this->rootName], $processor->processConfiguration($this->configuration, $configs));
    }
}

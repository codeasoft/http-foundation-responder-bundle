<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Responder\Test\DependencyInjection\Compiler;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Contracts\Translation\TranslatorInterface;
use Tuzex\Bundle\Responder\DependencyInjection\Compiler\ReconfigureFlashMessagePublisherPass;
use Tuzex\Responder\Bridge\HttpFoundation\TranslatableSessionFlashMessagePublisher;
use Tuzex\Responder\Service\FlashMessagePublisher;

final class ReconfigureFlashMessagePublisherPassTest extends TestCase
{
    private const PUBLISHER_NAME = TranslatableSessionFlashMessagePublisher::class;
    private const PUBLISHER_ALIAS = FlashMessagePublisher::class;

    private ContainerBuilder $containerBuilder;
    private ReconfigureFlashMessagePublisherPass $compilerPass;

    protected function setUp(): void
    {
        $this->containerBuilder = new ContainerBuilder();
        $this->compilerPass = new ReconfigureFlashMessagePublisherPass();

        parent::setUp();
    }

    public function testItReconfigureServiceIfUsingTranslator(): void
    {
        $translatorClass = TranslatorInterface::class;
        $this->containerBuilder->setDefinition($translatorClass, new Definition($translatorClass));

        $this->compilerPass->process($this->containerBuilder);

        $this->assertTrue($this->containerBuilder->has(self::PUBLISHER_NAME));
        $this->assertSame(self::PUBLISHER_NAME, (string) $this->containerBuilder->getAlias(self::PUBLISHER_ALIAS));
    }

    public function testItReconfigureServiceIfNotUsingTranslator(): void
    {
        $this->compilerPass->process($this->containerBuilder);

        $this->assertFalse($this->containerBuilder->has(self::PUBLISHER_NAME));
        $this->assertFalse($this->containerBuilder->hasAlias(self::PUBLISHER_ALIAS));
    }
}

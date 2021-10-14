<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Responder\Test\DependencyInjection\Compiler;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Contracts\Translation\TranslatorInterface;
use Tuzex\Bundle\Responder\DependencyInjection\Compiler\ResetFlashMessagePublisherCompilerPass;
use Tuzex\Bundle\Responder\DependencyInjection\Helper\DefinitionFactory;
use Tuzex\Responder\Bridge\HttpFoundation\TranslatableSessionFlashMessagePublisher;
use Tuzex\Responder\Service\FlashMessagePublisher;

final class ResetFlashMessagePublisherCompilerPassTest extends TestCase
{
    private ContainerBuilder $containerBuilder;
    private ResetFlashMessagePublisherCompilerPass $compilerPass;

    protected function setUp(): void
    {
        $this->containerBuilder = new ContainerBuilder();
        $this->compilerPass = new ResetFlashMessagePublisherCompilerPass();

        parent::setUp();
    }

    public function testItDoesNotRegistersTranslatableServiceIfNotUsingTranslator(): void
    {
        $publisherId = TranslatableSessionFlashMessagePublisher::class;

        $this->compilerPass->process($this->containerBuilder);

        $this->assertFalse($this->containerBuilder->has($publisherId));
    }

    public function testItRegistersTranslatableServiceIfUsingTranslator(): void
    {
        $publisherId = TranslatableSessionFlashMessagePublisher::class;
        $translatorId = TranslatorInterface::class;

        $this->containerBuilder->setDefinition($translatorId, DefinitionFactory::create($translatorId));
        $this->compilerPass->process($this->containerBuilder);

        $this->assertTrue($this->containerBuilder->has($publisherId));
        $this->assertSame($publisherId, (string) $this->containerBuilder->getAlias(FlashMessagePublisher::class));
    }
}

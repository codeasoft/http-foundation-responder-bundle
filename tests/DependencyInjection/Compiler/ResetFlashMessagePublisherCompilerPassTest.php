<?php

declare(strict_types=1);

namespace Codea\Bundle\SmartReply\Test\DependencyInjection\Compiler;

use Codea\Bundle\SmartReply\DependencyInjection\Compiler\ResetFlashMessagePublisherCompilerPass;
use Codea\Bundle\SmartReply\DependencyInjection\Helper\DefinitionFactory;
use Codea\SmartReply\Bridge\HttpFoundation\TranslatableSessionFlashMessagePublisher;
use Codea\SmartReply\Service\FlashMessagePublisher;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Contracts\Translation\TranslatorInterface;

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
        $this->compilerPass->process($this->containerBuilder);

        $this->assertFalse(
            $this->containerBuilder->has($this->getTranslatablePublisherId())
        );
    }

    public function testItRegistersTranslatableServiceIfUsingTranslator(): void
    {
        $publisherId = $this->getTranslatablePublisherId();
        $translatorId = $this->getTranslatorId();

        $this->containerBuilder->setDefinition($translatorId, DefinitionFactory::create($translatorId));
        $this->compilerPass->process($this->containerBuilder);

        $this->assertTrue($this->containerBuilder->has($publisherId));
        $this->assertSame($publisherId, (string) $this->containerBuilder->getAlias($this->getPublisherAlias()));
    }

    private function getTranslatorId(): string
    {
        return TranslatorInterface::class;
    }

    private function getTranslatablePublisherId(): string
    {
        return TranslatableSessionFlashMessagePublisher::class;
    }

    private function getPublisherAlias(): string
    {
        return FlashMessagePublisher::class;
    }
}

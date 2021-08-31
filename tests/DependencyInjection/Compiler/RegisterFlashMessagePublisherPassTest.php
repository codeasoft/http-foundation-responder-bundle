<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Responder\Test\DependencyInjection\Compiler;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Contracts\Translation\TranslatorInterface;
use Tuzex\Bundle\Responder\DependencyInjection\Compiler\RegisterFlashMessagePublisherPass;
use Tuzex\Responder\Bridge\HttpFoundation\SessionFlashMessagePublisher;
use Tuzex\Responder\Bridge\HttpFoundation\TranslatableSessionFlashMessagePublisher;
use Tuzex\Responder\Service\FlashMessagePublisher;

final class RegisterFlashMessagePublisherPassTest extends TestCase
{
    private ContainerBuilder $containerBuilder;
    private RegisterFlashMessagePublisherPass $compilerPass;

    protected function setUp(): void
    {
        $this->containerBuilder = new ContainerBuilder();
        $this->compilerPass = new RegisterFlashMessagePublisherPass();

        parent::setUp();
    }

    public function testItRegisterServiceIfNotUsingTranslator(): void
    {
        $this->registerPublisher();

        $this->assertService(SessionFlashMessagePublisher::class);
    }

    public function testItRegisterTranslatableServiceIfUsingTranslator(): void
    {
        $this->registerTranslator();
        $this->registerPublisher();

        $this->assertService(TranslatableSessionFlashMessagePublisher::class);
    }

    private function registerPublisher(): void
    {
        $this->compilerPass->process($this->containerBuilder);
    }

    private function registerTranslator(): void
    {
        $translatorClass = TranslatorInterface::class;
        $this->containerBuilder->setDefinition($translatorClass, new Definition($translatorClass));
    }

    private function assertService(string $publisherId): void
    {
        $this->assertTrue($this->containerBuilder->has($publisherId));
        $this->assertSame($publisherId, (string) $this->containerBuilder->getAlias(FlashMessagePublisher::class));
    }
}

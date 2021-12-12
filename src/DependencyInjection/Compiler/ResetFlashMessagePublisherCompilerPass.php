<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Responder\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Contracts\Translation\TranslatorInterface;
use Tuzex\Bundle\Responder\DependencyInjection\Helper\DefinitionFactory;
use Tuzex\Responder\Bridge\HttpFoundation\SessionFlashMessagePublisher;
use Tuzex\Responder\Bridge\HttpFoundation\TranslatableSessionFlashMessagePublisher;
use Tuzex\Responder\Service\FlashMessagePublisher;

final class ResetFlashMessagePublisherCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (! $this->existsTranslator($container)) {
            return;
        }

        $publisherId = $this->getTranslatablePublisherId();
        $publisherArguments = [
            $this->getPublisherId(),
            $this->getTranslatorId(),
        ];

        $container->setDefinition($publisherId, DefinitionFactory::create($publisherId, $publisherArguments));
        $container->setAlias($this->getPublisherAlias(), $publisherId);
    }

    private function existsTranslator(ContainerBuilder $container): bool
    {
        return $container->hasDefinition($this->getTranslatorId());
    }

    private function getTranslatorId(): string
    {
        return TranslatorInterface::class;
    }

    private function getPublisherId(): string
    {
        return SessionFlashMessagePublisher::class;
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

<?php

declare(strict_types=1);

namespace Termyn\Bundle\SmartReply\DependencyInjection\Compiler;

use Termyn\Bundle\SmartReply\DependencyInjection\Helper\DefinitionFactory;
use Termyn\SmartReply\Bridge\HttpFoundation\SessionFlashMessagePublisher;
use Termyn\SmartReply\Bridge\HttpFoundation\TranslatableSessionFlashMessagePublisher;
use Termyn\SmartReply\Service\FlashMessagePublisher;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Contracts\Translation\TranslatorInterface;

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

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
    public function process(ContainerBuilder $containerBuilder): void
    {
        $translatorId = TranslatorInterface::class;
        if (! $containerBuilder->hasDefinition($translatorId)) {
            return;
        }

        $publisherId = TranslatableSessionFlashMessagePublisher::class;
        $serviceIds = [
            SessionFlashMessagePublisher::class,
            $translatorId,
        ];

        $publisherAlias = FlashMessagePublisher::class;

        $containerBuilder->setDefinition($publisherId, DefinitionFactory::create($publisherId, $serviceIds));
        $containerBuilder->setAlias($publisherAlias, $publisherId);
    }
}

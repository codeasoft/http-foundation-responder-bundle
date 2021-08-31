<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Responder\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Tuzex\Responder\Bridge\HttpFoundation\SessionFlashMessagePublisher;
use Tuzex\Responder\Bridge\HttpFoundation\TranslatableSessionFlashMessagePublisher;
use Tuzex\Responder\Service\FlashMessagePublisher;

final class RegisterFlashMessagePublisherPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $containerBuilder): void
    {
        $publisherAlias = FlashMessagePublisher::class;
        $publisherId = TranslatableSessionFlashMessagePublisher::class;

        $translatorId = TranslatorInterface::class;
        if (! $containerBuilder->has($translatorId)) {
            $publisherId = SessionFlashMessagePublisher::class;
            $translatorId = null;
        }

        $containerBuilder->setDefinition($publisherId, $this->define($publisherId, $translatorId));
        $containerBuilder->setAlias($publisherAlias, $publisherId);
    }

    private function define(string $publisherId, ?string $translatorId = null): Definition
    {
        $referenceIds = [FlashBagInterface::class];
        if ($translatorId) {
            array_push($referenceIds, $translatorId);
        }

        return new Definition(
            $publisherId,
            array_map(
                fn (string $referenceId): Reference => new Reference($referenceId),
                $referenceIds
            )
        );
    }
}

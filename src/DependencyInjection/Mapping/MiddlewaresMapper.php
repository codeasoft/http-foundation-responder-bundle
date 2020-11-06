<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Responder\DependencyInjection\Mapping;

use RuntimeException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Tuzex\Responder\Middleware;

final class MiddlewaresMapper
{
    /**
     * @return Reference[]
     */
    public function map(ContainerBuilder $containerBuilder): array
    {
        $middlewares = [];

        $middlewaresIds = $this->resolveMiddlewareIds($containerBuilder);
        foreach ($middlewaresIds as $middlewareId) {
            if (!$this->validateMiddlewareId($middlewareId)) {
                throw new RuntimeException(sprintf('Class "%s" does not implement "%s". ', $middlewareId, Middleware::class));
            }

            $middlewares[] = new Reference($middlewareId);
        }

        return $middlewares;
    }

    private function resolveMiddlewareIds(ContainerBuilder $containerBuilder): array
    {
        return $containerBuilder->getExtensionConfig('tuzex')[0]['responder']['middlewares'] ?? [];
    }

    private function validateMiddlewareId(string $middlewareId): bool
    {
        return is_a($middlewareId, Middleware::class, true);
    }
}

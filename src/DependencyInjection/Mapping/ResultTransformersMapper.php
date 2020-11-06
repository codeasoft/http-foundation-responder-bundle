<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Responder\DependencyInjection\Mapping;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class ResultTransformersMapper
{
    /**
     * @return Reference[]
     */
    public function map(ContainerBuilder $containerBuilder): array
    {
        $resultTransformerIds = array_keys(
            $containerBuilder->findTaggedServiceIds('tuzex.responder.result_transformer')
        );

        return array_map(fn (string $resultTransformerId): Reference => new Reference($resultTransformerId), $resultTransformerIds);
    }
}

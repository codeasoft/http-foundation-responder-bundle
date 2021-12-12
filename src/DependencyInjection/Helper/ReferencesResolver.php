<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Responder\DependencyInjection\Helper;

use Symfony\Component\DependencyInjection\Reference;

final class ReferencesResolver
{
    /**
     * @return array<int|string, Reference>
     */
    public static function resolve(string ...$ids): array
    {
        return array_map(fn (string $id): Reference => new Reference($id), $ids);
    }
}

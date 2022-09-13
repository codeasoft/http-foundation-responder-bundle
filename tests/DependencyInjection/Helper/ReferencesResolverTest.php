<?php

declare(strict_types=1);

namespace Codea\Bundle\Responder\Test\DependencyInjection\Helper;

use Codea\Bundle\Responder\DependencyInjection\Helper\ReferencesResolver;
use Codea\Bundle\Responder\ResponderBundle;
use Codea\Bundle\Responder\ResponderListener;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Reference;

final class ReferencesResolverTest extends TestCase
{
    /**
     * @dataProvider provideData
     */
    public function testItMapsIdToReference(int $count, array $ids): void
    {
        $references = ReferencesResolver::resolve(...$ids);

        $this->assertCount($count, $references);
        $this->assertContainsOnlyInstancesOf(Reference::class, $references);
    }

    public function provideData(): iterable
    {
        $data = [
            'one' => [
                ResponderListener::class,
            ],
            'several' => [
                ResponderListener::class,
                ResponderBundle::class,
            ],
        ];

        foreach ($data as $group => $ids) {
            yield $group => [
                'count' => count($ids),
                'ids' => $ids,
            ];
        }
    }
}

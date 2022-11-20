<?php

declare(strict_types=1);

namespace Termyn\Bundle\SmartReply\Test\DependencyInjection\Helper;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Reference;
use Termyn\Bundle\SmartReply\DependencyInjection\Helper\ReferencesResolver;
use Termyn\Bundle\SmartReply\ResponderListener;
use Termyn\Bundle\SmartReply\SmartReplyBundle;

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
                SmartReplyBundle::class,
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

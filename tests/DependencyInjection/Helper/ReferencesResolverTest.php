<?php

declare(strict_types=1);

namespace Codea\Bundle\SmartReply\Test\DependencyInjection\Helper;

use Codea\Bundle\SmartReply\DependencyInjection\Helper\ReferencesResolver;
use Codea\Bundle\SmartReply\ResponderListener;
use Codea\Bundle\SmartReply\SmartReplyBundle;
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

<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Responder\Test\DependencyInjection\Helper;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Reference;
use Tuzex\Bundle\Responder\DependencyInjection\Helper\ReferenceMapper;
use Tuzex\Bundle\Responder\ResponderBundle;
use Tuzex\Bundle\Responder\ResponderListener;

final class ReferenceMapperTest extends TestCase
{
    /**
     * @dataProvider provideData
     */
    public function testItMapsIdToReference(int $count, array $ids): void
    {
        $references = ReferenceMapper::map(...$ids);

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

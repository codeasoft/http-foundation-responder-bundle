<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Responder\Test\DependencyInjection\Mapping;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Reference;
use Tuzex\Bundle\Responder\DependencyInjection\Mapping\ResultTransformersMapper;
use Tuzex\Bundle\Responder\Test\FakeContainerBuilderFactory;
use Tuzex\Responder\Result\Payload\JsonDataTransformer;
use Tuzex\Responder\Result\Payload\TextTransformer;

final class ResultTransformersMapperTest extends TestCase
{
    /**
     * @dataProvider provideResultTransformerIds
     */
    public function testItMapsRegisteredResultTransformers(array $tranfromerIds): void
    {
        $resultTransformersMapper = new ResultTransformersMapper();
        $resultTransformers = $resultTransformersMapper->map(
            FakeContainerBuilderFactory::withResultTransformers($tranfromerIds)
        );

        $this->assertCount(count($tranfromerIds), $resultTransformers);
        $this->assertContainsOnlyInstancesOf(Reference::class, $resultTransformers);
    }

    public function provideResultTransformerIds(): array
    {
        return [
            'anyone' => [
                'transformers' => [],
            ],
            'one' => [
                'transformers' => [
                    TextTransformer::class,
                ],
            ],
            'several' => [
                'transformers' => [
                    TextTransformer::class,
                    JsonDataTransformer::class,
                ],
            ],
        ];
    }
}

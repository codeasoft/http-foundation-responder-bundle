<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Responder\Test\DependencyInjection\Mapping;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use Symfony\Component\DependencyInjection\Reference;
use Tuzex\Bundle\Responder\DependencyInjection\Mapping\MiddlewaresMapper;
use Tuzex\Bundle\Responder\Test\FakeContainerBuilderFactory;
use Tuzex\Responder\Middleware\TransformResultMiddleware;
use Tuzex\Responder\Responder;

final class MiddlewaresMapperTest extends TestCase
{
    private MiddlewaresMapper $middlewareMapper;

    protected function setUp(): void
    {
        $this->middlewareMapper = new MiddlewaresMapper();

        parent::setUp();
    }

    /**
     * @dataProvider provideMiddlewareIds
     */
    public function testItMapsRegisteredMiddlewares(array $middlewareIds): void
    {
        $middlewares = $this->middlewareMapper->map(
            FakeContainerBuilderFactory::withMiddlewares($middlewareIds)
        );

        $this->assertCount(count($middlewareIds), $middlewares);
        $this->assertContainsOnlyInstancesOf(Reference::class, $middlewares);
    }

    public function provideMiddlewareIds(): array
    {
        return [
            'anyone' => [
                'middlewares' => [],
            ],
            'one' => [
                'middlewares' => [
                    TransformResultMiddleware::class,
                ],
            ],
            'several' => [
                'middlewares' => [
                    TransformResultMiddleware::class,
                    TransformResultMiddleware::class,
                ],
            ],
        ];
    }

    public function testItThrowsExceptionIfRegisteredMiddlewareNotImplementedMiddlewareInterface(): void
    {
        $this->expectException(RuntimeException::class);
        $this->middlewareMapper->map(
            FakeContainerBuilderFactory::withMiddlewares([Responder::class])
        );
    }
}

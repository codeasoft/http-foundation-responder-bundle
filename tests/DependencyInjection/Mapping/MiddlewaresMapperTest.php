<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Responder\Test\DependencyInjection\Mapping;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use Symfony\Component\DependencyInjection\Reference;
use Tuzex\Bundle\Responder\DependencyInjection\Mapping\MiddlewaresMapper;
use Tuzex\Bundle\Responder\Test\FakeContainerBuilderFactory;
use Tuzex\Responder\Middleware\ProcessResultMiddleware;
use Tuzex\Responder\Responder;

final class MiddlewaresMapperTest extends TestCase
{
    /**
     * @dataProvider provideMiddlewareIds
     */
    public function testItMapsRegisteredMiddlewares(array $middlewareIds): void
    {
        $middlewaresMapper = new MiddlewaresMapper();
        $middlewares = $middlewaresMapper->map(
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
                    ProcessResultMiddleware::class,
                ],
            ],
            'several' => [
                'middlewares' => [
                    ProcessResultMiddleware::class,
                    ProcessResultMiddleware::class,
                ],
            ],
        ];
    }

    public function testItThrowsExceptionIfRegisteredMiddlewareNotImplementedMiddlewareInterface(): void
    {
        $containerBuilder = FakeContainerBuilderFactory::withMiddlewares([Responder::class]);
        $middlewaresMapper = new MiddlewaresMapper();

        $this->expectException(RuntimeException::class);
        $middlewaresMapper->map($containerBuilder);
    }
}

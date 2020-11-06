<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Responder\Test\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tuzex\Bundle\Responder\DependencyInjection\ResponderExtension;
use Tuzex\Responder\Result\ResultTransformer;

final class ResponderExtensionTest extends TestCase
{
    private ResponderExtension $responderExtension;
    private ContainerBuilder $containerBuilder;

    protected function setUp(): void
    {
        $this->responderExtension = new ResponderExtension();
        $this->containerBuilder = new ContainerBuilder();

        parent::setUp();
    }

    /**
     * @dataProvider provideServiceIds
     */
    public function testItRegisterServices(string $serviceId): void
    {
        $this->responderExtension->load([], $this->containerBuilder);

        $this->assertTrue($this->containerBuilder->hasDefinition($serviceId));
    }

    public function provideServiceIds(): array
    {
        return [
            'Tuzex\Responder\Bridge\HttpFoundation\RequestAccessor' => [
                'id' => 'Tuzex\Responder\Bridge\HttpFoundation\RequestAccessor',
            ],
            'Tuzex\Responder\Bridge\HttpFoundation\Response\BinaryFileResponseFactory' => [
                'id' => 'Tuzex\Responder\Bridge\HttpFoundation\Response\BinaryFileResponseFactory',
            ],
            'Tuzex\Responder\Bridge\HttpFoundation\Response\JsonResponseFactory' => [
                'id' => 'Tuzex\Responder\Bridge\HttpFoundation\Response\JsonResponseFactory',
            ],
            'Tuzex\Responder\Bridge\HttpFoundation\Response\RedirectResponseFactory' => [
                'id' => 'Tuzex\Responder\Bridge\HttpFoundation\Response\RedirectResponseFactory',
            ],
            'Tuzex\Responder\Bridge\HttpFoundation\Response\ResponseFactory' => [
                'id' => 'Tuzex\Responder\Bridge\HttpFoundation\Response\ResponseFactory',
            ],
            'Tuzex\Responder\Bridge\Twig\TwigTemplateRenderer' => [
                'id' => 'Tuzex\Responder\Bridge\Twig\TwigTemplateRenderer',
            ],
            'Tuzex\Responder\Service\ReferrerProvider' => [
                'id' => 'Tuzex\Responder\Service\ReferrerProvider',
            ],
            'Tuzex\Responder\Service\UriProvider' => [
                'id' => 'Tuzex\Responder\Service\UriProvider',
            ],
            'Tuzex\Responder\Result\Payload\FileTransformer' => [
                'id' => 'Tuzex\Responder\Result\Payload\FileTransformer',
            ],
            'Tuzex\Responder\Result\Payload\JsonTransformer' => [
                'id' => 'Tuzex\Responder\Result\Payload\JsonTransformer',
            ],
            'Tuzex\Responder\Result\Payload\TextTransformer' => [
                'id' => 'Tuzex\Responder\Result\Payload\TextTransformer',
            ],
            'Tuzex\Responder\Result\Payload\TwigTransformer' => [
                'id' => 'Tuzex\Responder\Result\Payload\TwigTransformer',
            ],
            'Tuzex\Responder\Result\Redirect\RedirectToRefererTransformer' => [
                'id' => 'Tuzex\Responder\Result\Redirect\RedirectToRefererTransformer',
            ],
            'Tuzex\Responder\Result\Redirect\RedirectToRouteTransformer' => [
                'id' => 'Tuzex\Responder\Result\Redirect\RedirectToRouteTransformer',
            ],
            'Tuzex\Responder\Result\Redirect\RedirectToSameUrlTransformer' => [
                'id' => 'Tuzex\Responder\Result\Redirect\RedirectToSameUrlTransformer',
            ],
            'Tuzex\Responder\Result\Redirect\RedirectToUrlTransformer' => [
                'id' => 'Tuzex\Responder\Result\Redirect\RedirectToUrlTransformer',
            ],
            'Tuzex\Responder\Responder' => [
                'id' => 'Tuzex\Responder\Responder',
            ],
            'Tuzex\Bundle\Responder\ResponderListener' => [
                'id' => 'Tuzex\Bundle\Responder\ResponderListener',
            ],
        ];
    }

    /**
     * @dataProvider provideServiceAliases
     */
    public function testItRegisterAutowiringAliases(string $serviceId, string $serviceAlias): void
    {
        $this->responderExtension->load([], $this->containerBuilder);

        $this->assertTrue($this->containerBuilder->hasAlias($serviceId));
        $this->assertSame($serviceAlias, $this->containerBuilder->getAlias($serviceId)->__toString());
    }

    public function provideServiceAliases(): array
    {
        return [
            'Tuzex\Responder\Service\TemplateRenderer' => [
                'id' => 'Tuzex\Responder\Service\TemplateRenderer',
                'alias' => 'Tuzex\Responder\Bridge\Twig\TwigTemplateRenderer',
            ],
        ];
    }

    /**
     * @dataProvider provideTransformerSettings
     */
    public function testItRegistersAutoconfigurationOfTransformers(string $id, string $tag): void
    {
        $this->responderExtension->prepend($this->containerBuilder);

        $this->assertArrayHasKey($id, $this->containerBuilder->getAutoconfiguredInstanceof());
        $this->assertArrayHasKey($tag, $this->containerBuilder->getAutoconfiguredInstanceof()[$id]->getTags());
    }

    public function provideTransformerSettings(): array
    {
        return [
            'result-transformer' => [
                'id' => ResultTransformer::class,
                'tag' => 'tuzex.responder.result_transformer',
            ],
        ];
    }
}

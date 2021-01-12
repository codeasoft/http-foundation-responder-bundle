<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Responder\Test\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tuzex\Bundle\Responder\DependencyInjection\ResponderExtension;
use Tuzex\Bundle\Responder\ResponderListener;
use Tuzex\Responder\Bridge\HttpFoundation\RequestAccessor;
use Tuzex\Responder\Bridge\HttpFoundation\Response\BinaryFileResponseFactory;
use Tuzex\Responder\Bridge\HttpFoundation\Response\JsonResponseFactory;
use Tuzex\Responder\Bridge\HttpFoundation\Response\RedirectResponseFactory;
use Tuzex\Responder\Bridge\HttpFoundation\Response\ResponseFactory;
use Tuzex\Responder\Bridge\Twig\TwigTemplateRenderer;
use Tuzex\Responder\Responder;
use Tuzex\Responder\Result\Payload\FileTransformer;
use Tuzex\Responder\Result\Payload\JsonDataTransformer;
use Tuzex\Responder\Result\Payload\TextTransformer;
use Tuzex\Responder\Result\Payload\TwigTemplateTransformer;
use Tuzex\Responder\Result\Redirect\RedirectToReferrerTransformer;
use Tuzex\Responder\Result\Redirect\RedirectToRouteTransformer;
use Tuzex\Responder\Result\Redirect\RedirectToSameUrlTransformer;
use Tuzex\Responder\Result\Redirect\RedirectToUrlTransformer;
use Tuzex\Responder\Result\ResultTransformer;
use Tuzex\Responder\Service\ReferrerProvider;
use Tuzex\Responder\Service\TemplateRenderer;
use Tuzex\Responder\Service\UriProvider;

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

    public function testItReturnsParticularAlias(): void
    {
        $this->assertSame('tuzex', $this->responderExtension->getAlias());
    }

    /**
     * @dataProvider provideServiceIds
     */
    public function testItRegistersServices(string $serviceId): void
    {
        $this->responderExtension->load([], $this->containerBuilder);

        $this->assertTrue($this->containerBuilder->hasDefinition($serviceId));
    }

    public function provideServiceIds(): iterable
    {
        $services = [
            RequestAccessor::class,
            BinaryFileResponseFactory::class,
            JsonResponseFactory::class,
            RedirectResponseFactory::class,
            ResponseFactory::class,
            TwigTemplateRenderer::class,
            ReferrerProvider::class,
            UriProvider::class,
            FileTransformer::class,
            JsonDataTransformer::class,
            TextTransformer::class,
            TwigTemplateTransformer::class,
            RedirectToReferrerTransformer::class,
            RedirectToRouteTransformer::class,
            RedirectToSameUrlTransformer::class,
            RedirectToUrlTransformer::class,
            Responder::class,
            ResponderListener::class,
        ];

        foreach ($services as $serviceId) {
            yield $serviceId => [
                'serviceId' => $serviceId,
            ];
        }
    }

    /**
     * @dataProvider provideServiceAliases
     */
    public function testItRegistersAutowiringAliases(string $serviceId, string $serviceAlias): void
    {
        $this->responderExtension->load([], $this->containerBuilder);

        $this->assertTrue($this->containerBuilder->hasAlias($serviceId));
    }

    public function provideServiceAliases(): array
    {
        return [
            TemplateRenderer::class => [
                'serviceId' => TemplateRenderer::class,
                'serviceAlias' => TwigTemplateRenderer::class,
            ],
        ];
    }

    /**
     * @dataProvider provideTransformerSettings
     */
    public function testItRegistersAutoconfigurationOfTransformers(string $serviceId, string $serviceTag): void
    {
        $this->responderExtension->prepend($this->containerBuilder);

        $this->assertArrayHasKey($serviceId, $this->containerBuilder->getAutoconfiguredInstanceof());
        $this->assertArrayHasKey($serviceTag, $this->containerBuilder->getAutoconfiguredInstanceof()[$serviceId]->getTags());
    }

    public function provideTransformerSettings(): array
    {
        return [
            ResultTransformer::class => [
                'serviceId' => ResultTransformer::class,
                'serviceTag' => 'tuzex.responder.result_transformer',
            ],
        ];
    }
}

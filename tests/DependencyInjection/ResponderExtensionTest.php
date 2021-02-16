<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Responder\Test\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tuzex\Bundle\Responder\DependencyInjection\ResponderExtension;
use Tuzex\Bundle\Responder\ResponderListener;
use Tuzex\Responder\Bridge\HttpFoundation\RequestAccessor;
use Tuzex\Responder\Bridge\HttpFoundation\RequestReferrerProvider;
use Tuzex\Responder\Bridge\HttpFoundation\RequestUriProvider;
use Tuzex\Responder\Bridge\HttpFoundation\SessionFlashMessagePublisher;
use Tuzex\Responder\Bridge\Twig\TwigTemplateRenderer;
use Tuzex\Responder\FlexResponder;
use Tuzex\Responder\Http\ReferrerProvider;
use Tuzex\Responder\Http\UriProvider;
use Tuzex\Responder\Middleware;
use Tuzex\Responder\Middleware\PublishFlashMessagesMiddleware;
use Tuzex\Responder\Responder;
use Tuzex\Responder\Response\ContentResponseFactory;
use Tuzex\Responder\Response\FileResponseFactory;
use Tuzex\Responder\Response\JsonResponseFactory;
use Tuzex\Responder\Response\ReferrerRedirectResponseFactory;
use Tuzex\Responder\Response\RouteRedirectResponseFactory;
use Tuzex\Responder\Response\TwigResponseFactory;
use Tuzex\Responder\Response\UriRedirectResponseFactory;
use Tuzex\Responder\Response\UrlRedirectResponseFactory;
use Tuzex\Responder\ResponseFactory;
use Tuzex\Responder\Service\FlashMessagePublisher;
use Tuzex\Responder\Service\TemplateRenderer;

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
    public function testItRegistersServices(string $serviceId): void
    {
        $this->responderExtension->load([], $this->containerBuilder);

        $this->assertTrue($this->containerBuilder->hasDefinition($serviceId));
    }

    public function provideServiceIds(): iterable
    {
        $serviceIds = [
            RequestAccessor::class,
            RequestReferrerProvider::class,
            RequestUriProvider::class,
            SessionFlashMessagePublisher::class,
            TwigTemplateRenderer::class,
            PublishFlashMessagesMiddleware::class,
            ContentResponseFactory::class,
            FileResponseFactory::class,
            JsonResponseFactory::class,
            ReferrerRedirectResponseFactory::class,
            ResponderListener::class,
            RouteRedirectResponseFactory::class,
            TwigResponseFactory::class,
            UrlRedirectResponseFactory::class,
            UriRedirectResponseFactory::class,
        ];

        foreach ($serviceIds as $serviceId) {
            yield $serviceId => [
                'serviceId' => $serviceId,
            ];
        }
    }

    /**
     * @dataProvider provideServiceAliases
     */
    public function testItRegistersAliases(string $serviceAlias, string $serviceId): void
    {
        $this->responderExtension->load([], $this->containerBuilder);

        $this->assertSame($serviceId, (string) $this->containerBuilder->getAlias($serviceAlias));
    }

    public function provideServiceAliases(): iterable
    {
        $serviceAliases = [
            Responder::class => FlexResponder::class,
            FlashMessagePublisher::class => SessionFlashMessagePublisher::class,
            ReferrerProvider::class => RequestReferrerProvider::class,
            TemplateRenderer::class => TwigTemplateRenderer::class,
            UriProvider::class => RequestUriProvider::class,
        ];

        foreach ($serviceAliases as $serviceAlias => $serviceId) {
            yield $serviceAlias => [
                'serviceAlias' => $serviceAlias,
                'serviceId' => $serviceId,
            ];
        }
    }

    /**
     * @dataProvider provideServiceTags
     */
    public function testItRegistersTags(string $serviceId, string $serviceTag): void
    {
        $this->responderExtension->prepend($this->containerBuilder);

        $this->assertArrayHasKey($serviceId, $this->containerBuilder->getAutoconfiguredInstanceof());
        $this->assertArrayHasKey($serviceTag, $this->containerBuilder->getAutoconfiguredInstanceof()[$serviceId]->getTags());
    }

    public function provideServiceTags(): iterable
    {
        $serviceTags = [
            Middleware::class => 'tuzex.responder.middleware',
            ResponseFactory::class => 'tuzex.responder.response_factory',
        ];

        foreach ($serviceTags as $serviceId => $serviceTag) {
            yield $serviceId => [
                'serviceId' => $serviceId,
                'serviceTag' => $serviceTag,
            ];
        }
    }
}

<?php

declare(strict_types=1);

namespace Codea\Bundle\Responder\Test\DependencyInjection;

use Codea\Bundle\Responder\DependencyInjection\ResponderExtension;
use Codea\Bundle\Responder\ResponderListener;
use Codea\Responder\Bridge\HttpFoundation\Request\RequestAccessor;
use Codea\Responder\Bridge\HttpFoundation\Request\RequestFlashBagProvider;
use Codea\Responder\Bridge\HttpFoundation\Request\RequestReferrerProvider;
use Codea\Responder\Bridge\HttpFoundation\Request\RequestUriProvider;
use Codea\Responder\Bridge\HttpFoundation\SessionFlashMessagePublisher;
use Codea\Responder\Bridge\Twig\TwigTemplateRenderer;
use Codea\Responder\Http\ReferrerProvider;
use Codea\Responder\Http\UriProvider;
use Codea\Responder\Middleware;
use Codea\Responder\Middleware\FlashMessageEmitter;
use Codea\Responder\MiddlewareResponder;
use Codea\Responder\Responder;
use Codea\Responder\Response\ResponseFactory;
use Codea\Responder\Response\ResponseFactory\FileResponseFactory;
use Codea\Responder\Response\ResponseFactory\JsonResponseFactory;
use Codea\Responder\Response\ResponseFactory\ReferrerRedirectResponseFactory;
use Codea\Responder\Response\ResponseFactory\RouteRedirectResponseFactory;
use Codea\Responder\Response\ResponseFactory\TextResponseFactory;
use Codea\Responder\Response\ResponseFactory\TwigResponseFactory;
use Codea\Responder\Response\ResponseFactory\UriRedirectResponseFactory;
use Codea\Responder\Response\ResponseFactory\UrlRedirectResponseFactory;
use Codea\Responder\Service\FlashMessagePublisher;
use Codea\Responder\Service\TemplateRenderer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

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
            RequestFlashBagProvider::class,
            RequestReferrerProvider::class,
            RequestUriProvider::class,
            SessionFlashMessagePublisher::class,
            TwigTemplateRenderer::class,
            FlashMessageEmitter::class,
            FileResponseFactory::class,
            JsonResponseFactory::class,
            ReferrerRedirectResponseFactory::class,
            ResponderListener::class,
            RouteRedirectResponseFactory::class,
            TextResponseFactory::class,
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
            FlashMessagePublisher::class => SessionFlashMessagePublisher::class,
            ReferrerProvider::class => RequestReferrerProvider::class,
            Responder::class => MiddlewareResponder::class,
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
            Middleware::class => 'codea.responder.middleware',
            ResponseFactory::class => 'codea.responder.response_factory',
        ];

        foreach ($serviceTags as $serviceId => $serviceTag) {
            yield $serviceId => [
                'serviceId' => $serviceId,
                'serviceTag' => $serviceTag,
            ];
        }
    }
}

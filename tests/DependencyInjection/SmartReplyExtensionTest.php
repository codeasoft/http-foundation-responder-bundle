<?php

declare(strict_types=1);

namespace Termyn\Bundle\SmartReply\Test\DependencyInjection;

use Termyn\SmartReply\Bridge\HttpFoundation\Request\RequestUriProvider;
use Termyn\SmartReply\Bridge\HttpFoundation\SessionFlashMessagePublisher;
use Termyn\SmartReply\Bridge\Twig\TwigTemplateRenderer;
use Termyn\SmartReply\Http\ReferrerProvider;
use Termyn\SmartReply\Http\UriProvider;
use Termyn\SmartReply\Middleware;
use Termyn\SmartReply\Middleware\FlashMessageEmitter;
use Termyn\SmartReply\MiddlewareResponder;
use Termyn\SmartReply\Responder;
use Termyn\SmartReply\Response\ResponseFactory;
use Termyn\SmartReply\Response\ResponseFactory\FileResponseFactory;
use Termyn\SmartReply\Response\ResponseFactory\JsonResponseFactory;
use Termyn\SmartReply\Response\ResponseFactory\ReferrerRedirectResponseFactory;
use Termyn\SmartReply\Response\ResponseFactory\RouteRedirectResponseFactory;
use Termyn\SmartReply\Response\ResponseFactory\TextResponseFactory;
use Termyn\SmartReply\Response\ResponseFactory\TwigResponseFactory;
use Termyn\SmartReply\Response\ResponseFactory\UriRedirectResponseFactory;
use Termyn\SmartReply\Response\ResponseFactory\UrlRedirectResponseFactory;
use Termyn\SmartReply\Service\FlashMessagePublisher;
use Termyn\SmartReply\Service\TemplateRenderer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Termyn\Bundle\SmartReply\DependencyInjection\SmartReplyExtension;
use Termyn\Bundle\SmartReply\ResponderListener;
use Termyn\SmartReply\Bridge\HttpFoundation\Request\RequestAccessor;
use Termyn\SmartReply\Bridge\HttpFoundation\Request\RequestFlashBagProvider;
use Termyn\SmartReply\Bridge\HttpFoundation\Request\RequestReferrerProvider;

final class SmartReplyExtensionTest extends TestCase
{
    private SmartReplyExtension $extension;

    private ContainerBuilder $containerBuilder;

    protected function setUp(): void
    {
        $this->extension = new SmartReplyExtension();
        $this->containerBuilder = new ContainerBuilder();

        parent::setUp();
    }

    /**
     * @dataProvider provideServiceIds
     */
    public function testItRegistersServices(string $serviceId): void
    {
        $this->extension->load([], $this->containerBuilder);

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
        $this->extension->load([], $this->containerBuilder);

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
        $this->extension->prepend($this->containerBuilder);

        $this->assertArrayHasKey($serviceId, $this->containerBuilder->getAutoconfiguredInstanceof());
        $this->assertArrayHasKey($serviceTag, $this->containerBuilder->getAutoconfiguredInstanceof()[$serviceId]->getTags());
    }

    public function provideServiceTags(): iterable
    {
        $serviceTags = [
            Middleware::class => 'termyn.responder.middleware',
            ResponseFactory::class => 'termyn.responder.response_factory',
        ];

        foreach ($serviceTags as $serviceId => $serviceTag) {
            yield $serviceId => [
                'serviceId' => $serviceId,
                'serviceTag' => $serviceTag,
            ];
        }
    }
}

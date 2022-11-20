<?php

declare(strict_types=1);

namespace Termyn\Bundle\SmartReply\Test\DependencyInjection;

use Termyn\Bundle\SmartReply\DependencyInjection\SmartReplyExtension;
use Termyn\Bundle\SmartReply\ResponderListener;
use Termyn\SmartReply\Bridge\HttpFoundation\Request\RequestAccessor;
use Termyn\SmartReply\Bridge\HttpFoundation\Request\RequestFlashBagProvider;
use Termyn\SmartReply\Bridge\HttpFoundation\Request\RequestReferrerProvider;
use Codea\SmartReply\Bridge\HttpFoundation\Request\RequestUriProvider;
use Codea\SmartReply\Bridge\HttpFoundation\SessionFlashMessagePublisher;
use Codea\SmartReply\Bridge\Twig\TwigTemplateRenderer;
use Codea\SmartReply\Http\ReferrerProvider;
use Codea\SmartReply\Http\UriProvider;
use Codea\SmartReply\Middleware;
use Codea\SmartReply\Middleware\FlashMessageEmitter;
use Codea\SmartReply\MiddlewareResponder;
use Codea\SmartReply\Responder;
use Codea\SmartReply\Response\ResponseFactory;
use Codea\SmartReply\Response\ResponseFactory\FileResponseFactory;
use Codea\SmartReply\Response\ResponseFactory\JsonResponseFactory;
use Codea\SmartReply\Response\ResponseFactory\ReferrerRedirectResponseFactory;
use Codea\SmartReply\Response\ResponseFactory\RouteRedirectResponseFactory;
use Codea\SmartReply\Response\ResponseFactory\TextResponseFactory;
use Codea\SmartReply\Response\ResponseFactory\TwigResponseFactory;
use Codea\SmartReply\Response\ResponseFactory\UriRedirectResponseFactory;
use Codea\SmartReply\Response\ResponseFactory\UrlRedirectResponseFactory;
use Codea\SmartReply\Service\FlashMessagePublisher;
use Codea\SmartReply\Service\TemplateRenderer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

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

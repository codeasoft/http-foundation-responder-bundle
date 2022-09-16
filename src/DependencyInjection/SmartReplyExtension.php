<?php

declare(strict_types=1);

namespace Codea\Bundle\SmartReply\DependencyInjection;

use Codea\SmartReply\Middleware;
use Codea\SmartReply\Response\ResponseFactory;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

final class SmartReplyExtension extends Extension implements ExtensionInterface, PrependExtensionInterface
{
    private FileLocator $fileLocator;

    public function __construct()
    {
        $this->fileLocator = new FileLocator(__DIR__ . '/../Resources/config');
    }

    public function prepend(ContainerBuilder $container): void
    {
        $container->registerForAutoconfiguration(Middleware::class)
            ->addTag('codea.responder.middleware');

        $container->registerForAutoconfiguration(ResponseFactory::class)
            ->addTag('codea.responder.response_factory');
    }

    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new XmlFileLoader($container, $this->fileLocator);
        $loader->load('services.xml');
    }
}

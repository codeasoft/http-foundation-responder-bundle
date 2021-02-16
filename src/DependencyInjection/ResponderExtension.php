<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Responder\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Tuzex\Responder\Middleware;
use Tuzex\Responder\ResponseFactory;

final class ResponderExtension extends Extension implements ExtensionInterface, PrependExtensionInterface
{
    private FileLocator $fileLocator;

    public function __construct()
    {
        $this->fileLocator = new FileLocator(__DIR__.'/../Resources/config');
    }

    public function prepend(ContainerBuilder $container): void
    {
        $container->registerForAutoconfiguration(Middleware::class)
            ->addTag('tuzex.responder.middleware');

        $container->registerForAutoconfiguration(ResponseFactory::class)
            ->addTag('tuzex.responder.response_factory');
    }

    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new XmlFileLoader($container, $this->fileLocator);
        $loader->load('services.xml');
    }
}

<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Responder\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Tuzex\Responder\Result\ResultTransformer;

final class ResponderExtension extends Extension implements ExtensionInterface, PrependExtensionInterface
{
    private FileLocator $fileLocator;

    public function __construct()
    {
        $this->fileLocator = new FileLocator(__DIR__.'/../Resources/config');
    }

    public function prepend(ContainerBuilder $containerBuilder): void
    {
        $containerBuilder->registerForAutoconfiguration(ResultTransformer::class)
            ->addTag('tuzex.responder.result_transformer');
    }

    public function load(array $configs, ContainerBuilder $containerBuilder): void
    {
        $loader = new XmlFileLoader($containerBuilder, $this->fileLocator);
        $loader->load('services.xml');
    }
}
}

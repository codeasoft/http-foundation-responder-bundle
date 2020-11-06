<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Responder;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Tuzex\Bundle\Responder\DependencyInjection\Compilation\RegisterMiddlewaresCompilerPass;
use Tuzex\Bundle\Responder\DependencyInjection\Compilation\RegisterProcessResultMiddlewareCompilerPass;
use Tuzex\Bundle\Responder\DependencyInjection\ResponderExtension;

final class ResponderBundle extends Bundle implements BundleInterface
{
    public function build(ContainerBuilder $containerBuilder): void
    {
        parent::build($containerBuilder);

        $containerBuilder->addCompilerPass(new RegisterProcessResultMiddlewareCompilerPass());
        $containerBuilder->addCompilerPass(new RegisterMiddlewaresCompilerPass());
    }

    public function getContainerExtension(): ExtensionInterface
    {
        return new ResponderExtension();
    }
}

<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Responder;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Tuzex\Bundle\Responder\DependencyInjection\Compiler\RegisterFlashMessagePublisherPass;
use Tuzex\Bundle\Responder\DependencyInjection\Compiler\RegisterCreateResponseMiddlewarePass;
use Tuzex\Bundle\Responder\DependencyInjection\Compiler\RegisterResponderPass;

final class ResponderBundle extends Bundle implements BundleInterface
{
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new RegisterFlashMessagePublisherPass());
        $container->addCompilerPass(new RegisterCreateResponseMiddlewarePass());
        $container->addCompilerPass(new RegisterResponderPass());

        parent::build($container);
    }
}

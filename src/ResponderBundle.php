<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Responder;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Tuzex\Bundle\Responder\DependencyInjection\Compiler\ReconfigureFlashMessagePublisherPass;
use Tuzex\Bundle\Responder\DependencyInjection\Compiler\RegisterFlexResponderPass;
use Tuzex\Bundle\Responder\DependencyInjection\Compiler\RegisterResponseMiddlewarePass;

final class ResponderBundle extends Bundle implements BundleInterface
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new ReconfigureFlashMessagePublisherPass());
        $container->addCompilerPass(new RegisterResponseMiddlewarePass());
        $container->addCompilerPass(new RegisterFlexResponderPass());
    }
}

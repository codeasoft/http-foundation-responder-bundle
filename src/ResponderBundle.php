<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Responder;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Tuzex\Bundle\Responder\DependencyInjection\Compiler\RegisterContextResponder;
use Tuzex\Bundle\Responder\DependencyInjection\Compiler\RegisterCreateResponseMiddlewarePass;
use Tuzex\Bundle\Responder\DependencyInjection\Compiler\RegisterFlashMessagePublisherPass;

final class ResponderBundle extends Bundle implements BundleInterface
{
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new RegisterFlashMessagePublisherPass());
        $container->addCompilerPass(new RegisterCreateResponseMiddlewarePass());
        $container->addCompilerPass(new RegisterContextResponder());

        parent::build($container);
    }
}

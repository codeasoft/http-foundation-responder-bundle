<?php

declare(strict_types=1);

namespace Codea\Bundle\Responder;

use Codea\Bundle\Responder\DependencyInjection\Compiler\RegisterMiddlewareResponderCompilerPas;
use Codea\Bundle\Responder\DependencyInjection\Compiler\RegisterResponseProducerCompilerPass;
use Codea\Bundle\Responder\DependencyInjection\Compiler\ResetFlashMessagePublisherCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

final class ResponderBundle extends Bundle implements BundleInterface
{
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new RegisterResponseProducerCompilerPass());
        $container->addCompilerPass(new RegisterMiddlewareResponderCompilerPas());
        $container->addCompilerPass(new ResetFlashMessagePublisherCompilerPass());

        parent::build($container);
    }
}

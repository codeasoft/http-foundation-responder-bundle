<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Responder;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Tuzex\Bundle\Responder\DependencyInjection\Compiler\RegisterFlexibleResponderCompilerPas;
use Tuzex\Bundle\Responder\DependencyInjection\Compiler\RegisterResponseProducerCompilerPass;
use Tuzex\Bundle\Responder\DependencyInjection\Compiler\ResetFlashMessagePublisherCompilerPass;

final class ResponderBundle extends Bundle implements BundleInterface
{
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new RegisterResponseProducerCompilerPass());
        $container->addCompilerPass(new RegisterFlexibleResponderCompilerPas());
        $container->addCompilerPass(new ResetFlashMessagePublisherCompilerPass());

        parent::build($container);
    }
}

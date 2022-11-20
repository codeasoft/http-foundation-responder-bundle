<?php

declare(strict_types=1);

namespace Termyn\Bundle\SmartReply;

use Termyn\Bundle\SmartReply\DependencyInjection\Compiler\RegisterMiddlewareResponderCompilerPas;
use Termyn\Bundle\SmartReply\DependencyInjection\Compiler\RegisterResponseProducerCompilerPass;
use Termyn\Bundle\SmartReply\DependencyInjection\Compiler\ResetFlashMessagePublisherCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

final class SmartReplyBundle extends Bundle implements BundleInterface
{
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new RegisterResponseProducerCompilerPass());
        $container->addCompilerPass(new RegisterMiddlewareResponderCompilerPas());
        $container->addCompilerPass(new ResetFlashMessagePublisherCompilerPass());

        parent::build($container);
    }
}

<?php

namespace whatwedo\MonitoringBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use whatwedo\MonitoringBundle\DependencyInjection\Compiler\CheckPass;

/**
 * Class whatwedoMonitoringBundle
 * @package whatwedo\MonitoringBundle
 */
class whatwedoMonitoringBundle extends Bundle
{

    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new CheckPass());
    }
}

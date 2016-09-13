<?php
/*
* This file is part of the scheduler-bundle package.
*
* (c) Hannes Schulz <hannes.schulz@aboutcoders.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Abc\Bundle\SchedulerBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
class AbcSchedulerExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        if ('custom' !== $config['db_driver'])
        {
            $container->setParameter('abc.scheduler.backend_type_' . $config['db_driver'], true);
        }

        $loader->load('scheduler.xml');
        $loader->load('processors.xml');
        $loader->load('validator.xml');

        $this->remapParametersNamespaces(
            $config, $container, array(
            '' => array(
                'model_manager_name' => 'abc.scheduler.model_manager_name',
                'schedule_class'     => 'abc.scheduler.model.schedule.class'
            )
        ));
    }

    /**
     * @param array            $config
     * @param ContainerBuilder $container
     * @param array            $namespaces
     */
    protected function remapParametersNamespaces(array $config, ContainerBuilder $container, array $namespaces)
    {
        foreach ($namespaces as $ns => $map)
        {
            if ($ns)
            {
                if (!array_key_exists($ns, $config))
                {
                    continue;
                }
                $namespaceConfig = $config[$ns];
            }
            else
            {
                $namespaceConfig = $config;
            }
            if (is_array($map))
            {
                $this->remapParameters($namespaceConfig, $container, $map);
            }
            else
            {
                foreach ($namespaceConfig as $name => $value)
                {
                    $container->setParameter(sprintf($map, $name), $value);
                }
            }
        }

    }


    /**
     * @param array            $config
     * @param ContainerBuilder $container
     * @param array            $map
     */
    protected function remapParameters(array $config, ContainerBuilder $container, array $map)
    {
        foreach ($map as $name => $paramName)
        {
            if (array_key_exists($name, $config))
            {
                $container->setParameter($paramName, $config[$name]);
            }
        }
    }
}
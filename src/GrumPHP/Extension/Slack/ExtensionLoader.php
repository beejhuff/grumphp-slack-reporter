<?php
declare(strict_types = 1);

namespace GrumPHP\Extension\Slack;

use GrumPHP\Extension\ExtensionInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Class ExtensionLoader
 *
 * @package GrumPHP\Extension\Slack
 */
class ExtensionLoader implements ExtensionInterface
{
    /**
     * @param ContainerBuilder $container
     *
     * @throws \Exception
     */
    public function load(ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../../../../resources/config'));
        $loader->load('services.yml');
        $loader->load('listeners.yml');
    }
}

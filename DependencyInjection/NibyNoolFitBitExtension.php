<?php

namespace NibyNool\FitBitBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;

class NibyNoolFitBitExtension extends Extension
{
	public function load(array $configs, ContainerBuilder $container)
	{
		$configuration = new Configuration();
		$config = $this->processConfiguration($configuration, $configs);
		$container->setParameter('niby_nool_fit_bit.key',      $config[0]['key']);
		$container->setParameter('niby_nool_fit_bit.secret',   $config[0]['secret']);
		$container->setParameter('niby_nool_fit_bit.callback', $config[0]['callback']);
		$loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
		$loader->load('services.yml');
	}

	public function getAlias()
	{
		return 'niby_nool_fit_bit';
	}
}

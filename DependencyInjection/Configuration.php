<?php

namespace NibyNool\FitBitBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
	public function getConfigTreeBuilder()
	{
		$treeBuilder = new TreeBuilder();
		$rootNode = $treeBuilder->root('fitbit');

		$children = $rootNode->children();
		$children
			->scalarNode('key')
			->info('The FitBit API key')
			->end();
		$children
			->scalarNode('secret')
			->info('The FitBit API secret')
			->end();
		$children
			->scalarNode('callback')
			->info('The callback URL to pass to FitBit')
			->end();
		$rootNode->end();

		return $treeBuilder;
	}
}

<?php

namespace NibyNool\FitBitBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
	public function getConfigTreeBuilder()
	{
		$treeBuilder = new TreeBuilder();
		$rootNode = $treeBuilder->root('fitbit')
			->children()
				->scalarNode('key')
				->info('The FitBit API key')
			->end()
			->scalarNode('secret')
				->info('The FitBit API secret')
			->end()
			->scalarNode('callback')
				->info('The callback URL to pass to FitBit')
			->end()
			->arrayNode('distance_units')
				->info('Distance units recognised by FitBit')
				->defaultValue(array('Centimeter', 'Foot', 'Inch', 'Kilometer', 'Meter', 'Mile', 'Millimeter', 'Steps', 'Yards'))
			->end()
		->end();

		return $treeBuilder;
	}
}

<?php
/**
 * This file is part of the NasExt extensions of Nette Framework
 *
 * Copyright (c) 2013 Dusan Hudak (http://dusan-hudak.com)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace NasExt\ModularRouter\DI;

use Nette\DI\CompilerExtension;

/**
 * @author Dusan Hudak <admin@dusan-hudak.com>
 */
class ModularRouterExtension extends CompilerExtension
{
	const MODULAR_ROUTER_TAG = 'modularRouter';

	/** @var array */
	public $defaults = array(
		'prefixes' => FALSE,
		'priorities' => array()
	);


	public function beforeCompile()
	{
		$routerFactories = $this->getSortedRouters();

		$router = $this->getContainerBuilder()->getDefinition('router');
		if (count($routerFactories)) {
			$router->addSetup('@NasExt\ModularRouter\Router::create($service, ?)', array($routerFactories));
		}
	}


	/**
	 * @return array
	 */
	protected function getSortedRouters()
	{
		$config = $this->getConfig($this->defaults);
		$builder = $this->getContainerBuilder();

		$routerFactories = array();
		foreach ($builder->findByTag(self::MODULAR_ROUTER_TAG) as $name => $_) {

			$service = $builder->getDefinition($name);
			$serviceClass = $service->class;

			if (in_array('NasExt\ModularRouter\IRouterFactory', class_implements($serviceClass))) {

				$prefix = $serviceClass::getPrefix();
				$module = $serviceClass::getModule();

				if (array_key_exists($module, $config['prefixes'])) {
					$prefix = $config['prefixes'][$module];
					$service->addSetup('setPrefix', array($prefix));
				}

				$priority = $name;
				if (array_key_exists($module, $config['priorities'])) {
					$priority = $config['priorities'][$module];
				}

				$routerFactories[$prefix][$priority] = '@' . $name;
			}
		}

		if (!empty($routerFactories)) {
			krsort($routerFactories);

			foreach ($routerFactories as $items => $values) {
				ksort($values, SORT_STRING);
				$routerFactories[$items] = $values;
			}
		}

		return \Nette\Utils\Arrays::flatten($routerFactories);
	}
}

<?php
/**
 * This file is part of the NasExt extensions of Nette Framework
 *
 * Copyright (c) 2013 Dusan Hudak (http://dusan-hudak.com)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace NasExt\ModularRouter;

use Nette\Application\IRouter;
use Nette\Application\Routers\RouteList;
use Nette\Utils\AssertionException;

/**
 * @author Dusan Hudak <admin@dusan-hudak.com>
 */
class Router
{

	/**
	 * @param IRouter $router
	 * @param IRouterFactory[] $routerFactories
	 * @throws \Nette\Utils\AssertionException
	 */
	public function create(IRouter &$router, array $routerFactories)
	{
		if (!$router instanceof RouteList) {
			throw new AssertionException(
				'If you want to use NasExt\ModularRouter then your main router must be an instance of Nette\Application\Routers\RouteList'
			);
		}

		if (count($routerFactories)) {
			$definedRoutes = array_merge($routerFactories, iterator_to_array($router));
			$router = new RouteList;

			foreach ($definedRoutes as $route) {
				if ($route instanceof IRouter) {
					$router[] = $route;
				} elseif ($route instanceof IRouterFactory) {
					$router[] = $route->create();
				}
			}
		}
	}
}

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

/**
 * @author Dusan Hudak <admin@dusan-hudak.com>
 */
interface IRouterFactory
{

	/**
	 * @return IRouter
	 */
	public function create();


	/**
	 * @return string
	 */
	public static function getModule();


	/**
	 * @param string $prefix
	 */
	public function setPrefix($prefix);


	/**
	 * @return string
	 */
	public static function getPrefix();
}

<?php

namespace Brabijan\SeoComponents\DI;

use Brabijan\SeoComponents\Router\Target;

interface ITargetProvider
{

	/**
	 * @return Target
	 */
	public function getTarget();

}
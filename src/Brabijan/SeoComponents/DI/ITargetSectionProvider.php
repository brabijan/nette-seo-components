<?php

namespace Brabijan\SeoComponents\DI;

use Brabijan\SeoComponents\TargetSection;

interface ITargetSectionProvider
{

	/**
	 * @return TargetSection
	 */
	public function getTargetSection();

}
<?php

namespace Brabijan\SeoComponents;

use Brabijan\SeoComponents\Entity\Target;
use Nette\Object;

class CurrentTarget extends Object
{

	/** @var Target|null */
	private $currentTarget;



	/**
	 * @param Target $target
	 */
	public function setCurrentTarget(Target $target = NULL)
	{
		$this->currentTarget = $target;
	}



	/**
	 * @return Target|null
	 */
	public function getCurrentTarget()
	{
		return $this->currentTarget;
	}

}
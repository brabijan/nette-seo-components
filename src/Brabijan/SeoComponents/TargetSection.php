<?php

namespace Brabijan\SeoComponents;

use Brabijan\SeoComponents\Router\Target;
use Nette\Object;

class TargetSection extends Object
{

	/** @var string */
	private $name;

	/** @var array */
	private $targetList = array();



	public function __construct($name)
	{
		$this->name = $name;
	}



	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}



	/**
	 * @param $name
	 * @param Target $target
	 */
	public function addTarget($name, Target $target)
	{
		$this->targetList[$name] = $target;
	}



	/**
	 * @return Target[] array($name => Target)
	 */
	public function getTargetList()
	{
		return $this->targetList;
	}

}
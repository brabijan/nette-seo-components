<?php

namespace Brabijan\SeoComponents\Dao;

use Kdyby\Doctrine\EntityDao;
use Nette\Object;

class Target extends Object
{

	/** @var \Kdyby\Doctrine\EntityDao */
	private $targetDao;



	public function __construct(EntityDao $targetDao)
	{
		$this->targetDao = $targetDao;
	}

}
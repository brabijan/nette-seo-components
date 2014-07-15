<?php

namespace Brabijan\SeoComponents\Dao;

use Kdyby\Doctrine\EntityDao;
use Nette\Object;

class Meta extends Object
{

	/** @var \Kdyby\Doctrine\EntityDao */
	private $metaDao;



	public function __construct(EntityDao $metaDao)
	{
		$this->metaDao = $metaDao;
	}

}
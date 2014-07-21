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



	/**
	 * @param \Brabijan\SeoComponents\Entity\Meta $meta
	 */
	public function save(\Brabijan\SeoComponents\Entity\Meta $meta)
	{
		$this->metaDao->save($meta);
	}

}
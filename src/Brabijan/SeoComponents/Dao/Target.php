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



	/**
	 * @param string|\Brabijan\SeoComponents\Router\Target $presenter
	 * @param string|null $action
	 * @param string|null $id
	 * @return null|\Brabijan\SeoComponents\Entity\Target
	 */
	public function findTarget($presenter, $action = NULL, $id = NULL)
	{
		if ($presenter instanceof \Brabijan\SeoComponents\Router\Target) {
			$action = $presenter->action;
			$id = $presenter->id;
			$presenter = $presenter->presenter;
		}

		return $this->targetDao->findOneBy(array("targetPresenter" => $presenter, "targetAction" => $action, "targetId" => $id));
	}

}
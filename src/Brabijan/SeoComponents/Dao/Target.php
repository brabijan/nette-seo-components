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
	 * @return \Brabijan\SeoComponents\Entity\Target
	 */
	public function createBlankTarget($presenter, $action = NULL, $id = NULL)
	{
		if ($presenter instanceof \Brabijan\SeoComponents\Router\Target) {
			$action = $presenter->action;
			$id = $presenter->id;
			$presenter = $presenter->presenter;
		}

		$targetEntity = new \Brabijan\SeoComponents\Entity\Target();
		$targetEntity->targetPresenter = $presenter;
		$targetEntity->targetAction = $action;
		$targetEntity->targetId = $id;

		$meta = new \Brabijan\SeoComponents\Entity\Meta();
		$meta->setTarget($targetEntity);
		$targetEntity->setMeta($meta);
		$this->targetDao->save($targetEntity);

		return $targetEntity;
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



	/**
	 * @param \Brabijan\SeoComponents\Entity\Target $target
	 */
	public function save(\Brabijan\SeoComponents\Entity\Target $target)
	{
		$this->targetDao->save($target);
	}

}
<?php

namespace Brabijan\SeoComponents\Dao;

use Kdyby\Doctrine\EntityDao;
use Nette\Object;
use Brabijan;
use Nette;

class Route extends Object
{

	/** @var \Kdyby\Doctrine\EntityDao */
	private $routeDao;



	public function __construct(EntityDao $routeDao)
	{
		$this->routeDao = $routeDao;
	}



	/**
	 * @param Brabijan\SeoComponents\Router\Target $target
	 * @return Brabijan\SeoComponents\Entity\Route|null
	 */
	public function findCurrentRouteByTarget(Brabijan\SeoComponents\Router\Target $target)
	{
		$qb = $this->routeDao->createQueryBuilder("r")->select("r");
		$qb->leftJoin('Brabijan\SeoComponents\Entity\Target', 't', 'WITH', 'r.target = t.id');
		$qb->andWhere('t.targetPresenter = :presenter')->setParameter(':presenter', $target->presenter);
		$qb->andWhere('t.targetAction = :action')->setParameter(':action', $target->action);
		if ($target->id) {
			$qb->andWhere('t.targetId = :id')->setParameter(':id', $target->id);
		}
		$qb->andWhere('r.oneWay = :oneWay')->setParameter(':oneWay', FALSE);
		$qb->setMaxResults(1);

		$result = $qb->getQuery()->execute();

		return !empty($result) ? $result[0] : NULL;
	}



	/**
	 * @param $slug
	 * @param $oneWays
	 * @return Brabijan\SeoComponents\Entity\Route|null
	 */
	public function findRouteBySlug($slug, $oneWays)
	{
		$search = array("slug" => $slug);
		if ($oneWays !== TRUE) {
			$search["oneWay"] = FALSE;
		}

		return $this->routeDao->findOneBy($search);
	}

}
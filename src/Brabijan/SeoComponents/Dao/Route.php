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

	/** @var Nette\Caching\Cache */
	private $cache;



	public function __construct(EntityDao $routeDao, Nette\Caching\IStorage $storage)
	{
		$this->routeDao = $routeDao;
		$this->cache = new Nette\Caching\Cache($storage, "brabijan.router");
	}



	/**
	 * @param Brabijan\SeoComponents\Entity\Target $target
	 * @param $slug
	 * @return Brabijan\SeoComponents\Entity\Route
	 */
	public function addRoute(Brabijan\SeoComponents\Entity\Target $target, $slug)
	{
		$slug = Nette\Utils\Strings::webalize($slug, "./");

		$qb = $this->routeDao->createQueryBuilder("r")->update();
		$qb->set("r.oneWay", ":oneWay")->setParameter(":oneWay", TRUE);
		$qb->where("r.target = :target")->setParameter(":target", $target);
		$qb->getQuery()->execute();

		$route = new Brabijan\SeoComponents\Entity\Route();
		$route->target = $target;
		$route->slug = $slug;
		$route->oneWay = FALSE;
		$this->routeDao->save($route);

		$this->cleanTargetCache(new Brabijan\SeoComponents\Router\Target($target->targetPresenter, $target->targetAction, $target->targetId));

		return $route;
	}



	/**
	 * @return array
	 */
	public function getRouteListIndexedByTarget()
	{
		$routes = array();
		foreach ($this->routeDao->findBy(array(), array("oneWay" => "ASC", "id" => "DESC")) as $route) {
			$routes[$route->target->id][$route->id] = $route;
		}

		return $routes;
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
		if($target->id) {
			$qb->andWhere('t.targetId = :id')->setParameter(':id', $target->id);
		} else {
			$qb->andWhere('t.targetId IS NULL');
		}
		$qb->andWhere('r.oneWay = :oneWay')->setParameter(':oneWay', FALSE);
		$qb->setMaxResults(1);

		$result = $qb->getQuery()->execute();
		$result = !empty($result) ? $result[0] : NULL;

		if (!$result) {
			$qb->getParameter("oneWay")->setValue(TRUE);
			$qb->orderBy("r.id", "DESC");
			$resultWithOneWays = $qb->getQuery()->execute();
			$result = !empty($resultWithOneWays) ? $resultWithOneWays[0] : NULL;
		}

		return $result;
	}



	/**
	 * @param Brabijan\SeoComponents\Router\Target $target
	 * @return string|NULL
	 */
	public function findCurrentSlugByTarget(Brabijan\SeoComponents\Router\Target $target)
	{
		$serializedTarget = serialize($target);
		$cachedSlug = $this->cache->load($serializedTarget);

		if ($cachedSlug === FALSE) {
			return NULL;
		}
		if ($cachedSlug !== NULL) {
			return $cachedSlug;
		}

		if ($route = $this->findCurrentRouteByTarget($target)) {
			$slug = $route->slug;
			$this->cache->save($serializedTarget, $slug);

			return $slug;
		} else {
			$this->cache->save($serializedTarget, FALSE);
		}

		return NULL;
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



	/**
	 * @param Brabijan\SeoComponents\Entity\Target $target
	 * @param Brabijan\SeoComponents\Entity\Route $route
	 */
	public function setCurrentRouteForTarget(Brabijan\SeoComponents\Entity\Target $target, Brabijan\SeoComponents\Entity\Route $route)
	{
		$qb = $this->routeDao->createQueryBuilder("r")->update();
		$qb->set("r.oneWay", ":oneWay")->setParameter(":oneWay", TRUE);
		$qb->where("r.target = :target")->setParameter(":target", $target);
		$qb->getQuery()->execute();

		$route->oneWay = FALSE;
		$this->routeDao->save($route);
	}



	/**
	 * @param $id
	 * @return Brabijan\SeoComponents\Entity\Route|object
	 */
	public function findRouteById($id)
	{
		return $this->routeDao->find($id);
	}



	/**
	 * @param Brabijan\SeoComponents\Entity\Route $route
	 */
	public function delete(Brabijan\SeoComponents\Entity\Route $route)
	{
		$target = $route->target;
		$isRouteOneWay = $route->oneWay;
		$this->routeDao->delete($route);
		if ($isRouteOneWay === FALSE) {
			$target = new Brabijan\SeoComponents\Router\Target($target->targetPresenter, $target->targetAction, $target->targetId);
			$currentRoute = $this->findCurrentRouteByTarget($target);
			if ($currentRoute) {
				$this->cleanTargetCache($target);
				$currentRoute->oneWay = FALSE;
				$this->routeDao->save($currentRoute);
			}
		}
	}



	private function cleanTargetCache(Brabijan\SeoComponents\Router\Target $target)
	{
		$this->cache->remove(serialize($target));
	}

}
<?php

namespace Brabijan\SeoComponents\Forms\Controls;

use Nette\Forms\Container;
use Nette;
use Brabijan;
use Brabijan\SeoComponents\Dao\Target as TargetDao;
use Brabijan\SeoComponents\Dao\Meta as MetaDao;
use Brabijan\SeoComponents\Dao\Route as RouteDao;

class SeoContainer extends Container
{

	/** @var TargetDao */
	private $targetDao;

	/** @var RouteDao */
	private $routeDao;

	/** @var MetaDao */
	private $metaDao;

	/** @var Brabijan\SeoComponents\Router\Target */
	private $target;

	/** @var array */
	private $defaults;



	public function __construct(Nette\Forms\ControlGroup $group = NULL, Brabijan\SeoComponents\Router\Target $target = NULL)
	{
		parent::__construct();
		$this->target = $target;
		if ($group) {
			$this->currentGroup = $group;
		}

		$this->addText("seoTitle", "Title:");
		$this->addTextArea("seoKeywords", "Keywords:");
		$this->addTextArea("seoDescription", "Description:");
		$this->addSelect("seoRobots", "Robots:", array(
			"index" => "index",
			"noindex" => "noindex",
		));
		$this->addSelect("sitemapChangeFreq", "Sitemap change frequency:", array(
			"always" => "always",
			"hourly" => "hourly",
			"daily" => "daily",
			"weekly" => "weekly",
			"monthly" => "monthly",
			"yearly" => "yearly",
			"never" => "never",
		));
		$this->addText("sitemapPriority", "Sitemap priority:")
			 ->setType("number")
			 ->setRequired()
			 ->setAttribute("step", "0.1")
			 ->addRule(Nette\Application\UI\Form::FLOAT, "Priority must be number between 0 and 1")
			 ->addRule(Nette\Application\UI\Form::RANGE, "Priority must be number between 0 and 1", array(0, 1));
		$this->addText("route", "Route:")
			 ->setRequired()
			 ->addFilter(function ($val) {
				 return ltrim($val, "/");
			 });
	}



	public function injectDependencies(TargetDao $targetDao, MetaDao $metaDao, RouteDao $routeDao)
	{
		$this->targetDao = $targetDao;
		$this->metaDao = $metaDao;
		$this->routeDao = $routeDao;
		$this->loadData();
	}



	public function setTarget(Brabijan\SeoComponents\Router\Target $target)
	{
		$this->target = $target;
		$this->loadData();
	}



	private function loadData()
	{
		$target = $this->targetDao->findTarget($this->target);
		if ($this->target && $target) {
			$meta = $target->meta;
			$currentRoute = $this->routeDao->findCurrentRouteByTarget($this->target);
			$this->defaults = array(
				"seoTitle" => $meta ? $meta->seoTitle : NULL,
				"seoKeywords" => $meta ? $meta->seoKeywords : NULL,
				"seoDescription" => $meta ? $meta->seoDescription : NULL,
				"seoRobots" => $meta ? $meta->seoRobots : "index",
				"sitemapChangeFreq" => $meta ? $meta->sitemapChangeFreq : "weekly",
				"sitemapPriority" => $meta ? $meta->sitemapPriority : "0.5",
				"route" => $currentRoute ? $currentRoute->slug : NULL
			);
			$this->setDefaults($this->defaults);
		} else {
			$this->setDefaults(array(
				"seoRobots" => "index",
				"sitemapChangeFreq" => "weekly",
				"sitemapPriority" => "0.5",
			));
		}
	}



	public function saveChanges()
	{
		$values = $this->getValues();
		$target = $this->targetDao->findTarget($this->target);
		if (!$target) {
			$target = new Brabijan\SeoComponents\Entity\Target();
			$target->targetPresenter = $this->target->presenter;
			$target->targetAction = $this->target->action;
			$target->targetId = $this->target->id;
			$this->targetDao->save($target);
		}

		if (!$target->meta) {
			$meta = new Brabijan\SeoComponents\Entity\Meta();
			$meta->target = $target;
			$target->meta = $meta;
			$this->metaDao->save($meta);
			$this->targetDao->save($target);
		}

		$meta = $target->meta;
		$meta->seoTitle = $values->seoTitle;
		$meta->seoKeywords = $values->seoKeywords;
		$meta->seoDescription = $values->seoDescription;
		$meta->seoRobots = $values->seoRobots;
		$meta->sitemapChangeFreq = $values->sitemapChangeFreq;
		$meta->sitemapPriority = $values->sitemapPriority;
		$this->metaDao->save($meta);

		$currentRoute = $this->routeDao->findCurrentRouteByTarget($this->target);
		if ($values->route && (!$currentRoute || $currentRoute->slug !== $values->route)) {
			$this->routeDao->addRoute($target, $values->route);
		}
	}

}



interface SeoContainerFactory
{

	/** @return SeoContainer */
	public function create(Nette\Forms\ControlGroup $group = NULL, Brabijan\SeoComponents\Router\Target $target = NULL);

}
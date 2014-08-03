<?php

namespace Brabijan\SeoComponents\Components;

use Nette\Application\UI\Control;
use Brabijan\SeoComponents\AllowedTargetList;
use Brabijan\SeoComponents\Dao;
use Brabijan\SeoComponents\Entity;
use Nette\Application\UI\Multiplier;

class SetRoute extends Control
{

	/** @var \Brabijan\SeoComponents\AllowedTargetList */
	private $allowedTargetList;

	/** @var \Brabijan\SeoComponents\Dao\Target */
	private $targetDao;

	/** @var \Brabijan\SeoComponents\Dao\Route */
	private $routeDao;

	/** @var array */
	private $preparedTargetSections = array();

	/** @var array */
	private $preparedTargetList = array();



	public function __construct(AllowedTargetList $allowedTargetList, Dao\Target $targetDao, Dao\Route $routeDao)
	{
		$this->allowedTargetList = $allowedTargetList;
		$this->targetDao = $targetDao;
		$this->routeDao = $routeDao;
		$this->prepareTargetList();
	}



	public function render()
	{
		$this->template->setFile(__DIR__ . "/SetRoute.latte");
		$this->template->sections = $this->preparedTargetSections;
		$this->template->routeList = $this->routeDao->getRouteListIndexedByTarget();
		$this->template->render();
	}



	private function prepareTargetList()
	{
		foreach ($this->allowedTargetList->getSections() as $section) {
			$name = $section->getName();
			$sections[$name] = array();
			foreach ($section->getTargetList() as $targetName => $target) {
				$targetEntity = $this->targetDao->findTarget($target);
				if (!$targetEntity) {
					$targetEntity = $this->targetDao->createBlankTarget($target);
				}
				$this->preparedTargetList[$targetEntity->id] = $targetEntity;
				$this->preparedTargetSections[$name][$targetName] = $targetEntity;
			}
		}
	}



	public function createComponentAddRouteForm()
	{
		return new Multiplier(function ($targetId) {
			$factory = new AddRouteForm($this->routeDao, $this->preparedTargetList[$targetId]);
			$form = $factory->create();
			$form->onSuccess[] = function () {
				$this->redirect("this");
			};

			return $form;
		});
	}



	public function handleDeleteRoute($routeId)
	{
		if ($route = $this->routeDao->findRouteById($routeId)) {
			$this->routeDao->delete($route);
		}
		$this->redirect("this");
	}



	public function handleMakeRouteActive($routeId)
	{
		if ($route = $this->routeDao->findRouteById($routeId)) {
			$this->routeDao->setCurrentRouteForTarget($route->target, $route);
		}
		$this->redirect("this");
	}

}



interface SetRouteFactory
{

	/** @return SetRoute */
	public function create();

}
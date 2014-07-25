<?php

namespace Brabijan\SeoComponents\Components;

use Brabijan\SeoComponents\AllowedTargetList;
use Nette\Application\UI\Control;
use Brabijan\SeoComponents\Dao;

class SetTarget extends Control
{

	/** @var \Brabijan\SeoComponents\AllowedTargetList */
	private $allowedTargetList;

	/** @var \Brabijan\SeoComponents\Dao\Target */
	private $targetDao;

	/** @var \Brabijan\SeoComponents\Dao\Meta */
	private $metaDao;

	/** @var array */
	private $preparedTargetSections = array();

	/** @var array */
	private $preparedTargetList = array();

	/** @var int */
	private $showForm;



	public function __construct(AllowedTargetList $allowedTargetList, Dao\Target $targetDao, Dao\Meta $metaDao)
	{
		$this->allowedTargetList = $allowedTargetList;
		$this->targetDao = $targetDao;
		$this->metaDao = $metaDao;
		$this->prepareTargetList();
	}



	public function render()
	{
		$this->template->setFile(__DIR__ . "/SetTarget.latte");
		$this->template->sections = $this->preparedTargetSections;
		$this->template->showForm = $this->showForm;
		$this->template->form = $this["form"];
		$this->template->render();
	}



	protected function createComponentForm()
	{
		$factory = new SetTargetForm($this->metaDao, $this->preparedTargetList);
		$factory->onProcessSingleRow[] = function ($containerValues) {
			$this->presenter->payload->values = $containerValues;
			$this->presenter->sendPayload();
		};
		$factory->onProcessEntireForm[] = function () {
			$this->redirect("this");
		};

		return $factory->create();
	}



	private function prepareTargetList()
	{
		foreach ($this->allowedTargetList->getSections() as $section) {
			$name = $section->getName();
			$sections[$name] = array();
			foreach ($section->getTargetList() as $targetName => $target) {
				$target = $this->targetDao->findTarget($target);
				$this->preparedTargetList[$target->id] = $target;
				$this->preparedTargetSections[$name][$targetName] = $target;
			}
		}
	}

}



interface SetTargetFactory
{

	/** @return SetTarget */
	public function create();

}
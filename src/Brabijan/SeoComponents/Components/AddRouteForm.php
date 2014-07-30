<?php

namespace Brabijan\SeoComponents\Components;

use Brabijan\SeoComponents\Dao\Route;
use Brabijan\SeoComponents\Entity\Target;
use Nette\Application\UI\Form;
use Nette\Object;

class AddRouteForm extends Object
{

	/** @var \Brabijan\SeoComponents\Dao\Route */
	private $routeDao;

	/** @var \Brabijan\SeoComponents\Entity\Target */
	private $targetEntity;



	public function __construct(Route $routeDao, Target $targetEntity)
	{
		$this->routeDao = $routeDao;
		$this->targetEntity = $targetEntity;
	}



	public function create()
	{
		$form = new Form();
		$form->addText("route", "New route:")
			 ->addFilter(function ($val) {
				 return ltrim($val, "/");
			 });
		$form->addSubmit("send", "Add route");

		$form->onSuccess[] = $this->processForm;

		return $form;
	}



	public function processForm(Form $form)
	{
		$this->routeDao->addRoute($this->targetEntity, $form->values->route);
	}

}
<?php

namespace Brabijan\SeoComponents\Forms;

use Brabijan\SeoComponents\Dao\Settings;
use Nette\Application\UI\Form;
use Nette\Object;

class SetRobots extends Object
{

	/** @var \Brabijan\SeoComponents\Dao\Settings */
	private $settingsDao;



	public function __construct(Settings $settingsDao)
	{
		$this->settingsDao = $settingsDao;
	}



	public function create()
	{
		$form = new Form();
		$form->addGroup("Set robots.txt");
		$form->addTextArea("robots", "Robots.txt content:");
		$form->addSubmit("send", "Set robots.txt");

		$form->onSuccess[] = $this->processForm;
		$form->setDefaults(array(
			"robots" => $this->settingsDao->getRobots(),
		));

		return $form;
	}



	public function processForm(Form $form)
	{
		$this->settingsDao->setRobots($form->values->robots);
	}

}
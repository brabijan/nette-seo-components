<?php

namespace Brabijan\SeoComponents\Forms;

use Brabijan\SeoComponents\Dao\Settings;
use Nette\Application\UI\Form;
use Nette\Object;

class SetBaseTitle extends Object
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
		$form->addGroup("Set base title");
		$form->addText("baseTitle", "Base title:");
		$form->addSubmit("send", "Set base title");

		$form->onSuccess[] = $this->processForm;
		$form->setDefaults(array(
			"baseTitle" => $this->settingsDao->getBaseTitle(),
		));

		return $form;
	}



	public function processForm(Form $form)
	{
		$this->settingsDao->setBaseTitle($form->values->baseTitle);
	}

} 
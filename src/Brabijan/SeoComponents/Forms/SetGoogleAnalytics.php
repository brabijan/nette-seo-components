<?php

namespace Brabijan\SeoComponents\Forms;

use Brabijan\SeoComponents\Dao\Settings;
use Nette\Object;
use Nette\Application\UI\Form;

class SetGoogleAnalytics extends Object
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
		$form->addGroup("Set Google Analytics key");
		$form->addText("key", "Google Analytics api key:")
			 ->setRequired('Fill Google Analytics api key');
		$form->addSubmit("send", "Set Google Analytics key");

		$form->onSuccess[] = $this->processForm;
		$form->setDefaults(array(
			"key" => $this->settingsDao->getGoogleAnalyticsKey(),
		));

		return $form;
	}



	public function processForm(Form $form)
	{
		$this->settingsDao->setGoogleAnalyticsKey($form->values->key);
	}

}
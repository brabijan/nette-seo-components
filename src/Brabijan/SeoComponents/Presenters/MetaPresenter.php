<?php

namespace Brabijan\SeoComponents\Presenters;

use Brabijan\SeoComponents\Dao\Settings;
use Nette\Application\UI\Presenter;

class MetaPresenter extends Presenter
{

	/** @var Settings @inject */
	public $settingsDao;


	public function renderGoogleWebmasterTools() {
		$this->template->webmasterTools = $this->settingsDao->getWebmasterTools();
	}


	public function renderRobots()
	{
		$this->template->robots = $this->settingsDao->getRobots();
	}



	public function formatTemplateFiles()
	{
		return array(__DIR__ . '/' . $this->view . '.latte');
	}

}
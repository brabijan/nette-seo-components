<?php

namespace Brabijan\SeoComponents\Components;

use Brabijan\SeoComponents\Dao\Settings;
use Nette\Application\UI\Control;

class GoogleAnalytics extends Control
{

	/** @var \Brabijan\SeoComponents\Dao\Settings */
	private $settingsDao;



	public function __construct(Settings $settingsDao)
	{
		$this->settingsDao = $settingsDao;
	}



	public function render()
	{
		$this->template->setFile(__DIR__ . "/GoogleAnalytics.latte");
		$this->template->googleAnalyticsKey = $this->settingsDao->getGoogleAnalyticsKey();
		$this->template->render();
	}
}



interface GoogleAnalyticsFactory
{

	/** @return GoogleAnalytics */
	public function create();

}

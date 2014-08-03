<?php

namespace Brabijan\SeoComponents\Components;

use Brabijan\SeoComponents\CurrentTarget;
use Brabijan\SeoComponents\Dao\Settings;
use Nette\Application\UI\Control;

class MetaTags extends Control
{

	/** @var \Brabijan\SeoComponents\CurrentTarget */
	private $currentTarget;

	/** @var \Brabijan\SeoComponents\Dao\Settings */
	private $settingsDao;



	public function __construct(CurrentTarget $currentTarget, Settings $settingsDao)
	{
		$this->currentTarget = $currentTarget;
		$this->settingsDao = $settingsDao;
	}



	public function render()
	{
		$target = $this->currentTarget->getCurrentTarget();
		$meta = $target && $target->getMeta() ? $target->meta : NULL;

		$this->template->setFile(__DIR__ . "/MetaTags.latte");
		$targetTitle = $meta ? $meta->seoTitle : NULL;
		$baseTitle = $this->settingsDao->getBaseTitle();
		if ($baseTitle and $targetTitle) {
			$title = $targetTitle . " - " . $baseTitle;
		} else {
			$title = $targetTitle . $baseTitle;
		}

		$this->template->title = $title;
		$this->template->keywords = $meta ? $meta->seoKeywords : NULL;
		$this->template->description = $meta ? $meta->seoDescription : NULL;
		$this->template->robots = $meta ? $meta->seoRobots : NULL;
		$this->template->render();
	}

}



interface MetaTagsFactory
{

	/** @return MetaTags */
	public function create();

}
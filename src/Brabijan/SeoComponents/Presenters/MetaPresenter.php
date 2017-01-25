<?php

namespace Brabijan\SeoComponents\Presenters;

use Brabijan\SeoComponents\AllowedTargetList;
use Brabijan\SeoComponents\Dao\Settings;
use Brabijan\SeoComponents\Dao\Target;
use Nette\Application\UI\Presenter;

class MetaPresenter extends Presenter
{

	/** @var Settings @inject */
	public $settingsDao;

	/** @var AllowedTargetList @inject */
	public $allowedTargetList;

	/** @var Target @inject */
	public $targetDao;



	protected function startup()
	{
		parent::startup();
		$this->autoCanonicalize = false;
	}



	public function renderGoogleWebmasterTools()
	{
		$this->template->webmasterTools = $this->settingsDao->getWebmasterTools();
	}



	public function renderRobots()
	{
		$this->template->robots = $this->settingsDao->getRobots();
	}



	public function renderSitemap()
	{
		$this->invalidLinkMode = self::INVALID_LINK_SILENT;
		$targetList = array();
		foreach ($this->allowedTargetList->getSections() as $section) {
			foreach ($section->getTargetList() as $target) {
				$link = $this->link('//:' . $target->presenter . ':' . $target->action, $target->id ? ["id" => $target->id] : []);
				if ($link == "#") {
					continue;
				}

				$target = $this->targetDao->findTarget($target);
				$targetList[] = array(
					"url" => $link,
					"changefreq" => ($target && $target->meta) ? $target->meta->sitemapChangeFreq : NULL,
					"priority" => ($target && $target->meta) ? $target->meta->sitemapPriority : NULL,
				);
			}
		}

		$this->template->targetList = $targetList;
	}



	public function formatTemplateFiles()
	{
		return array(__DIR__ . '/' . $this->view . '.latte');
	}

}

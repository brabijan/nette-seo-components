<?php

namespace Brabijan\SeoComponents\Components;

use Brabijan\SeoComponents\CurrentTarget;
use Nette\Application\UI\Control;

class MetaTags extends Control
{

	/** @var \Brabijan\SeoComponents\CurrentTarget */
	private $currentTarget;



	public function __construct(CurrentTarget $currentTarget)
	{
		$this->currentTarget = $currentTarget;
	}



	public function render()
	{
		$target = $this->currentTarget->getCurrentTarget();
		$meta = $target && $target->getMeta() ? $target->meta : NULL;

		$this->template->setFile(__DIR__ . "/MetaTags.latte");
		$this->template->title = $meta ? $meta->seoTitle : NULL;
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
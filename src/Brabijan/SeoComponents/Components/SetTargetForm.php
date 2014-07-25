<?php

namespace Brabijan\SeoComponents\Components;

use Brabijan\SeoComponents\Entity\Target;
use Brabijan\SeoComponents\Dao;
use Nette\Application\UI\Form;
use Nette\Forms\Controls\SubmitButton;
use Nette\Object;

class SetTargetForm extends Object
{

	/** @var \Brabijan\SeoComponents\Dao\Meta */
	private $metaDao;

	/** @var array */
	private $targetList;

	/** @var array */
	public $onProcessSingleRow = array();

	/** @var array */
	public $onProcessEntireForm = array();



	public function __construct(Dao\Meta $metaDao, array $targetList)
	{
		$this->metaDao = $metaDao;
		$this->targetList = $targetList;
	}



	public function create()
	{
		$form = new Form();
		$rows = $form->addContainer("rows");
		foreach ($this->targetList as $target) {
			$container = $rows->addContainer($target->id);
			$container->addText("seoTitle");
			$container->addTextArea("seoKeywords");
			$container->addTextArea("seoDescription");

			$container->addSelect("seoRobots", NULL, array(
				"index" => "index",
				"noindex" => "noindex",
			));
			$container->addSelect("sitemapChangeFreq", NULL, array(
				"always" => "always",
				"hourly" => "hourly",
				"daily" => "daily",
				"weekly" => "weekly",
				"monthly" => "monthly",
				"yearly" => "yearly",
				"never" => "never",
			));
			$container->addText("sitemapPriority", NULL)
					  ->setType("number")
					  ->setAttribute("step", "0.1")
					  ->addRule(Form::FLOAT, "Priority must be number between 0 and 1")
					  ->addRule(Form::RANGE, "Priority must be number between 0 and 1", array(0, 1));
			$container->addSubmit("save", "Save")
					  ->setValidationScope($container->getComponents())
				->onClick[] = $this->processSingleRow;

			$meta = $target->meta;
			$container->setDefaults(array(
				"seoTitle" => $meta->seoTitle,
				"seoKeywords" => $meta->seoKeywords,
				"seoDescription" => $meta->seoDescription,
				"seoRobots" => $meta->seoRobots,
				"sitemapChangeFreq" => $meta->sitemapChangeFreq,
				"sitemapPriority" => $meta->sitemapPriority,
			));
		}

		$form->addSubmit("send", "Save all")
			->onClick[] = $this->processEntireForm;

		return $form;
	}



	public function processSingleRow(SubmitButton $button)
	{
		$container = $button->getParent();
		$values = $button->getForm()->getValues(TRUE);
		$this->saveMeta($this->targetList[$container->getName()], $values["rows"][$container->getName()]);
		$this->onProcessSingleRow($values["rows"][$container->getName()]);
	}



	public function processEntireForm(SubmitButton $button)
	{
		$form = $button->getForm();
		$values = $form->getValues(TRUE);

		foreach ($values["rows"] as $containerId => $containerValues) {
			$this->saveMeta($this->targetList[$containerId], $containerValues);
		}
		$this->onProcessEntireForm($form);
	}



	/**
	 * @param Target $target
	 * @param array $values
	 */
	private function saveMeta(Target $target, array $values)
	{
		$meta = $target->meta;
		$meta->seoTitle = $values["seoTitle"];
		$meta->seoKeywords = $values["seoKeywords"];
		$meta->seoDescription = $values["seoDescription"];
		$meta->seoRobots = $values["seoRobots"];
		$meta->sitemapPriority = $values["sitemapPriority"];
		$meta->sitemapChangeFreq = $values["sitemapChangeFreq"];
		$this->metaDao->save($meta);
	}

} 
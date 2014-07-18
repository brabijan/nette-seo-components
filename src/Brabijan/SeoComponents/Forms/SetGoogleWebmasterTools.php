<?php

namespace Brabijan\SeoComponents\Forms;

use Brabijan\SeoComponents\Dao\Settings;
use Nette\Application\UI\Form;
use Nette\Http\FileUpload;
use Nette\Object;

class SetGoogleWebmasterTools extends Object
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
		$form->addGroup("Set Google Webmaster tools");
		$form->addUpload("file", "Google Webmaster tools file:")
			 ->setRequired("Upload Google Webmaster tools file");
		$form->addSubmit("send", "Set Google Webmaster tools");

		$form->onSuccess[] = $this->processForm;

		return $form;
	}



	public function processForm(Form $form)
	{
		/** @var FileUpload $file */
		$file = $form->values->file;
		if ($file->size > 0) {
			$fileContent = file_get_contents($file->getTemporaryFile());
			$this->settingsDao->setWebmasterToolsName($file->getName());
			$this->settingsDao->setWebmasterTools($fileContent);
		}
	}

}
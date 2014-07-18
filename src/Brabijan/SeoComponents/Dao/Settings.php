<?php

namespace Brabijan\SeoComponents\Dao;

use Brabijan\SeoComponents\Entity;
use Kdyby\Doctrine\EntityDao;
use Nette\Object;

class Settings extends Object
{

	/** @var \Kdyby\Doctrine\EntityDao */
	private $settingsDao;



	public function __construct(EntityDao $settingsDao)
	{
		$this->settingsDao = $settingsDao;
	}



	/**
	 * @param $key
	 * @param $value
	 */
	private function setValue($key, $value)
	{
		if ($this->settingsDao->countBy(array("name" => $key)) != 0) {
			$setting = $this->settingsDao->findOneBy(array("name" => $key));
			$setting->value = $value;
			$this->save($setting);
		} else {
			$newSetting = new Entity\Settings();
			$newSetting->name = $key;
			$newSetting->value = $value;
			$this->save($newSetting);
		}
	}



	/**
	 * @param $key
	 * @return mixed|null
	 */
	private function getValue($key)
	{
		$setting = $this->settingsDao->findOneBy(array("name" => $key));

		return $setting ? $setting->value : NULL;
	}



	public function setWebmasterToolsName($webmasterToolsName)
	{
		$this->setValue("webmasterToolsName", $webmasterToolsName);
	}



	public function getWebmasterToolsName()
	{
		return $this->getValue("webmasterToolsName");
	}



	public function setWebmasterTools($webmasterTools)
	{
		$this->setValue("webmasterToolsKey", $webmasterTools);
	}



	public function getWebmasterTools()
	{
		return $this->getValue("webmasterToolsKey");
	}



	public function setRobots($robots)
	{
		$this->setValue("robots", $robots);
	}



	public function getRobots()
	{
		return $this->getValue("robots");
	}



	public function setGoogleAnalyticsKey($googleAnalyticsKey)
	{
		$this->setValue("googleAnalyticsKey", $googleAnalyticsKey);
	}



	public function getGoogleAnalyticsKey()
	{
		return $this->getValue("googleAnalyticsKey");
	}



	/**
	 * @param Entity\Settings $setting
	 */
	public function save(Entity\Settings $setting)
	{
		$this->settingsDao->save($setting);
	}

}
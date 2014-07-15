<?php

namespace Brabijan\SeoComponents\Router;

use Nette;

/**
 * @property $presenter
 * @property $action
 * @property $id
 */
class Target extends Nette\Object
{

	/** @var string */
	private $presenter;

	/** @var string */
	private $action;

	/** @var string */
	private $id;



	public function __construct($presenter, $action, $id = NULL)
	{
		$this->presenter = $presenter;
		$this->action = $action;
		$this->id = $id;
	}



	/**
	 * @return mixed
	 */
	public function getPresenter()
	{
		return $this->presenter;
	}



	/**
	 * @return string
	 */
	public function getAction()
	{
		return $this->action;
	}



	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

}
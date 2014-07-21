<?php

namespace Brabijan\SeoComponents\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Kdyby\Doctrine\Entities\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @property $id
 * @property $targetPresenter
 * @property $targetAction
 * @property $targetId
 * @property ArrayCollection|Route[] $routes
 * @property Meta $meta
 */
class Target extends BaseEntity
{

	/**
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/** @ORM\Column(type="string") */
	protected $targetPresenter;

	/** @ORM\Column(type="string") */
	protected $targetAction;

	/** @ORM\Column(type="integer", nullable=true) */
	protected $targetId;

	/** @ORM\OneToMany(targetEntity="Brabijan\SeoComponents\Entity\Route", mappedBy="target") */
	protected $routes;

	/** @ORM\OneToOne(targetEntity="Brabijan\SeoComponents\Entity\Meta", inversedBy="target") */
	protected $meta;



	public function __construct()
	{
		$this->routes = new ArrayCollection();
	}



	/**
	 * @return ArrayCollection|Route[]
	 */
	public function getRoutes()
	{
		return $this->routes;
	}



	/**
	 * @param Meta $meta
	 */
	public function setMeta(Meta $meta)
	{
		$this->meta = $meta;
	}



	/**
	 * @return Meta
	 */
	public function getMeta()
	{
		return $this->meta;
	}

}
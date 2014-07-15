<?php

namespace Brabijan\SeoComponents\Entity;

use Kdyby\Doctrine\Entities\BaseEntity;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity()
 * @property $id
 * @property $slug
 * @property boolean $oneWay
 * @property Target $target
 */
class Route extends BaseEntity
{

	/**
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/** @ORM\Column(type="string") */
	protected $slug;

	/** @ORM\Column(type="boolean") */
	protected $oneWay;

	/** @ORM\ManyToOne(targetEntity="Brabijan\SeoComponents\Entity\Target", inversedBy="routes") */
	protected $target;



	/**
	 * @return Target
	 */
	public function getTarget()
	{
		return $this->target;
	}

}
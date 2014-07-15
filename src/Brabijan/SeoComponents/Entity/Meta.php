<?php

namespace Brabijan\SeoComponents\Entity;

use Kdyby\Doctrine\Entities\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @property $id
 * @property $seoTitle
 * @property $seoKeywords
 * @property $seoDescription
 * @property $seoRobots
 */
class Meta extends BaseEntity
{

	/**
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/** @ORM\Column(type="string") */
	protected $seoTitle;

	/** @ORM\Column(type="string") */
	protected $seoKeywords;

	/** @ORM\Column(type="text") */
	protected $seoDescription;

	/** @ORM\Column(type="string") */
	protected $seoRobots;

	/** @ORM\OneToOne(targetEntity="Brabijan\SeoComponents\Entity\Target", inversedBy="meta") */
	protected $target;

}
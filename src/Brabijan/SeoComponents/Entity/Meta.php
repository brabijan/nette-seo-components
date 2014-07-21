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
 * @property $sitemapChangeFreq
 * @property $sitemapPriority
 * @property Target $target
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

	/** @ORM\Column(type="string") */
	protected $sitemapChangeFreq;

	/** @ORM\Column(type="string") */
	protected $sitemapPriority;

	/** @ORM\OneToOne(targetEntity="Brabijan\SeoComponents\Entity\Target", inversedBy="meta") */
	protected $target;



	/**
	 * @param Target $target
	 */
	public function setTarget(Target $target)
	{
		$this->target = $target;
	}



	/**
	 * @return Target
	 */
	public function getTarget()
	{
		return $this->target;
	}

}
<?php

namespace Brabijan\SeoComponents\Entity;

use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\BaseEntity;

/**
 * @ORM\Entity()
 * @ORM\Table(name="`seoSettings`")
 * @property $id
 * @property $name
 * @property $value
 */
class Settings extends BaseEntity
{

	/**
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/** @ORM\Column(type="string", unique=true) */
	protected $name;

	/** @ORM\Column(type="text") */
	protected $value;

}
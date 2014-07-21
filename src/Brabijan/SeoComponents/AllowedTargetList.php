<?php

namespace Brabijan\SeoComponents;

use Brabijan\SeoComponents\DI\ITargetSectionProvider;
use Nette\DI\Container;
use Nette\InvalidStateException;
use Nette\Object;

class AllowedTargetList extends Object
{

	/** @var \Nette\DI\Container */
	private $container;



	public function __construct(Container $container)
	{
		$this->container = $container;
	}



	/**
	 * @return TargetSection[]
	 * @throws \Nette\InvalidStateException
	 */
	public function getSections()
	{
		$sections = array();
		foreach ($this->container->findByTag("Brabijan.seo.targetSectionProvider") as $serviceName => $attributes) {
			$section = $this->container->getService($serviceName);
			if (!$section instanceof ITargetSectionProvider) {
				throw new InvalidStateException('Target provider must be instance of Brabijan\SeoComponents\DI\ITargetSectionProvider');
			}
			$sections[] = $section->getTargetSection();
		}

		return $sections;
	}

}
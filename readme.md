# SEO components

This is seo components for [Nette Framework](http://nette.org/) which uses Doctrine2. It's allows you control these things:
 
- Google analytics
- Google webmaster tools
- Auto generated sitemap.xml
- robots.txt
- meta tags (title, keywords, description and robots)
- SEO friendly URLs

## Instalation

The best way to install brabijan/nette-seo-components is using  [Composer](http://getcomposer.org/):


```sh
$ composer require brabijan/nette-seo-components:@dev
```

Then you have to register extension in `config.neon`.

```yaml
extensions:
	- Brabijan\SeoComponents\DI\SeoExtension
```

## Usage

You have to insert following lines to your BasePresenter and use components in your @layout.latte 
(typically MetaTags to `<head>` and GoogleAnalytics before `</body>` tag)

```php
/** @var \Brabijan\SeoComponents\Components\MetaTagsFactory @inject */
public $metaTagsFactory;

/** @var \Brabijan\SeoComponents\Components\GoogleAnalyticsFactory @inject */
public $googleAnalyticsFactory;



public function createComponentMetaTags()
{
	return $this->metaTagsFactory->create();
}



public function createComponentGoogleAnalytics()
{
	return $this->googleAnalyticsFactory->create();
}
```

## Administration

### Application targets

For proper generating sitemap.xml or setting up target meta data we have to create list of application targets.
Let's have PagesManager for example. If we want to use it as application target list, implement ITargetSectionProvider on it. 

```php
<?php

namespace App\Model\Pages;

use Brabijan\SeoComponents\DI\ITargetSectionProvider;
use Brabijan\SeoComponents\Router\Target;

class PagesManager extends Object implements ITargetSectionProvider {


	/**
	 * @return Page
	 */
	public function getPagesList()
	{
		// return ...;
	}



	/**
	 * @return TargetSection
	 */
	public function getTargetSection()
	{
		$section = new TargetSection("Pages");
		foreach ($this->getPagesList() as $page) {
			$section->addTarget($page->name, new Target("Page", "show", $page->id));
		}

		return $section;
	}
	
}
```

and register it in your config.neon 

```yaml
services
	-
		class: App\Model\Pages\PageManager
		tags: [ Brabijan.seo.targetSectionProvider ]
```

### Forms

Library contains few forms in namespace `Brabijan\SeoComponents\Forms`, which you can use for set up Google Analytics, Google Webmaster tools and robots.txt.

### Form controls

In class Brabijan\SeoComponents\Forms\Controls\SeoContainer you can find container, which allows you to set up meta tags and url directly in your forms. 
Above we have example with pages, and now we're continue in it. 

```php
<?php

namespace App\AdminModule\Components;

use App\Model\Pages\Page;
use App\Model\Pages\PageManager;
use Brabijan\SeoComponents\Forms\Controls;
use Brabijan\SeoComponents\Router\Target;
use Nette\Application\UI\Form;
use Nette;

class SetPageForm extends Nette\Object
{

	/** @var PageManager */
	private $pageManager;

	/** @var Controls\SeoContainerFactory */
	private $seoContainerFactory;

	/** @var Page */
	private $page;



	public function __construct(PageFacade $pageManager, Controls\SeoContainerFactory $seoContainerFactory)
	{
		$this->pageManager = $pageManager;
		$this->seoContainerFactory = $seoContainerFactory;
	}



	public function setPage(Page $page)
	{
		$this->page = $page;
	}



	public function create()
	{
		$form = new Form();
		$form->addGroup($this->page ? "Edit page" : "Add page");
		$form->addText("name", "Name:");
		$form->addTextArea("content", "Content:");
		$seoGroup = $form->addGroup("SEO");
		$form['seoComponents'] = $this->seoContainerFactory->create($seoGroup);
		$form->addGroup();
		$form->addSubmit("send", $this->page ? "Edit page" : "Add page");

		$form->onSuccess[] = $this->processForm;
		if ($this->page) {
			$form->setDefaults(array(
				"name" => $this->page->name,
				"content" => $this->page->content,
			));
		}

		return $form;
	}



	public function processForm(Form $form)
	{
		$values = $form->values;
		$page = $this->page ? $this->page : new Page();
		$page->name = $values->name;
		$page->content = $values->content;
		$this->pageManager->save($page);

		/** @var Controls\SeoContainer $seoComponents */
		$seoComponents = $form['seoComponents'];
		$seoComponents->setTarget(new Target("Page", "show", $page->id));
		$seoComponents->saveChanges();
	}

}
```

### Components

There is component Brabijan\SeoComponents\Components\SetTarget. It's used for global settings all application targets. Use it anywhere in your administration.

```php
/** @var SetTargetFactory @inject */
public $setTargetFactory;



public function createComponentSetTarget()
{
	return $this->setTargetFactory->create();
}
```
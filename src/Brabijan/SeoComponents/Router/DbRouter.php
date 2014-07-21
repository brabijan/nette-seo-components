<?php

namespace Brabijan\SeoComponents\Router;

use Brabijan\SeoComponents\CurrentTarget;
use Brabijan\SeoComponents\Dao\Route as RouteDao;
use Brabijan\SeoComponents\Dao\Target as TargetDao;
use Brabijan\SeoComponents\Dao\Settings as SettingsDao;
use Nette\Application\IRouter;
use Nette\Application\Request;
use Nette\Application\Routers\RouteList;
use Nette;

class DbRouter extends Nette\Object implements IRouter
{

	/** @var RouteDao */
	private $routeDao;

	/** @var TargetDao */
	private $targetDao;

	/** @var SettingsDao */
	private $settingsDao;

	/** @var \Brabijan\SeoComponents\CurrentTarget */
	private $currentTarget;

	/** @var Target */
	private $defaultRoute;



	public function __construct(RouteDao $routeDao, TargetDao $targetDao, SettingsDao $settingsDao, CurrentTarget $currentTarget)
	{
		$this->routeDao = $routeDao;
		$this->targetDao = $targetDao;
		$this->currentTarget = $currentTarget;
		$this->settingsDao = $settingsDao;
		$this->defaultRoute = new Target("Homepage", "default", NULL);
	}



	/**
	 * @param $presenter
	 * @param $action
	 * @param null $id
	 */
	public function setDefaultRoute($presenter, $action, $id = NULL)
	{
		$this->defaultRoute = new Target($presenter, $action, $id);
	}



	/**
	 * Maps HTTP request to a Request object.
	 *
	 * @param Nette\Http\IRequest $httpRequest
	 * @return Request|NULL
	 */
	public function match(Nette\Http\IRequest $httpRequest)
	{
		$relativeUrl = trim($httpRequest->getUrl()->relativeUrl, "/");
		$path = trim($httpRequest->getUrl()->path, "/");

		if ($relativeUrl == "") {
			$target = $this->defaultRoute;
			$this->currentTarget->setCurrentTarget($this->targetDao->findTarget($target->presenter, $target->action, $target->id));
		} elseif ($relativeUrl == "sitemap.xml") {
			$target = new Target("Seo:Meta", "sitemap");
		} elseif ($relativeUrl == "robots.txt") {
			$target = new Target("Seo:Meta", "robots");
		} elseif (substr($relativeUrl, 0, 6) == "google" && $this->settingsDao->getWebmasterToolsName() == $relativeUrl) {
			$target = new Target("Seo:Meta", "googleWebmasterTools");
		} else {
			$route = $this->routeDao->findRouteBySlug($relativeUrl, TRUE);
			if (!$route) {
				$route = $this->routeDao->findRouteBySlug($path, TRUE);
				if (!$route) {
					return NULL;
				}
			}
			$this->currentTarget->setCurrentTarget($route->getTarget());
			$target = new Target($route->target->targetPresenter, $route->target->targetAction, $route->target->targetId);
		}

		$params = array();
		$params["action"] = $target->action;
		if ($target->id) {
			$params["id"] = $target->id;
		}
		$params += $httpRequest->getQuery();

		return new Request(
			$target->presenter,
			$httpRequest->getMethod(),
			$params,
			$httpRequest->getPost(),
			$httpRequest->getFiles(),
			array(Request::SECURED => $httpRequest->isSecured())
		);
	}



	/**
	 * Constructs absolute URL from Request object.
	 *
	 * @param Request $appRequest
	 * @param Nette\Http\Url $refUrl
	 * @return NULL|string
	 */
	public function constructUrl(Request $appRequest, Nette\Http\Url $refUrl)
	{
		if ($appRequest->presenterName == "Seo:Meta") {
			$action = $appRequest->parameters["action"];
			if ($action == "sitemap") {
				return "/sitemap.xml";
			} elseif ($action == "robots") {
				return "/robots.txt";
			} elseif ($action == "googleWebmasterTools" and $webmasterToolsName = $this->settingsDao->getWebmasterToolsName()) {
				return "/" . $webmasterToolsName;
			}
		}

		$id = isset($appRequest->parameters["id"]) ? $appRequest->parameters["id"] : NULL;
		$target = new Target($appRequest->presenterName, $appRequest->parameters["action"], $id);

		if ($this->defaultRoute == $target) {
			$slug = "/";
		} else {
			$route = $this->routeDao->findCurrentRouteByTarget($target);
			if (!$route) {
				return NULL;
			}
			$slug = "/" . $route->slug;
		}
		$parameters = $appRequest->parameters;
		unset($parameters["action"], $parameters["id"]);

		$url = clone $refUrl;
		$url->setPath($slug);
		$url->setQuery($parameters);

		return $url;
	}



	/**
	 * @author Filip Procházka <filip@prochazka.su>
	 *
	 * @param \Nette\Application\IRouter $router
	 * @param DbRouter $cliRouter
	 * @throws \Nette\Utils\AssertionException
	 * @return \Nette\Application\Routers\RouteList
	 */
	public static function prependTo(Nette\Application\IRouter &$router, self $cliRouter)
	{
		if (!$router instanceof RouteList) {
			throw new Nette\Utils\AssertionException(
				'If you want to use Kdyby/Console then your main router ' .
				'must be an instance of Nette\Application\Routers\RouteList'
			);
		}

		$router[] = $cliRouter; // need to increase the array size

		$lastKey = count($router) - 1;
		foreach ($router as $i => $route) {
			if ($i === $lastKey) {
				break;
			}
			$router[$i + 1] = $route;
		}

		$router[0] = $cliRouter;
	}

}
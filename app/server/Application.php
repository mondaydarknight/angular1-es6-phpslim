<?php

namespace App;

use RocketTheme\Toolbox\Event\EventDispatcher;
use RocketTheme\Toolbox\Event\Event;
use Slim\App;
use Slim\Container;

class Application
{

	const VERSION = '1.0.0';

	/**
	 * @var Container The global container object
	 */
	protected $container;

	/**
	 * @var The application of Slim
	 */
	protected $app;

	protected $resourcePaths = [
		'assets',
		'config',
		'extra',
		'locale',
		'schema',
		'routes',
		'templates'
	];

	/**
	 * Constructor
	 */ 
	public function __construct() 
	{
		$this->container = new Container;
	}

	/**
	 * fire up all events of application
	 *
	 * @param string $eventName
	 * @param closure|null
	 */
	public function dispatchEvent($eventName, Event $event = null)
	{
		return $this->container->eventDispatcher->dispatch($eventName, $event);
	}

	/**
	 * @return object
	 */
	public function getApp()
	{
		return $this->app;
	}

	/**
	 * @return object
	 */
	public function getContainer()
	{
		return $this->container;
	}

	/**
	 * @todo Execute the application 
	 */
	public function run()
	{
		$this->setupConfiguration();
		$this->app = new App($this->container);
		// $this->container->settings = $this->container->config['settings'];

		// $this->dispatchEvent('onAppInitialization', $appEvent);
		$this->loadRoutes();

		$this->app->run();
	}

	protected function setupConfiguration($isWeb = true)
	{	
		$serviceProvider = new serviceProvider;
		$serviceProvider->register($this->container);
		$this->buildConfigureResource();	
	}


	protected function buildConfigureResource()
	{
		$serverPath = __DIR__;

		foreach ($this->resourcePaths as $path) {		
			$this->container->locator->addPath($path, '', $serverPath . DIRECTORY_SEPARATOR . $path);
			$this->container->locator->findResource("$path://", true, false);
		}
	}

	/**
	 * Include all defined routes in route stream
	 *
	 * @todo Since routes aren't encasulated in al calss yet, we need global workaround
	 *
	 */
	protected function loadRoutes()
	{
		global $app;
		$app = $this->app;
	
		$routesPaths = $this->container->locator->findResources('routes://', true, true);

		foreach ($routesPaths as $key => $path) {
			$routeFiles = glob($path . '/*.php');
		
			foreach ($routeFiles as $file) {
				require_once $file;
			}
		}
	}

	/**
	 * Destructor
	 */
	public function __destruct()
	{
		unset($this->container);
	}

	public function __toString()
	{
		return static::VERSION;
	}

}
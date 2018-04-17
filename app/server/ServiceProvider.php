<?php

namespace App;

use Interop\Container\ContainerInterface;
use RocketTheme\Toolbox\Event\EventDispatcher;
use RocketTheme\Toolbox\ResourceLocator\UniformResourceLocator;
use RocketTheme\Toolbox\StreamWrapper\ReadOnlyStream;
use RocketTheme\Toolbox\StreamWrapper\StreamBuilder;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;

class ServiceProvider
{
	/**
	 * Setup the event dispatcher, required by application to hook into lifestyle
	 *
	 */
	public function register(ContainerInterface $container)
	{
		$container['eventDispatcher'] = function($c) {
			return new EventDispatcher;
		};

		$container['locator'] = function($c) {
			$locator = new UniformResourceLocator(ROOT_DIR);
			
			ReadOnlyStream::setLocator($locator);

			$c->streamBuilder;

			return $locator;
		};

		$container['streamBuilder'] = function($c) {
			$streams = [
				'templates' => '\\RocketTheme\\Toolbox\\StreamWrapper\\ReadOnlyStream',
				'config'	=> '\\RocketTheme\\Toolbox\\StreamWrapper\\ReadOnlyStream',
				'routes'	=> '\\RocketTheme\\Toolbox\\StreamWrapper\\ReadOnlyStream'
			];

			foreach ($streams as $streamName => $stream) {
				if (in_array($streamName, stream_get_wrappers())) {
					stream_wrapper_unregister(($streamName));
				}
			}

			$streamBuilder = new StreamBuilder($streams);
			return $streamBuilder;
		};


		$container['view'] = function($c) {
			$templatePaths = $c->locator->findResource('templates://', true, true);
			$view = new Twig($templatePaths);

			$loader = $view->getLoader();

			$slimExtension = new TwigExtension($c->router, $c->request->getUri());
			$view->addExtension($slimExtension);
			
			return $view;
		};


	}



}


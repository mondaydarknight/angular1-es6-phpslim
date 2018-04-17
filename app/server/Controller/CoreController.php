<?php

namespace App\Controller;

use Interop\Container\ContainerInterface;


class CoreController
{
	/**
	 * @var ContainerInterface 
	 */
	protected $container;


	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}
	
	public function pageIndex($request, $response, $args)
	{
		return $this->container->view->render($response, 'base.html.twig');
	}

}

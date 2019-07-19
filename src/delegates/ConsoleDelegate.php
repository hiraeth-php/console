<?php

namespace Hiraeth\Console;

use Hiraeth;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\CommandLoader\ContainerCommandLoader;

/**
 * Delegates are responsible for constructing dependencies for the dependency injector.
 *
 * Each delegate operates on a single concrete class and provides the class that it is capable
 * of building so that it can be registered easily with the application.
 */
class ConsoleDelegate implements Hiraeth\Delegate
{
	/**
	 * Get the class for which the delegate operates.
	 *
	 * @static
	 * @access public
	 * @return string The class for which the delegate operates
	 */
	static public function getClass(): string
	{
		return Application::class;
	}


	/**
	 * Get the instance of the class for which the delegate operates.
	 *
	 * @access public
	 * @param Hiraeth\Application $app The application instance for which the delegate operates
	 * @return object The instance of the class for which the delegate operates
	 */
	public function __invoke(Hiraeth\Application $app): object
	{
		$console    = new Application();
		$helper_set = new HelperSet();
		$cmd_list   = array();

		foreach ($app->getConfig('*', 'console.helpers', array()) as $collection => $helpers) {
			foreach ($helpers as $alias => $helper) {
				$helper_set->set($app->get($helper), $alias);
			}
		}

		foreach ($app->getConfig('*', 'console.commands', array()) as $collection => $commands) {
			foreach ($commands as $name => $command) {
				if (is_numeric($name)) {
					$cmd_list[$command::getDefaultName()] = $command;
				} else {
					$cmd_list[$name] = $command;
				}
			}
		}

		//
		// NOTE: The order below matters, helper set must be set before adding commands or else the
		// commands will not get them.
		//

		$console->setHelperSet($helper_set);
		$console->setCommandLoader(new ContainerCommandLoader($app, $cmd_list));

		return $console;
	}
}

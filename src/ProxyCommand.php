<?php

namespace Hiraeth\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

abstract class ProxyCommand extends Command
{
	/**
	 * @var string
	 */
	static protected $defaultName;

	/**
	 * @var array<string>
	 */
	static protected $excludeOptions = array();

	/**
	 * @var array<string>
	 */
	static protected $excludePassthruOptions = array();

	/**
	 * @var class-string<Command>
	 */
	static protected $proxy;

	/**
	 *
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 *
	 */
	protected function configure(): void
	{
		$proxy  = new static::$proxy();
		$merges = array_diff_key(
			$proxy->getDefinition()->getOptions(),
			array_combine(
				static::$excludeOptions,
				array_pad([], count(static::$excludeOptions), FALSE)
			)
		);

		$proxy
			->setDescription($proxy->getDescription())
			->setHelp($proxy->getHelp())
		;

		foreach ($merges as $option) {
			$option_mode = NULL;

			if ($option->isValueRequired()) {
				$option_mode = $option_mode + InputOption::VALUE_REQUIRED;
			}

			if ($option->isValueOptional()) {
				$option_mode = $option_mode + InputOption::VALUE_OPTIONAL;
			}

			if ($option->isArray()) {
				$option_mode = $option_mode + InputOption::VALUE_IS_ARRAY;
			}

			if ($option->isNegatable()) {
				$option_mode = $option_mode + InputOption::VALUE_NEGATABLE;
			}

			$this->addOption(
				$option->getName(),
				$option->getShortcut(),
				!is_null($option_mode)
					? $option_mode
					:NULL,
				$option->getDescription(),
				$option->isValueOptional()
					? $option->getDefault()
					: NULL
			);
		}

		foreach ($proxy->getDefinition()->getArguments() as $argument) {
			$argument_mode = NULL;

			if ($argument->isRequired()) {
				$argument_mode = $argument_mode + InputArgument::REQUIRED;
			} else {
				$argument_mode = $argument_mode + InputArgument::OPTIONAL;
			}

			if ($option->isArray()) {
				$argument_mode = $argument_mode + InputArgument::IS_ARRAY;
			}

			$this->addArgument(
				$argument->getName(),
				$argument_mode,
				$argument->getDescription(),
				!$argument->isRequired()
					? $argument->getDefault()
					: NULL
			);
		}
	}


	/**
	 *
	 */
	protected function passthru(InputInterface $input): InputInterface
	{

		$parameters    = array();

		foreach ($input->getOptions() as $option => $value) {
			if (in_array($option, static::$excludePassthruOptions)) {
				continue;
			}

			if ($value) {
				$parameters['--' . $option] = $value;
			}
		}

		foreach ($input->getArguments() as $argument => $value) {
			if ($argument == 'command') {
				continue;
			}

			if ($value) {
				$parameters[$argument] = $value;
			}
		}

		$passthru = new ArrayInput($parameters);
		$passthru->setInteractive($input->isInteractive());

		return $passthru;
	}
}

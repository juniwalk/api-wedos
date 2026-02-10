<?php declare(strict_types=1);

/**
 * @copyright Martin Procházka (c) 2022
 * @license   MIT License
 */

namespace JuniWalk\Wedos\DI;

use JuniWalk\Wedos\Connector;
use Nette\DI\CompilerExtension;
use Nette\Schema\Expect;
use Nette\Schema\Schema;

final class WedosExtension extends CompilerExtension
{
	public function getConfigSchema(): Schema
	{
		return Expect::structure([
			'user' => Expect::string()->required(),
			'password' => Expect::string()->required(),
			'isTest' => Expect::bool(),
			'config' => Expect::structure([])
				->otherItems(Expect::type('string|int|bool'))
				->castTo('array'),
		])->castTo('array');
	}


	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();
		$config = $this->getConfig();

		$builder->addDefinition($this->prefix('connector'))
			->setFactory(Connector::class, (array) $config);
	}
}

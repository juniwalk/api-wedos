<?php declare(strict_types=1);

/**
 * @copyright Martin Procházka (c) 2026
 * @license   MIT License
 */

use Nette\Bootstrap\Configurator;
use Nette\DI\Container;
use Tester\Environment;
use Tester\Helpers;

define('ProcessId', getmypid());

if (@!include __DIR__.'/../vendor/autoload.php') {
	echo 'Install Nette Tester using `composer install`';
	exit(1);
}

const TemporaryDir = __DIR__.'/../temp/'.ProcessId;

Environment::setup();
Helpers::purge(TemporaryDir);


function createContainer(): Container
{
	$configurator = new Configurator;
	$configurator->setDebugMode(true);
	$configurator->setTempDirectory(TemporaryDir);
	$configurator->addConfig(__DIR__.'/config.neon');
	$configurator->addStaticParameters([
		'pid' => ProcessId,
	]);

	return $configurator->createContainer();
}

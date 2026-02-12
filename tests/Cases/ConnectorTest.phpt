<?php declare(strict_types=1);

/**
 * @copyright Martin Procházka (c) 2026
 * @license   MIT License
 */

namespace JuniWalk\Tests\Cases;

require __DIR__ . '/../bootstrap.php';

use JuniWalk\Wedos\Connector;
use Tester\Assert;
use Tester\TestCase;

class ConnectorTest extends TestCase
{
	public function testCall(): void
	{
		$wedos = createContainer()->getByType(Connector::class);
		$response = $wedos->call('ping');

		Assert::same('OK', $response->getResult());
	}
}

(new ConnectorTest)->run();

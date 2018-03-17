<?php
/**
 * Test: IPub\FlashMessages\Extension
 * @testCase
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:FlashMessages!
 * @subpackage     Tests
 * @since          1.0.0
 *
 * @date           07.02.15
 */

declare(strict_types = 1);

namespace IPubTests\FlashMessages;

use Nette;

use Tester;
use Tester\Assert;

use IPub\FlashMessages;

require __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'bootstrap.php';

class ExtensionTest extends Tester\TestCase
{
	public function testCompilersServices() : void
	{
		$dic = $this->createContainer();

		Assert::true($dic->getService('flashMessages.notifier') instanceof FlashMessages\FlashNotifier);
		Assert::true($dic->getService('flashMessages.storage') instanceof FlashMessages\Storage\Session);
		Assert::true($dic->getService('flashMessages.onResponseHandler') instanceof FlashMessages\Events\OnResponseHandler);

		// Get component factory
		$factory = $dic->getService('flashMessages.messages');

		Assert::true($factory instanceof FlashMessages\Components\IControl);
		Assert::true($factory->create() instanceof FlashMessages\Components\Control);
	}

	/**
	 * @return Nette\DI\Container
	 */
	protected function createContainer() : Nette\DI\Container
	{
		$config = new Nette\Configurator();
		$config->setTempDirectory(TEMP_DIR);

		FlashMessages\DI\FlashMessagesExtension::register($config);

		return $config->createContainer();
	}
}

\run(new ExtensionTest());

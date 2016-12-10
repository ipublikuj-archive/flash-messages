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

use IPub;
use IPub\FlashMessages;

require __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'bootstrap.php';

class ExtensionTest extends Tester\TestCase
{
	public function testCompilersServices()
	{
		$dic = $this->createContainer();

		Assert::true($dic->getService('flashMessages.notifier') instanceof IPub\FlashMessages\FlashNotifier);
		Assert::true($dic->getService('flashMessages.storage') instanceof IPub\FlashMessages\Storage\Session);
		Assert::true($dic->getService('flashMessages.onResponseHandler') instanceof IPub\FlashMessages\Events\OnResponseHandler);

		// Get component factory
		$factory = $dic->getService('flashMessages.messages');

		Assert::true($factory instanceof IPub\FlashMessages\Components\IControl);
		Assert::true($factory->create() instanceof IPub\FlashMessages\Components\Control);
	}

	/**
	 * @return Nette\DI\Container
	 */
	protected function createContainer()
	{
		$config = new Nette\Configurator();
		$config->setTempDirectory(TEMP_DIR);

		FlashMessages\DI\FlashMessagesExtension::register($config);

		return $config->createContainer();
	}
}

\run(new ExtensionTest());

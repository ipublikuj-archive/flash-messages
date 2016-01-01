<?php
/**
 * Test: IPub\FlashMessages\Notifier
 * @testCase
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:FlashMessages!
 * @subpackage     Tests
 * @since          1.0.2
 *
 * @date           01.01.16
 */

namespace IPubTests\FlashMessages;

use Nette;
use Nette\Application;
use Nette\Application\UI;

use Tester;
use Tester\Assert;

use IPub;
use IPub\FlashMessages;
use IPub\FlashMessages\Entities;

require __DIR__ . '/../bootstrap.php';

class NotifierTest extends Tester\TestCase
{
	/**
	 * @var FlashMessages\FlashNotifier
	 */
	private $notifier;

	/**
	 * @var Nette\DI\Container
	 */
	private $container;

	/**
	 * Set up
	 */
	public function setUp()
	{
		parent::setUp();

		$this->container = $this->createContainer();

		// Get flash notifier from container
		$this->notifier = $this->container->getByType('IPub\FlashMessages\FlashNotifier');
	}

	public function testSkipTitle()
	{
		// Without title and with overlay
		$flash = $this->notifier->setMessage('Message text', Entities\IMessage::LEVEL_SUCCESS, TRUE, NULL);

		Assert::true($flash instanceof Entities\IMessage);
		Assert::same('Message text', $flash->getMessage());
		Assert::same(Entities\IMessage::LEVEL_SUCCESS, $flash->getLevel());
		Assert::same(NULL, $flash->getTitle());
		Assert::true($flash->getOverlay());

		// Without title and without overlay
		$flash = $this->notifier->setMessage('Message text', Entities\IMessage::LEVEL_SUCCESS, FALSE);

		Assert::true($flash instanceof Entities\IMessage);
		Assert::same('Message text', $flash->getMessage());
		Assert::same(Entities\IMessage::LEVEL_SUCCESS, $flash->getLevel());
		Assert::same(NULL, $flash->getTitle());
		Assert::false($flash->getOverlay());

		// Without title and without overlay
		$flash = $this->notifier->setMessage('Message text: %param%', Entities\IMessage::LEVEL_SUCCESS, ['param' => 'value']);

		Assert::true($flash instanceof Entities\IMessage);
		Assert::same('Message text: value', $flash->getMessage());
		Assert::same(Entities\IMessage::LEVEL_SUCCESS, $flash->getLevel());
		Assert::same(NULL, $flash->getTitle());
		Assert::false($flash->getOverlay());

		// Without title and without overlay and with params
		$flash = $this->notifier->setMessage('Message text: %param%', Entities\IMessage::LEVEL_SUCCESS, 5, ['param' => 'value']);

		Assert::true($flash instanceof Entities\IMessage);
		Assert::same('Message text: value', $flash->getMessage());
		Assert::same(Entities\IMessage::LEVEL_SUCCESS, $flash->getLevel());
		Assert::same(NULL, $flash->getTitle());
		Assert::false($flash->getOverlay());

		// Without title and with overlay and params
		$flash = $this->notifier->setMessage('Message text: %param%', Entities\IMessage::LEVEL_SUCCESS, TRUE, 5, ['param' => 'value']);

		Assert::true($flash instanceof Entities\IMessage);
		Assert::same('Message text: value', $flash->getMessage());
		Assert::same(Entities\IMessage::LEVEL_SUCCESS, $flash->getLevel());
		Assert::same(NULL, $flash->getTitle());
		Assert::true($flash->getOverlay());

		// Short call with info without title and with overlay and params
		$flash = $this->notifier->info('Message text: %param%', TRUE, 5, ['param' => 'value']);

		Assert::true($flash instanceof Entities\IMessage);
		Assert::same('Message text: value', $flash->getMessage());
		Assert::same(Entities\IMessage::LEVEL_INFO, $flash->getLevel());
		Assert::same(NULL, $flash->getTitle());
		Assert::true($flash->getOverlay());

		// With shortcut call with info without title and without overlay and params
		$flash = $this->notifier->info('Message text: %param%', ['param' => 'value']);

		Assert::true($flash instanceof Entities\IMessage);
		Assert::same('Message text: value', $flash->getMessage());
		Assert::same(Entities\IMessage::LEVEL_INFO, $flash->getLevel());
		Assert::same(NULL, $flash->getTitle());
		Assert::false($flash->getOverlay());
	}

	public function testSkipOverlay()
	{
		// Without overlay
		$flash = $this->notifier->setMessage('Message text', Entities\IMessage::LEVEL_SUCCESS, 'Message title');

		Assert::true($flash instanceof Entities\IMessage);
		Assert::same('Message text', $flash->getMessage());
		Assert::same(Entities\IMessage::LEVEL_SUCCESS, $flash->getLevel());
		Assert::same('Message title', $flash->getTitle());
		Assert::false($flash->getOverlay());

		// Without overlay with params
		$flash = $this->notifier->setMessage('Message text: %param%', Entities\IMessage::LEVEL_SUCCESS, 'Message title and %param%', ['param' => 'value']);

		Assert::true($flash instanceof Entities\IMessage);
		Assert::same('Message text: value', $flash->getMessage());
		Assert::same(Entities\IMessage::LEVEL_SUCCESS, $flash->getLevel());
		Assert::same('Message title and value', $flash->getTitle());
		Assert::false($flash->getOverlay());

		// Without overlay with params
		$flash = $this->notifier->setMessage('Message text: %param%', Entities\IMessage::LEVEL_SUCCESS, 'Message title and %param%', 5, ['param' => 'value']);

		Assert::true($flash instanceof Entities\IMessage);
		Assert::same('Message text: value', $flash->getMessage());
		Assert::same(Entities\IMessage::LEVEL_SUCCESS, $flash->getLevel());
		Assert::same('Message title and value', $flash->getTitle());
		Assert::false($flash->getOverlay());

		// Short call with info without title and with overlay and params
		$flash = $this->notifier->info('Message text: %param%', 'Message title and %param%', ['param' => 'value']);

		Assert::true($flash instanceof Entities\IMessage);
		Assert::same('Message text: value', $flash->getMessage());
		Assert::same(Entities\IMessage::LEVEL_INFO, $flash->getLevel());
		Assert::same('Message title and value', $flash->getTitle());
		Assert::false($flash->getOverlay());

		// Short call with info without title and with overlay and params
		$flash = $this->notifier->info('Message text: %param%', 'Message title and %param%', 5, ['param' => 'value']);

		Assert::true($flash instanceof Entities\IMessage);
		Assert::same('Message text: value', $flash->getMessage());
		Assert::same(Entities\IMessage::LEVEL_INFO, $flash->getLevel());
		Assert::same('Message title and value', $flash->getTitle());
		Assert::false($flash->getOverlay());
	}

	public function testOverlayShortCut()
	{
		// Short call with info with title and without params
		$flash = $this->notifier->overlay('Message text', 'Message title');

		Assert::true($flash instanceof Entities\IMessage);
		Assert::same('Message text', $flash->getMessage());
		Assert::same(Entities\IMessage::LEVEL_INFO, $flash->getLevel());
		Assert::same('Message title', $flash->getTitle());
		Assert::true($flash->getOverlay());

		// Short call with info without title and with level and without params
		$flash = $this->notifier->overlay('Message text', Entities\IMessage::LEVEL_ERROR);

		Assert::true($flash instanceof Entities\IMessage);
		Assert::same('Message text', $flash->getMessage());
		Assert::same(Entities\IMessage::LEVEL_ERROR, $flash->getLevel());
		Assert::same(NULL, $flash->getTitle());
		Assert::true($flash->getOverlay());

		// Short call with info without title and with level and with params
		$flash = $this->notifier->overlay('Message text: %param%', Entities\IMessage::LEVEL_ERROR, ['param' => 'value']);

		Assert::true($flash instanceof Entities\IMessage);
		Assert::same('Message text: value', $flash->getMessage());
		Assert::same(Entities\IMessage::LEVEL_ERROR, $flash->getLevel());
		Assert::same(NULL, $flash->getTitle());
		Assert::true($flash->getOverlay());

		// Short call with info with title and without level and with params
		$flash = $this->notifier->overlay('Message text: %param%', 'Message title and %param%', ['param' => 'value']);

		Assert::true($flash instanceof Entities\IMessage);
		Assert::same('Message text: value', $flash->getMessage());
		Assert::same(Entities\IMessage::LEVEL_INFO, $flash->getLevel());
		Assert::same('Message title and value', $flash->getTitle());
		Assert::true($flash->getOverlay());

		// Short call with info with title and without level and with params
		$flash = $this->notifier->overlay('Message text: %param%', 'Message title and %param%', 5, ['param' => 'value']);

		Assert::true($flash instanceof Entities\IMessage);
		Assert::same('Message text: value', $flash->getMessage());
		Assert::same(Entities\IMessage::LEVEL_INFO, $flash->getLevel());
		Assert::same('Message title and value', $flash->getTitle());
		Assert::true($flash->getOverlay());
	}

	/**
	 * @return Nette\DI\Container
	 */
	protected function createContainer()
	{
		$config = new Nette\Configurator();
		$config->setTempDirectory(TEMP_DIR);

		FlashMessages\DI\FlashMessagesExtension::register($config);

		$config->addConfig(__DIR__ . '/files/translator.neon', $config::NONE);

		return $config->createContainer();
	}
}

\run(new NotifierTest());

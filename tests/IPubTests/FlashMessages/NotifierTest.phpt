<?php
/**
 * Test: IPub\FlashMessages\Notifier
 * @testCase
 *
 * @copyright      More in license.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 * @package        iPublikuj:FlashMessages!
 * @subpackage     Tests
 * @since          1.0.2
 *
 * @date           01.01.16
 */

declare(strict_types = 1);

namespace IPubTests\FlashMessages;

use Nette;
use Nette\Application;
use Nette\Application\UI;

use Tester;
use Tester\Assert;

use IPub\FlashMessages;
use IPub\FlashMessages\Entities;

require __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'bootstrap.php';

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
	 * {@inheritdoc}
	 */
	public function setUp() : void
	{
		parent::setUp();

		$this->container = $this->createContainer();

		// Get flash notifier from container
		$this->notifier = $this->container->getByType(FlashMessages\FlashNotifier::class);
	}

	public function testSkipTitle() : void
	{
		// Without title and with overlay
		$flash = $this->notifier->setMessage('Message text', Entities\IMessage::LEVEL_SUCCESS, TRUE, NULL);

		Assert::true($flash instanceof Entities\IMessage);
		Assert::same('Message text', $flash->getMessage());
		Assert::same(Entities\IMessage::LEVEL_SUCCESS, $flash->getLevel());
		Assert::same(NULL, $flash->getTitle());
		Assert::true($flash->hasOverlay());

		// Without title and without overlay
		$flash = $this->notifier->setMessage('Message text', Entities\IMessage::LEVEL_SUCCESS, FALSE);

		Assert::true($flash instanceof Entities\IMessage);
		Assert::same('Message text', $flash->getMessage());
		Assert::same(Entities\IMessage::LEVEL_SUCCESS, $flash->getLevel());
		Assert::same(NULL, $flash->getTitle());
		Assert::false($flash->hasOverlay());

		// Without title and without overlay
		$flash = $this->notifier->setMessage('Message text: %param%', Entities\IMessage::LEVEL_SUCCESS, ['param' => 'value']);

		Assert::true($flash instanceof Entities\IMessage);
		Assert::same('Message text: value', $flash->getMessage());
		Assert::same(Entities\IMessage::LEVEL_SUCCESS, $flash->getLevel());
		Assert::same(NULL, $flash->getTitle());
		Assert::false($flash->hasOverlay());

		// Without title and without overlay and with params
		$flash = $this->notifier->setMessage('Message text: %param%', Entities\IMessage::LEVEL_SUCCESS, 5, ['param' => 'value']);

		Assert::true($flash instanceof Entities\IMessage);
		Assert::same('Message text: value', $flash->getMessage());
		Assert::same(Entities\IMessage::LEVEL_SUCCESS, $flash->getLevel());
		Assert::same(NULL, $flash->getTitle());
		Assert::false($flash->hasOverlay());

		// Without title and with overlay and params
		$flash = $this->notifier->setMessage('Message text: %param%', Entities\IMessage::LEVEL_SUCCESS, TRUE, 5, ['param' => 'value']);

		Assert::true($flash instanceof Entities\IMessage);
		Assert::same('Message text: value', $flash->getMessage());
		Assert::same(Entities\IMessage::LEVEL_SUCCESS, $flash->getLevel());
		Assert::same(NULL, $flash->getTitle());
		Assert::true($flash->hasOverlay());

		// Short call with info without title and with overlay and params
		$flash = $this->notifier->info('Message text: %param%', TRUE, 5, ['param' => 'value']);

		Assert::true($flash instanceof Entities\IMessage);
		Assert::same('Message text: value', $flash->getMessage());
		Assert::same(Entities\IMessage::LEVEL_INFO, $flash->getLevel());
		Assert::same(NULL, $flash->getTitle());
		Assert::true($flash->hasOverlay());

		// With shortcut call with info without title and without overlay and params
		$flash = $this->notifier->info('Message text: %param%', ['param' => 'value']);

		Assert::true($flash instanceof Entities\IMessage);
		Assert::same('Message text: value', $flash->getMessage());
		Assert::same(Entities\IMessage::LEVEL_INFO, $flash->getLevel());
		Assert::same(NULL, $flash->getTitle());
		Assert::false($flash->hasOverlay());
	}

	public function testSkipOverlay() : void
	{
		// Without overlay
		$flash = $this->notifier->setMessage('Message text', Entities\IMessage::LEVEL_SUCCESS, 'Message title');

		Assert::true($flash instanceof Entities\IMessage);
		Assert::same('Message text', $flash->getMessage());
		Assert::same(Entities\IMessage::LEVEL_SUCCESS, $flash->getLevel());
		Assert::same('Message title', $flash->getTitle());
		Assert::false($flash->hasOverlay());

		// Without overlay with params
		$flash = $this->notifier->setMessage('Message text: %param%', Entities\IMessage::LEVEL_SUCCESS, 'Message title and %param%', ['param' => 'value']);

		Assert::true($flash instanceof Entities\IMessage);
		Assert::same('Message text: value', $flash->getMessage());
		Assert::same(Entities\IMessage::LEVEL_SUCCESS, $flash->getLevel());
		Assert::same('Message title and value', $flash->getTitle());
		Assert::false($flash->hasOverlay());

		// Without overlay with params
		$flash = $this->notifier->setMessage('Message text: %param%', Entities\IMessage::LEVEL_SUCCESS, 'Message title and %param%', 5, ['param' => 'value']);

		Assert::true($flash instanceof Entities\IMessage);
		Assert::same('Message text: value', $flash->getMessage());
		Assert::same(Entities\IMessage::LEVEL_SUCCESS, $flash->getLevel());
		Assert::same('Message title and value', $flash->getTitle());
		Assert::false($flash->hasOverlay());

		// Short call with info without title and with overlay and params
		$flash = $this->notifier->info('Message text: %param%', 'Message title and %param%', ['param' => 'value']);

		Assert::true($flash instanceof Entities\IMessage);
		Assert::same('Message text: value', $flash->getMessage());
		Assert::same(Entities\IMessage::LEVEL_INFO, $flash->getLevel());
		Assert::same('Message title and value', $flash->getTitle());
		Assert::false($flash->hasOverlay());

		// Short call with info without title and with overlay and params
		$flash = $this->notifier->info('Message text: %param%', 'Message title and %param%', 5, ['param' => 'value']);

		Assert::true($flash instanceof Entities\IMessage);
		Assert::same('Message text: value', $flash->getMessage());
		Assert::same(Entities\IMessage::LEVEL_INFO, $flash->getLevel());
		Assert::same('Message title and value', $flash->getTitle());
		Assert::false($flash->hasOverlay());
	}

	public function testOverlayShortCut() : void
	{
		// Short call with info with title and without params
		$flash = $this->notifier->overlay('Message text', 'Message title');

		Assert::true($flash instanceof Entities\IMessage);
		Assert::same('Message text', $flash->getMessage());
		Assert::same(Entities\IMessage::LEVEL_INFO, $flash->getLevel());
		Assert::same('Message title', $flash->getTitle());
		Assert::true($flash->hasOverlay());

		// Short call with info without title and with level and without params
		$flash = $this->notifier->overlay('Message text', Entities\IMessage::LEVEL_ERROR);

		Assert::true($flash instanceof Entities\IMessage);
		Assert::same('Message text', $flash->getMessage());
		Assert::same(Entities\IMessage::LEVEL_ERROR, $flash->getLevel());
		Assert::same(NULL, $flash->getTitle());
		Assert::true($flash->hasOverlay());

		// Short call with info without title and with level and with params
		$flash = $this->notifier->overlay('Message text: %param%', Entities\IMessage::LEVEL_ERROR, ['param' => 'value']);

		Assert::true($flash instanceof Entities\IMessage);
		Assert::same('Message text: value', $flash->getMessage());
		Assert::same(Entities\IMessage::LEVEL_ERROR, $flash->getLevel());
		Assert::same(NULL, $flash->getTitle());
		Assert::true($flash->hasOverlay());

		// Short call with info with title and without level and with params
		$flash = $this->notifier->overlay('Message text: %param%', 'Message title and %param%', ['param' => 'value']);

		Assert::true($flash instanceof Entities\IMessage);
		Assert::same('Message text: value', $flash->getMessage());
		Assert::same(Entities\IMessage::LEVEL_INFO, $flash->getLevel());
		Assert::same('Message title and value', $flash->getTitle());
		Assert::true($flash->hasOverlay());

		// Short call with info with title and without level and with params
		$flash = $this->notifier->overlay('Message text: %param%', 'Message title and %param%', 5, ['param' => 'value']);

		Assert::true($flash instanceof Entities\IMessage);
		Assert::same('Message text: value', $flash->getMessage());
		Assert::same(Entities\IMessage::LEVEL_INFO, $flash->getLevel());
		Assert::same('Message title and value', $flash->getTitle());
		Assert::true($flash->hasOverlay());
	}

	/**
	 * @return Nette\DI\Container
	 */
	protected function createContainer() : Nette\DI\Container
	{
		$config = new Nette\Configurator();
		$config->setTempDirectory(TEMP_DIR);

		FlashMessages\DI\FlashMessagesExtension::register($config);

		$config->addConfig(__DIR__ . DS . 'files' . DS . 'translator.neon');

		return $config->createContainer();
	}
}

\run(new NotifierTest());

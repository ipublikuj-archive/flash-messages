<?php
/**
 * Test: IPub\FlashMessages\Storage
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

declare(strict_types = 1);

namespace IPubTests\FlashMessages;

use Nette;
use Nette\Application;
use Nette\Application\UI;

use Tester;
use Tester\Assert;

use IPub\FlashMessages;

require __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'bootstrap.php';

class StorageTest extends Tester\TestCase
{
	/**
	 * @var Application\IPresenterFactory
	 */
	private $presenterFactory;

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

		// Get presenter factory from container
		$this->presenterFactory = $this->container->getByType('Nette\Application\IPresenterFactory');
	}

	public function testStoreMessage() : void
	{
		// Create container
		$container = $this->createContainer();

		// Get notifier from container
		/** @var FlashMessages\FlashNotifier $notifier */
		$notifier = $container->getByType(FlashMessages\FlashNotifier::class);

		// Set first message
		$notifier->message('Stored message', 'success');

		// Get session storage from container
		/** @var FlashMessages\Storage\Session $storage */
		$storage = $container->getByType(FlashMessages\Storage\Session::class);

		Assert::equal(1, count($storage->get(FlashMessages\Storage\Session::KEY_MESSAGES, [])));

		// Create container
		$container = $this->createContainer();

		// Get notifier from container
		/** @var FlashMessages\FlashNotifier $notifier */
		$notifier = $container->getByType(FlashMessages\FlashNotifier::class);

		// Set second message
		$notifier->message('Second stored message', 'success');

		// Get session storage from container
		/** @var FlashMessages\Storage\Session $storage */
		$storage = $container->getByType(FlashMessages\Storage\Session::class);

		Assert::equal(2, count($storage->get(FlashMessages\Storage\Session::KEY_MESSAGES, [])));

		// Create container
		$container = $this->createContainer();

		// Get session storage from container
		/** @var FlashMessages\Storage\Session $storage */
		$storage = $container->getByType(FlashMessages\Storage\Session::class);

		/** @var FlashMessages\Entities\IMessage[] $messages */
		$messages = $storage->get(FlashMessages\Storage\Session::KEY_MESSAGES, []);
		$messages[0]->setDisplayed();

		Assert::equal(2, count($storage->get(FlashMessages\Storage\Session::KEY_MESSAGES, [])));

		// Get event from container
		/** @var FlashMessages\Events\OnResponseHandler $event */
		$event = $container->getByType(FlashMessages\Events\OnResponseHandler::class);

		$event->__invoke();

		Assert::equal(1, count($storage->get(FlashMessages\Storage\Session::KEY_MESSAGES, [])));
	}

	/**
	 * @return Application\IPresenter
	 */
	protected function createPresenter() : Application\IPresenter
	{
		// Create test presenter
		$presenter = $this->presenterFactory->createPresenter('StorageTest');
		// Disable auto canonicalize to prevent redirection
		$presenter->autoCanonicalize = FALSE;

		return $presenter;
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

class StorageTestPresenter extends UI\Presenter
{
	/**
	 * Implement flash messages
	 */
	use FlashMessages\TFlashMessages;

	public function renderShowMessage() : void
	{
		// Set template for component testing
		$this->template->setFile(__DIR__ . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'show.latte');
	}

	/**
	 * Create confirmation dialog
	 *
	 * @return FlashMessages\Components\Control
	 */
	protected function createComponentFlashMessages() : FlashMessages\Components\Control
	{
		// Init confirmation dialog
		$control = $this->flashMessagesFactory->create();

		return $control;
	}
}

\run(new StorageTest());

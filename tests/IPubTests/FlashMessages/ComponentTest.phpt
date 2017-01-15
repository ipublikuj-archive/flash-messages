<?php
/**
 * Test: IPub\FlashMessages\Compiler
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
use Nette\Application;
use Nette\Application\UI;
use Nette\Utils;

use Tester;
use Tester\Assert;

use IPub;
use IPub\FlashMessages;

require __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'bootstrap.php';

class ComponentTest extends Tester\TestCase
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
	 * @return array
	 */
	public function dataMessagesValues() : array
	{
		return [
			['This is success message', FlashMessages\Entities\Message::LEVEL_SUCCESS, 'Success message title', FALSE, 0, []],
			['This is success info', FlashMessages\Entities\Message::LEVEL_INFO, NULL, FALSE, 0, []],
			['This is success warning', FlashMessages\Entities\Message::LEVEL_WARNING, 'Warning message title', FALSE, 0, []],
			['This is success error', FlashMessages\Entities\Message::LEVEL_ERROR, 'Error message title', TRUE, 2, []],
			['Message with less params', FlashMessages\Entities\Message::LEVEL_INFO],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function setUp()
	{
		parent::setUp();

		$this->container = $this->createContainer();

		// Get presenter factory from container
		$this->presenterFactory = $this->container->getByType('Nette\Application\IPresenterFactory');
	}

	public function testSetValidTemplate()
	{
		// Create test presenter
		$presenter = $this->createPresenter();

		// Create GET request
		$request = new Application\Request('Test', 'GET', ['action' => 'validTemplate']);
		// & fire presenter & catch response
		$response = $presenter->run($request);

		$dq = Tester\DomQuery::fromHtml((string) $response->getSource());

		Assert::true($dq->has('div[class*="ipub-flash-messages"]'));
	}

	/**
	 * @throws \IPub\FlashMessages\Exceptions\FileNotFoundException
	 */
	public function testSetInvalidTemplate()
	{
		// Create test presenter
		$presenter = $this->createPresenter();

		// Create GET request
		$request = new Application\Request('Test', 'GET', ['action' => 'invalidTemplate']);
		// & fire presenter & catch response
		$presenter->run($request);
	}

	/**
	 * @dataProvider dataMessagesValues
	 *
	 * @param string $message
	 * @param string $level
	 * @param string $title
	 * @param bool $overlay
	 * @param int $count
	 * @param array $parameters
	 */
	public function testCreateMessage(string $message, string $level, string $title = NULL, bool $overlay = FALSE, int $count = 0, array $parameters = [])
	{
		// Get notifier service
		/** @var $notifier IPub\FlashMessages\FlashNotifier */
		$notifier = $this->container->getService('flashMessages.notifier');

		$flash = $notifier->message($message, $level, $title, $overlay, $count, $parameters);
		$flash->setMessage($message);

		Assert::true($flash instanceof FlashMessages\Entities\Message);
		Assert::same($message, $flash->getMessage());
		Assert::same($level, $flash->getLevel());
		Assert::same($title, $flash->getTitle());
		Assert::same($overlay, $flash->hasOverlay());
	}

	public function testRenderMessage()
	{
		// Create test presenter
		$presenter = $this->createPresenter();

		// Create GET request
		$request = new Application\Request('Test', 'GET', ['action' => 'showMessage']);
		// & fire presenter & catch response
		$response = $presenter->run($request);

		$dq = Tester\DomQuery::fromHtml((string) $response->getSource());

		Assert::true($dq->has('div[class*="ipub-flash-messages"]'));

		$messageElement = $dq->find('div[class*="alert"]');

		Assert::equal('success', (string) $messageElement[0]->attributes()->{'data-status'});

		$messageContentElement = $dq->find('div[class*="alert"] p');

		Assert::equal('Message to display', (string) $messageContentElement[0]);
	}

	public function testRenderMessageWithTitle()
	{
		// Create test presenter
		$presenter = $this->createPresenter();

		// Create GET request
		$request = new Application\Request('Test', 'GET', ['action' => 'showMessageWithTitle']);
		// & fire presenter & catch response
		$response = $presenter->run($request);

		$dq = Tester\DomQuery::fromHtml((string) $response->getSource());

		Assert::true($dq->has('div[class*="ipub-flash-messages"]'));

		$messageElement = $dq->find('div[class*="alert"]');

		Assert::equal('success', (string) $messageElement[0]->attributes()->{'data-status'});

		$messageContentElement = $dq->find('div[class*="alert"] p');

		Assert::equal('Message to display', (string) $messageContentElement[0]);

		$messageTitleElement = $dq->find('h2');

		Assert::equal('Message title', (string) $messageTitleElement[0]);
	}

	public function testRenderMessageWithoutTitle()
	{
		// Create test presenter
		$presenter = $this->createPresenter();

		// Create GET request
		$request = new Application\Request('Test', 'GET', ['action' => 'showMessageWithoutTitle']);
		// & fire presenter & catch response
		$response = $presenter->run($request);

		$dq = Tester\DomQuery::fromHtml((string) $response->getSource());

		Assert::true($dq->has('div[class*="ipub-flash-messages"]'));

		$messageElement = $dq->find('div[class*="alert"]');

		Assert::equal('success', (string) $messageElement[0]->attributes()->{'data-status'});

		$messageContentElement = $dq->find('div[class*="alert"] p');

		Assert::equal('Message to display', (string) $messageContentElement[0]);

		Assert::false($dq->has('h2'));
	}

	public function testRenderMessageWithOverlay()
	{
		// Create test presenter
		$presenter = $this->createPresenter();

		// Create GET request
		$request = new Application\Request('Test', 'GET', ['action' => 'showMessageWithOverlay']);
		// & fire presenter & catch response
		$response = $presenter->run($request);

		$dq = Tester\DomQuery::fromHtml((string) $response->getSource());

		Assert::true($dq->has('div[class*="ipub-flash-messages"]'));
		Assert::true($dq->has('div[class*="overlay"]'));

		$modalElement = $dq->find('div[class*="alert"]');

		Assert::equal('success', (string) $modalElement[0]->attributes()->{'data-status'});

		$messageContentElement = $dq->find('div[class*="modal-body"] p');

		Assert::equal('Message to display', (string) $messageContentElement[0]);

		$messageTitleElement = $dq->find('h3[class*="modal-title"]');

		Assert::equal('Message title', (string) $messageTitleElement[0]);
	}

	public function testRenderMessageWithoutOverlay()
	{
		// Create test presenter
		$presenter = $this->createPresenter();

		// Create GET request
		$request = new Application\Request('Test', 'GET', ['action' => 'showMessageWithoutOverlay']);
		// & fire presenter & catch response
		$response = $presenter->run($request);

		$dq = Tester\DomQuery::fromHtml((string) $response->getSource());

		Assert::true($dq->has('div[class*="ipub-flash-messages"]'));
		Assert::false($dq->has('div[class*="overlay"]'));
	}

	/**
	 * @return Application\IPresenter
	 */
	protected function createPresenter() : Application\IPresenter
	{
		// Create test presenter
		$presenter = $this->presenterFactory->createPresenter('Test');
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

		$config->addConfig(__DIR__ . DS . 'files' . DS . 'presenters.neon');

		return $config->createContainer();
	}
}

class TestPresenter extends UI\Presenter
{
	/**
	 * Implement flash messages
	 */
	use FlashMessages\TFlashMessages;

	public function actionValidTemplate()
	{
		// Set valid template name
		$this['flashMessages']->setTemplateFile('bootstrap.latte');
	}

	public function actionInvalidTemplate()
	{
		// Set invalid template name
		$this['flashMessages']->setTemplateFile('invalid.latte');
	}

	public function actionShowMessage()
	{
		$this->flashMessage('Message to display', 'success');
	}

	public function actionShowMessageWithTitle()
	{
		$this->flashMessage('Message to display', 'success', 'Message title');
	}

	public function actionShowMessageWithoutTitle()
	{
		$this->flashMessage('Message to display', 'success', 'Message title');
	}

	public function actionShowMessageWithOverlay()
	{
		$this->flashMessage('Message to display', 'success', 'Message title', TRUE);
	}

	public function actionShowMessageWithoutOverlay()
	{
		$this->flashMessage('Message to display', 'success', 'Message title', TRUE);
	}

	public function renderValidTemplate()
	{
		// Set template for component testing
		$this->template->setFile(__DIR__ . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'validTemplate.latte');
	}

	public function renderShowMessage()
	{
		// Set template for component testing
		$this->template->setFile(__DIR__ . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'show.latte');
	}

	public function renderShowMessageWithTitle()
	{
		// Set template for component testing
		$this->template->setFile(__DIR__ . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'show.latte');

		// Globaly enable titles
		$this['flashMessages']->enableTitle();
	}

	public function renderShowMessageWithoutTitle()
	{
		// Set template for component testing
		$this->template->setFile(__DIR__ . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'show.latte');

		// Globaly enable titles
		$this['flashMessages']->disableTitle();
	}

	public function renderShowMessageWithOverlay()
	{
		// Set template for component testing
		$this->template->setFile(__DIR__ . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'show.latte');

		// Globaly enable titles
		$this['flashMessages']->enableOverlay();
	}

	public function renderShowMessageWithoutOverlay()
	{
		// Set template for component testing
		$this->template->setFile(__DIR__ . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'show.latte');

		// Globaly enable titles
		$this['flashMessages']->disableOverlay();
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

\run(new ComponentTest());

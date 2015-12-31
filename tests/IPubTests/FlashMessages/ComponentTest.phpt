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

namespace IPubTests\FlashMessages;

use Nette;
use Nette\Application;
use Nette\Application\UI;
use Nette\Utils;

use Tester;
use Tester\Assert;

use IPub;
use IPub\FlashMessages;

require __DIR__ . '/../bootstrap.php';

class ComponentTest extends Tester\TestCase
{
	/**
	 * @var Nette\Application\IPresenterFactory
	 */
	private $presenterFactory;

	/**
	 * @var Nette\DI\Container
	 */
	private $container;

	public function dataMessagesValues()
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
	 * Set up
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
	public function testCreateMessage($message, $level, $title = NULL, $overlay = FALSE, $count = 0, $parameters = [])
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
		Assert::same($overlay, $flash->getOverlay());
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

	/**
	 * @return Application\IPresenter
	 */
	protected function createPresenter()
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
	protected function createContainer()
	{
		$config = new Nette\Configurator();
		$config->setTempDirectory(TEMP_DIR);

		FlashMessages\DI\FlashMessagesExtension::register($config);

		$config->addConfig(__DIR__ . '/files/presenters.neon', $config::NONE);

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

	/**
	 * Create confirmation dialog
	 *
	 * @return FlashMessages\Components\Control
	 */
	protected function createComponentFlashMessages()
	{
		// Init confirmation dialog
		$control = $this->flashMessagesFactory->create();

		return $control;
	}
}

\run(new ComponentTest());

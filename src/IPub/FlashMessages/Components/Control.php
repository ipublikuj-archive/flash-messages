<?php
/**
 * Component.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:FlashMessages!
 * @subpackage     Components
 * @since          1.0.0
 *
 * @date           12.03.14
 */

declare(strict_types = 1);

namespace IPub\FlashMessages\Components;

use Nette;
use Nette\Application;
use Nette\Localization;
use Nette\Utils;

use IPub;
use IPub\FlashMessages;
use IPub\FlashMessages\Entities;
use IPub\FlashMessages\Exceptions;
use IPub\FlashMessages\Storage;

/**
 * Flash messages control
 *
 * @package        iPublikuj:FlashMessages!
 * @subpackage     Components
 *
 * @author         Adam Kadlec http://www.ipublikuj.eu
 *
 * @property Application\UI\ITemplate $template
 */
class Control extends Application\UI\Control
{
	/**
	 * @var string
	 */
	private $templateFile;

	/**
	 * @var Storage\IStorage
	 */
	private $storage;

	/**
	 * @var Localization\ITranslator
	 */
	private $translator;

	/**
	 * @var bool
	 */
	private $useTitle = FALSE;

	/**
	 * @var bool
	 */
	private $useOverlay = FALSE;

	/**
	 * @param Localization\ITranslator $translator
	 */
	public function injectTranslator(Localization\ITranslator $translator = NULL)
	{
		$this->translator = $translator;
	}

	/**
	 * @param NULL|string $templateFile
	 * @param Storage\IStorage $storage
	 *
	 * @throws Exceptions\FileNotFoundException
	 */
	public function __construct(
		$templateFile = NULL,
		Storage\IStorage $storage
	) {
		parent::__construct();

		if ($templateFile !== NULL) {
			$this->setTemplateFile($templateFile);
		}

		$this->storage = $storage;
	}

	/**
	 * @param \Nette\ComponentModel\IComponent
	 */
	public function attached($presenter)
	{
		parent::attached($presenter);

		$this->redrawControl();
	}

	/**
	 * @return void
	 */
	public function enableTitle()
	{
		$this->useTitle = TRUE;
	}

	/**
	 * @return void
	 */
	public function disableTitle()
	{
		$this->useTitle = FALSE;
	}

	/**
	 * @return void
	 */
	public function enableOverlay()
	{
		$this->useOverlay = TRUE;
	}

	/**
	 * @return void
	 */
	public function disableOverlay()
	{
		$this->useOverlay = FALSE;
	}

	/**
	 * Prepare component for rendering
	 */
	public function beforeRender()
	{
		// Load messages from session
		/** @var Entities\IMessage[] $messages */
		$messages = $this->storage->get(Storage\IStorage::KEY_MESSAGES, []);

		// Assign vars to template
		$this->template->flashes = $messages ? $messages : [];
		$this->template->useTitle = $this->useTitle;
		$this->template->useOverlay = $this->useOverlay;

		// Check if translator is available
		if ($this->getTranslator() instanceof Localization\ITranslator) {
			$this->template->setTranslator($this->getTranslator());
		}

		// If template was not defined before...
		if ($this->template->getFile() === NULL) {
			// ...try to get base component template file
			$templateFile = !empty($this->templateFile) ? $this->templateFile : __DIR__ . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR . 'default' . DIRECTORY_SEPARATOR . 'default.latte';
			$this->template->setFile($templateFile);
		}
	}

	/**
	 * Render control
	 */
	public function render()
	{
		// Check if control has template
		if ($this->template instanceof Nette\Bridges\ApplicationLatte\Template) {
			$this->beforeRender();

			// Render component template
			$this->template->render();

		} else {
			throw new Exceptions\InvalidStateException('Flash messages control is without template.');
		}
	}

	/**
	 * Change default control template path
	 *
	 * @param string $templateFile
	 *
	 * @return void
	 *
	 * @throws Exceptions\FileNotFoundException
	 */
	public function setTemplateFile($templateFile)
	{
		// Check if template file exists...
		if (!is_file($templateFile)) {
			// Get component actual dir
			$dir = dirname($this->getReflection()->getFileName());

			$templateName = preg_replace('/.latte/', '', $templateFile);

			// ...check if extension template is used
			if (is_file($dir . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR . $templateName . '.latte')) {
				$templateFile = $dir . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR . $templateName . '.latte';

			} else {
				// ...if not throw exception
				throw new Exceptions\FileNotFoundException(sprintf('Template file "%s" was not found.', $templateFile));
			}
		}

		$this->templateFile = $templateFile;
	}

	/**
	 * @param Localization\ITranslator $translator
	 *
	 * @return void
	 */
	public function setTranslator(Localization\ITranslator $translator)
	{
		$this->translator = $translator;
	}

	/**
	 * @return Localization\ITranslator|null
	 */
	public function getTranslator()
	{
		if ($this->translator instanceof Localization\ITranslator) {
			return $this->translator;
		}

		return NULL;
	}
}

<?php
/**
 * Component.php
 *
 * @copyright      More in license.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 * @package        iPublikuj:FlashMessages!
 * @subpackage     Components
 * @since          1.0.0
 *
 * @date           12.03.14
 */

declare(strict_types = 1);

namespace IPub\FlashMessages\Components;

use Nette\Application;
use Nette\Bridges;
use Nette\ComponentModel;
use Nette\Localization;

use IPub\FlashMessages\Entities;
use IPub\FlashMessages\Exceptions;
use IPub\FlashMessages\Storage;

/**
 * Flash messages control
 *
 * @package        iPublikuj:FlashMessages!
 * @subpackage     Components
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
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
	 * @var Localization\ITranslator|NULL
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
	 * @param Localization\ITranslator|NULL $translator
	 *
	 * @return void
	 */
	public function injectTranslator(?Localization\ITranslator $translator = NULL) : void
	{
		$this->translator = $translator;
	}

	/**
	 * @param string|NULL $templateFile
	 * @param Storage\IStorage $storage
	 *
	 * @throws Exceptions\FileNotFoundException
	 */
	public function __construct(
		string $templateFile = NULL,
		Storage\IStorage $storage
	) {
		parent::__construct();

		if ($templateFile !== NULL) {
			$this->setTemplateFile($templateFile);
		}

		$this->storage = $storage;
	}

	/**
	 * @param ComponentModel\IComponent
	 *
	 * @return void
	 */
	public function attached($presenter) : void
	{
		parent::attached($presenter);

		$this->redrawControl();
	}

	/**
	 * @return void
	 */
	public function enableTitle() : void
	{
		$this->useTitle = TRUE;
	}

	/**
	 * @return void
	 */
	public function disableTitle() : void
	{
		$this->useTitle = FALSE;
	}

	/**
	 * @return void
	 */
	public function enableOverlay() : void
	{
		$this->useOverlay = TRUE;
	}

	/**
	 * @return void
	 */
	public function disableOverlay() : void
	{
		$this->useOverlay = FALSE;
	}

	/**
	 * Prepare component for rendering
	 *
	 * @return void
	 */
	public function beforeRender() : void
	{
		// Check if control has template
		if ($this->template instanceof Bridges\ApplicationLatte\Template) {
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
	}

	/**
	 * Render control
	 *
	 * @return void
	 */
	public function render() : void
	{
		// Check if control has template
		if ($this->template instanceof Bridges\ApplicationLatte\Template) {
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
	public function setTemplateFile(string $templateFile) : void
	{
		// Check if template file exists...
		if (!is_file($templateFile)) {
			// Get component actual dir
			$dir = dirname($this->getReflection()->getFileName());

			$templateName = preg_replace('/.latte/', '', $templateFile);

			// ...check if extension template is used
			if (is_file($dir . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR . $templateName . DIRECTORY_SEPARATOR . 'default.latte')) {
				$templateFile = $dir . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR . $templateName . DIRECTORY_SEPARATOR . 'default.latte';

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
	public function setTranslator(Localization\ITranslator $translator) : void
	{
		$this->translator = $translator;
	}

	/**
	 * @return Localization\ITranslator|NULL
	 */
	public function getTranslator() : ?Localization\ITranslator
	{
		if ($this->translator instanceof Localization\ITranslator) {
			return $this->translator;
		}

		return NULL;
	}
}

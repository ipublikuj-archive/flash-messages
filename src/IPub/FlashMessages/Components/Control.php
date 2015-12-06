<?php
/**
 * Control.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:FlashMessages!
 * @subpackage	Components
 * @since		5.0
 *
 * @date		12.03.14
 */

namespace IPub\FlashMessages\Components;

use Nette;
use Nette\Application;
use Nette\Localization;
use Nette\Utils;

use IPub;
use IPub\FlashMessages;
use IPub\FlashMessages\Exceptions;

/**
 * Flash messages control
 *
 * @package		iPublikuj:FlashMessages!
 * @subpackage	Components
 *
 * @property Application\UI\ITemplate $template
 */
class Control extends Application\UI\Control
{
	/**
	 * @var string
	 */
	protected $templateFile;

	/**
	 * @var FlashMessages\SessionStorage
	 */
	protected $sessionStorage;

	/**
	 * @var Localization\ITranslator
	 */
	protected $translator;

	/**
	 * @var bool
	 */
	protected $useTitle = FALSE;

	/**
	 * @var bool
	 */
	protected $useOverlay = FALSE;

	/**
	 * @param Localization\ITranslator $translator
	 */
	public function injectTranslator(Localization\ITranslator $translator = NULL)
	{
		$this->translator = $translator;
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
	 * @param NULL|string $templateFile
	 * @param FlashMessages\SessionStorage $sessionStorage
	 * @param Nette\ComponentModel\IContainer $parent
	 * @param null $name
	 *
	 * @throws Exceptions\FileNotFoundException
	 */
	public function __construct(
		$templateFile = NULL,
		FlashMessages\SessionStorage $sessionStorage,
		Nette\ComponentModel\IContainer $parent = NULL, $name = NULL
	) {
		// TODO: remove, only for tests
		parent::__construct(NULL, NULL);

		if ($templateFile) {
			$this->setTemplateFile($templateFile);
		}

		$this->sessionStorage = $sessionStorage;
	}

	/**
	 * @return $this
	 */
	public function enableTitle()
	{
		$this->useTitle = TRUE;

		return $this;
	}

	/**
	 * @return $this
	 */
	public function disableTitle()
	{
		$this->useTitle = FALSE;

		return $this;
	}

	/**
	 * @return $this
	 */
	public function enableOverlay()
	{
		$this->useOverlay = TRUE;

		return $this;
	}

	/**
	 * @return $this
	 */
	public function disableOverlay()
	{
		$this->useOverlay = FALSE;

		return $this;
	}

	/**
	 * Render control
	 */
	public function render()
	{
		// Check if control has template
		if ($this->template instanceof Nette\Bridges\ApplicationLatte\Template) {
			// Load messages from session
			$messages = $this->sessionStorage->get(FlashMessages\SessionStorage::KEY_MESSAGES);

			// Assign vars to template
			$this->template->flashes	= $messages ? $messages : [];
			$this->template->useTitle	= $this->useTitle;
			$this->template->useOverlay	= $this->useOverlay;

			// Check if translator is available
			if ($this->getTranslator() instanceof Localization\ITranslator) {
				$this->template->setTranslator($this->getTranslator());
			}

			// If template was not defined before...
			if ($this->template->getFile() === NULL) {
				// ...try to get base component template file
				$templateFile = !empty($this->templateFile) ? $this->templateFile : __DIR__ . DIRECTORY_SEPARATOR .'template'. DIRECTORY_SEPARATOR .'default'. DIRECTORY_SEPARATOR .'default.latte';
				$this->template->setFile($templateFile);
			}

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
	 * @return $this
	 *
	 * @throws Exceptions\FileNotFoundException
	 */
	public function setTemplateFile($templateFile)
	{
		// Check if template file exists...
		if (!is_file($templateFile)) {
			// Remove extension
			$template = basename($templateFile, '.latte');

			// ...check if extension template is used
			if (is_file(__DIR__ . DIRECTORY_SEPARATOR .'template'. DIRECTORY_SEPARATOR . $template . DIRECTORY_SEPARATOR .'default.latte')) {
				$templateFile = __DIR__ . DIRECTORY_SEPARATOR .'template'. DIRECTORY_SEPARATOR . $template . DIRECTORY_SEPARATOR .'default.latte';

			} else {
				// ...if not throw exception
				throw new Exceptions\FileNotFoundException('Template file "'. $templateFile .'" was not found.');
			}
		}

		$this->templateFile = $templateFile;

		return $this;
	}

	/**
	 * @param Localization\ITranslator $translator
	 *
	 * @return $this
	 */
	public function setTranslator(Localization\ITranslator $translator)
	{
		$this->translator = $translator;

		return $this;
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
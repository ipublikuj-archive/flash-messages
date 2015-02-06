<?php
/**
 * TFlashMessages.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:FlashMessages!
 * @subpackage	common
 * @since		5.0
 *
 * @date		01.02.15
 */

namespace IPub\FlashMessages;

use Nette;
use Nette\Localization;

use Kdyby;
use Kdyby\Translation;

use IPub;
use IPub\FlashMessages\Adapters;
use IPub\FlashMessages\Entities;

class FlashNotifier extends Nette\Object
{
	/**
	 * @var SessionStorage
	 */
	protected $sessionStorage;

	/**
	 * @var Localization\ITranslator
	 */
	protected $translator;

	/**
	 * @param SessionStorage $sessionStorage
	 * @param Localization\ITranslator $translator
	 */
	public function __construct(SessionStorage $sessionStorage, Localization\ITranslator $translator = NULL)
	{
		$this->sessionStorage = $sessionStorage;
		$this->translator = $translator;
	}

	/**
	 * Flash a success message
	 *
	 * @param string $message
	 * @param string|null $title
	 *
	 * @return $this
	 */
	public function success($message, $title = NULL)
	{
		$this->message($message, 'success', $title);

		return $this;
	}

	/**
	 * Flash an information message
	 *
	 * @param string $message
	 * @param string|null $title
	 *
	 * @return $this
	 */
	public function info($message, $title = NULL)
	{
		$this->message($message, 'info', $title);

		return $this;
	}

	/**
	 * Flash a warning message
	 *
	 * @param string $message
	 * @param string|null $title
	 *
	 * @return $this
	 */
	public function warning($message, $title = NULL)
	{
		$this->message($message, 'warning', $title);

		return $this;
	}

	/**
	 * Flash an error message
	 *
	 * @param string $message
	 * @param string|null $title
	 *
	 * @return $this
	 */
	public function error($message, $title = NULL)
	{
		$this->message($message, 'danger', $title);

		return $this;
	}

	/**
	 * Add an "important" flash to the session
	 *
	 * @return $this
	 */
	public function important()
	{
		$this->sessionStorage->set(SessionStorage::KEY_IMPORTANT, TRUE);

		return $this;
	}

	/**
	 * Flash an overlay modal
	 *
	 * @param string $message
	 * @param string $title
	 *
	 * @return $this
	 */
	public function overlay($message, $title = 'Notice')
	{
		$this->message($message, 'info', $title, TRUE);

		return $this;
	}

	/**
	 * Flash a general message
	 *
	 * @param string $message
	 * @param string $level
	 * @param string $title
	 * @param boolean $overlay
	 * @param int|null $count
	 * @param array|null $parameters
	 *
	 * @return Entities\IMessage
	 */
	public function message($message, $level = 'info', $title = 'Notice', $overlay = FALSE, $count = NULL, $parameters = [])
	{
		// Support for Kdyby/Translation
		if ($message instanceof Translation\Phrase) {
			$message = new Adapters\KdybyPhraseAdapter($message);

		// Default phrase adapter
		} elseif (!$message instanceof Adapters\IPhraseAdapter) {
			$message = new Adapters\DefaultPhraseAdapter($message, $count, $parameters);
		}

		// Get all stored messages
		$messages = $this->sessionStorage->get(SessionStorage::KEY_MESSAGES);

		// Create flash message
		$messages[] = $flash = (new Entities\Message($this->translator, $message))
			->setLevel($level)
			->setTitle($title)
			->setOverlay($overlay);

		// Store messages in session
		$this->sessionStorage->set(SessionStorage::KEY_MESSAGES, $messages);

		return $flash;
	}
}
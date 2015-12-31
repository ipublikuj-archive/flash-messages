<?php
/**
 * TFlashMessages.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:FlashMessages!
 * @subpackage     common
 * @since          1.0.0
 *
 * @date           01.02.15
 */

namespace IPub\FlashMessages;

use Nette;
use Nette\Localization;

use Kdyby;
use Kdyby\Translation;

use IPub;
use IPub\FlashMessages\Adapters;
use IPub\FlashMessages\Entities;

/**
 * Flash message notifier
 *
 * @package        iPublikuj:FlashMessages!
 * @subpackage     common
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
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
	 * @param array $parameters
	 *
	 * @return Entities\IMessage
	 */
	public function message($message, $level = 'info', $title = 'Notice', $overlay = FALSE, $count = NULL, array $parameters = [])
	{
		$title = $this->checkForAttribute([$title, $overlay, $count, $parameters], 'title');
		$overlay = $this->checkForAttribute([$title, $overlay, $count, $parameters], 'overlay');
		$count = $this->checkForAttribute([$title, $overlay, $count, $parameters], 'count');
		$parameters = $this->checkForAttribute([$title, $overlay, $count, $parameters], 'parameters');

		// Support for Kdyby/Translation
		if ($message instanceof Translation\Phrase) {
			$phrase = new Adapters\KdybyPhraseAdapter($message);

			// Default phrase adapter
		} else if (!$message instanceof Adapters\IPhraseAdapter) {
			$phrase = new Adapters\DefaultPhraseAdapter($message, $count, $parameters);
		}

		// Support for Kdyby/Translation
		if ($title instanceof Translation\Phrase) {
			$titlePhrase = new Adapters\KdybyPhraseAdapter($title);

		// Default phrase adapter
		} else if (!$title instanceof Adapters\IPhraseAdapter && $title !== NULL) {
			$titlePhrase = new Adapters\DefaultPhraseAdapter($title, $count, $parameters);

		} else {
			$titlePhrase = NULL;
		}

		// Get all stored messages
		$messages = $this->sessionStorage->get(SessionStorage::KEY_MESSAGES, []);

		// Create flash message
		$flash = (new Entities\Message($this->translator, $phrase, $titlePhrase))
			->setLevel($level)
			->setOverlay($overlay);

		if (!$this->translator instanceof Localization\ITranslator) {
			$flash->setMessage($message);
			$flash->setTitle($title);
		}

		if ($this->checkUnique($flash, $messages) === FALSE) {
			$messages[] = $flash;
		}

		// Store messages in session
		$this->sessionStorage->set(SessionStorage::KEY_MESSAGES, $messages);

		return $flash;
	}

	/**
	 * @param Entities\IMessage $flash
	 * @param Entities\IMessage[] $messages
	 *
	 * @return bool
	 */
	private function checkUnique(Entities\IMessage $flash, array $messages)
	{
		foreach ($messages as $member) {
			if ((string) $member === (string) $flash) {
				return TRUE;
			}
		}

		return FALSE;
	}

	/**
	 * @param array $attributes
	 * @param string $type
	 *
	 * @return mixed
	 */
	private function checkForAttribute(array $attributes, $type)
	{
		foreach($attributes as $attribute) {
			switch($type)
			{
				case 'title':
					if (is_string($attribute) === TRUE || $attribute instanceof Translation\Phrase || $attribute instanceof Adapters\IPhraseAdapter) {
						return $attribute;
					}
					break;

				case 'overlay':
					if (is_bool($attribute) === TRUE) {
						return $attribute;
					}
					break;

				case 'count':
					if (is_numeric($attribute) === TRUE) {
						return $attribute;
					}
					break;

				case 'parameters':
					if (is_array($attribute) === TRUE) {
						return $attribute;
					}
					break;
			}
		}

		return NULL;
	}
}

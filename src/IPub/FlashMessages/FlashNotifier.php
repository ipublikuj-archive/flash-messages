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

declare(strict_types = 1);

namespace IPub\FlashMessages;

use Nette;
use Nette\Localization;

use Kdyby;
use Kdyby\Translation;

use IPub;
use IPub\FlashMessages\Adapters;
use IPub\FlashMessages\Entities;
use IPub\FlashMessages\Storage;

/**
 * Flash message notifier
 *
 * @package        iPublikuj:FlashMessages!
 * @subpackage     common
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
class FlashNotifier
{

	use Nette\SmartObject;

	/**
	 * @var Storage\IStorage
	 */
	protected $storage;

	/**
	 * @var bool
	 */
	protected $useTranslator;

	/**
	 * @var Localization\ITranslator
	 */
	protected $translator;

	/**
	 * @param bool $useTranslator
	 * @param Storage\IStorage $storage
	 * @param Localization\ITranslator|NULL $translator
	 */
	public function __construct(
		bool $useTranslator = TRUE,
		Storage\IStorage $storage,
		Localization\ITranslator $translator = NULL
	) {
		$this->storage = $storage;
		$this->translator = $translator;
		$this->useTranslator = $useTranslator;
	}

	/**
	 * Flash a success message
	 *
	 * @param string $message
	 * @param string|NULL $title
	 *
	 * @return Entities\IMessage
	 */
	public function success($message, $title = NULL) : Entities\IMessage
	{
		$args = func_get_args();
		array_splice($args, 1, 0, ['success']);

		return call_user_func_array([$this, 'setMessage'], $args);
	}

	/**
	 * Flash an information message
	 *
	 * @param string $message
	 * @param string|NULL $title
	 *
	 * @return Entities\IMessage
	 */
	public function info($message, $title = NULL) : Entities\IMessage
	{
		$args = func_get_args();
		array_splice($args, 1, 0, ['info']);

		return call_user_func_array([$this, 'setMessage'], $args);
	}

	/**
	 * Flash a warning message
	 *
	 * @param string $message
	 * @param string|NULL $title
	 *
	 * @return Entities\IMessage
	 */
	public function warning($message, $title = NULL) : Entities\IMessage
	{
		$args = func_get_args();
		array_splice($args, 1, 0, ['warning']);

		return call_user_func_array([$this, 'setMessage'], $args);
	}

	/**
	 * Flash an error message
	 *
	 * @param string $message
	 * @param string|NULL $title
	 *
	 * @return Entities\IMessage
	 */
	public function error($message, $title = NULL) : Entities\IMessage
	{
		$args = func_get_args();
		array_splice($args, 1, 0, ['danger']);

		return call_user_func_array([$this, 'setMessage'], $args);
	}

	/**
	 * Add an "important" flash to the session
	 *
	 * @return void
	 */
	public function important() : void
	{
		$this->storage->set(Storage\IStorage::KEY_IMPORTANT, TRUE);
	}

	/**
	 * Flash an overlay modal
	 *
	 * @param string $message
	 * @param string|NULL $title
	 *
	 * @return Entities\IMessage
	 */
	public function overlay($message, $title = NULL) : Entities\IMessage
	{
		$args = func_get_args();

		$level = $args[1];

		if (is_string($level) === FALSE || $level === NULL
			|| !in_array($level, [Entities\IMessage::LEVEL_ERROR, Entities\IMessage::LEVEL_INFO, Entities\IMessage::LEVEL_SUCCESS, Entities\IMessage::LEVEL_WARNING])
		) {
			array_splice($args, 1, 0, ['info']);
		}

		array_splice($args, 3, 0, [TRUE]);

		return call_user_func_array([$this, 'setMessage'], $args);
	}

	/**
	 * @param string $message
	 * @param string $level
	 * @param string|NULL $title
	 * @param bool $overlay
	 * @param int|NULL $count
	 * @param array $parameters
	 *
	 * @return Entities\IMessage
	 */
	public function message($message, $level = 'info', $title = NULL, $overlay = FALSE, $count = NULL, array $parameters = []) : Entities\IMessage
	{
		return $this->setMessage($message, $level, $title, $overlay, $count, $parameters);
	}

	/**
	 * Flash a general message
	 *
	 * @param string $message
	 * @param string $level
	 * @param string|NULL $title
	 * @param bool $overlay
	 * @param int|NULL $count
	 * @param array $parameters
	 *
	 * @return Entities\IMessage
	 */
	public function setMessage($message, $level = 'info', $title = NULL, $overlay = FALSE, $count = NULL, array $parameters = []) : Entities\IMessage
	{
		$args = func_get_args();

		// Remove message
		unset($args[0]);
		// Remove level
		unset($args[1]);

		$title = $this->checkForAttribute($args, 'title', NULL);
		$overlay = $this->checkForAttribute($args, 'overlay', FALSE);
		$count = $this->checkForAttribute($args, 'count', NULL);
		$parameters = $this->checkForAttribute($args, 'parameters', []);

		// Support for Kdyby/Translation
		if ($message instanceof Translation\Phrase) {
			$phrase = new Adapters\KdybyPhraseAdapter($message);

		// Default phrase adapter
		} else if (!$message instanceof Adapters\IPhraseAdapter) {
			$phrase = new Adapters\DefaultPhraseAdapter($message, $count, $parameters);

		} else {
			$phrase = $message;
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
		$messages = $this->storage->get(Storage\IStorage::KEY_MESSAGES, []);

		// Create flash message
		$flash = new Entities\Message(($this->useTranslator ? $this->translator : NULL), $phrase, $titlePhrase);
		$flash->setLevel($level);
		$flash->setOverlay($overlay);

		if (!$this->useTranslator || !$this->translator instanceof Localization\ITranslator) {
			if (is_string($message) === TRUE) {
				$flash->setMessage($message);
			}

			if (is_string($title) === TRUE) {
				$flash->setTitle($title);
			}
		}

		if ($this->checkUnique($flash, $messages) === FALSE) {
			$messages[] = $flash;
		}

		// Store messages in session
		$this->storage->set(Storage\IStorage::KEY_MESSAGES, $messages);

		return $flash;
	}

	/**
	 * @param Entities\IMessage $flash
	 * @param Entities\IMessage[] $messages
	 *
	 * @return bool
	 */
	private function checkUnique(Entities\IMessage $flash, array $messages) : bool
	{
		foreach ($messages as $member) {
			if ((string) $member === (string) $flash && !$member->isDisplayed()) {
				return TRUE;
			}
		}

		return FALSE;
	}

	/**
	 * @param array $attributes
	 * @param string $type
	 * @param mixed $default
	 *
	 * @return mixed
	 */
	private function checkForAttribute(array $attributes, string $type, $default)
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

		// Return default
		return $default;
	}
}

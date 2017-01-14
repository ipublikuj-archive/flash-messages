<?php
/**
 * Message.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:FlashMessages!
 * @subpackage     Entities
 * @since          1.0.0
 *
 * @date           06.02.15
 */

declare(strict_types = 1);

namespace IPub\FlashMessages\Entities;

use Nette;
use Nette\Localization;

use IPub;
use IPub\FlashMessages\Adapters;
use IPub\FlashMessages\Exceptions;

/**
 * Flash message entity
 *
 * @package        iPublikuj:FlashMessages!
 * @subpackage     Entities
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
class Message extends Nette\Object implements IMessage
{
	/**
	 * @var string
	 */
	private $message;

	/**
	 * @var string
	 */
	private $level;

	/**
	 * @var string
	 */
	private $title;

	/**
	 * @var bool
	 */
	private $overlay = FALSE;

	/**
	 * @var bool
	 */
	private $displayed = FALSE;

	/**
	 * @var Localization\ITranslator
	 */
	private $translator;

	/**
	 * @var Adapters\IPhraseAdapter
	 */
	private $messagePhraseAdapter;

	/**
	 * @var Adapters\IPhraseAdapter
	 */
	private $titlePhraseAdapter;

	/**
	 * @param Localization\ITranslator $translator
	 * @param Adapters\IPhraseAdapter $messagePhraseAdapter
	 * @param Adapters\IPhraseAdapter $titlePhraseAdapter
	 */
	public function __construct(
		Localization\ITranslator $translator = NULL,
		Adapters\IPhraseAdapter $messagePhraseAdapter,
		Adapters\IPhraseAdapter $titlePhraseAdapter = NULL
	) {
		$this->translator = $translator;
		$this->messagePhraseAdapter = $messagePhraseAdapter;
		$this->titlePhraseAdapter = $titlePhraseAdapter;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setMessage(string $message)
	{
		if ($this->isUnserialized()) {
			$this->message = $message;

		} else {
			$this->messagePhraseAdapter->setMessage($message);
			$this->message = NULL;
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function getMessage() : string
	{
		if ($this->message === NULL && $this->translator) {
			$this->message = $this->messagePhraseAdapter->translate($this->translator);
		}

		return $this->message;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setLevel(string $level)
	{
		$this->level = $level;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getLevel() : string
	{
		return $this->level;
	}

	/**
	 * {@inheritdoc}
	 */
	public function info()
	{
		$this->setLevel(self::LEVEL_INFO);
	}

	/**
	 * {@inheritdoc}
	 */
	public function success()
	{
		$this->setLevel(self::LEVEL_SUCCESS);
	}

	/**
	 * {@inheritdoc}
	 */
	public function warning()
	{
		$this->setLevel(self::LEVEL_WARNING);
	}

	/**
	 * {@inheritdoc}
	 */
	public function error()
	{
		$this->setLevel(self::LEVEL_ERROR);
	}

	/**
	 * {@inheritdoc}
	 */
	public function setTitle(string $title = NULL)
	{
		if ($this->isUnserialized()) {
			$this->title = $title;

		} else {
			if ($this->titlePhraseAdapter instanceof Adapters\IPhraseAdapter) {
				$this->titlePhraseAdapter->setMessage($title);
			}
			$this->title = NULL;
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function getTitle()
	{
		if ($this->title === NULL && $this->translator && $this->titlePhraseAdapter instanceof Adapters\IPhraseAdapter) {
			$this->title = $this->titlePhraseAdapter->translate($this->translator);
		}

		return $this->title;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setOverlay(bool $overlay)
	{
		$this->overlay = $overlay;
	}

	/**
	 * {@inheritdoc}
	 */
	public function hasOverlay() : bool
	{
		return $this->overlay;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setParameters(array $parameter)
	{
		$this->validateState(__FUNCTION__);
		$this->messagePhraseAdapter->setParameters($parameter);
		$this->message = NULL;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setCount(int $count)
	{
		$this->validateState(__FUNCTION__);
		$this->messagePhraseAdapter->setCount($count);
		$this->message = NULL;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setDisplayed(bool $displayed = TRUE)
	{
		$this->displayed = $displayed;
	}

	/**
	 * {@inheritdoc}
	 */
	public function isDisplayed() : bool
	{
		return $this->displayed;
	}

	/**
	 * @param string $method
	 *
	 * @throws Exceptions\InvalidStateException
	 */
	private function validateState(string $method)
	{
		if ($this->isUnserialized()) {
			throw new Exceptions\InvalidStateException(sprintf('You cannot call method %s on unserialized Entities\Message object', $method));
		}
	}

	/**
	 * @return bool
	 */
	private function isUnserialized() : bool
	{
		return $this->translator === NULL;
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->level . ' ' . ($this->title ? $this->title . ' ' : '') . $this->getMessage();
	}

	/**
	 * @return array
	 */
	public function __sleep()
	{
		$this->message = $this->getMessage();

		return ['message', 'level', 'title', 'overlay', 'displayed'];
	}
}

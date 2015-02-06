<?php
/**
 * Message.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:FlashMessages!
 * @subpackage	Entities
 * @since		5.0
 *
 * @date		06.02.15
 */

namespace IPub\FlashMessages\Entities;

use Nette;
use Nette\Localization;

use IPub;
use IPub\FlashMessages\Adapters;
use IPub\FlashMessages\Exceptions;

class Message extends Nette\Object implements IMessage
{
	const LEVEL_INFO	= 'info';
	const LEVEL_SUCCESS	= 'success';
	const LEVEL_WARNING	= 'warning';
	const LEVEL_ERROR	= 'error';

	/**
	 * @var string
	 */
	protected $message;

	/**
	 * @var string
	 */
	protected $level;

	/**
	 * @var string
	 */
	protected $title;

	/**
	 * @var bool
	 */
	protected $overlay = FALSE;

	/**
	 * @var bool
	 */
	protected $displayed = FALSE;

	/**
	 * @var Localization\ITranslator
	 */
	protected $translator;

	/**
	 * @var Adapters\IPhraseAdapter
	 */
	protected $phraseAdapter;

	/**
	 * @param Localization\ITranslator $translator
	 * @param Adapters\IPhraseAdapter $phraseAdapter
	 */
	public function __construct(Localization\ITranslator $translator, Adapters\IPhraseAdapter $phraseAdapter)
	{
		$this->translator		= $translator;
		$this->phraseAdapter	= $phraseAdapter;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setMessage($message)
	{
		if ($this->isUnserialized()) {
			$this->message = $message;

		} else {
			$this->phraseAdapter->setMessage($message);
			$this->message = NULL;
		}

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getMessage()
	{
		if ($this->message === NULL && $this->translator) {
			$this->message = $this->phraseAdapter->translate($this->translator);
		}

		return $this->message;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setLevel($level)
	{
		$this->level = $level;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getLevel()
	{
		return $this->level;
	}

	/**
	 * {@inheritdoc}
	 */
	public function info()
	{
		$this->setType(self::LEVEL_INFO);

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function success()
	{
		$this->setType(self::LEVEL_SUCCESS);

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function warning()
	{
		$this->setType(self::LEVEL_WARNING);

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function error()
	{
		$this->setType(self::LEVEL_ERROR);

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setTitle($title = NULL)
	{
		$this->title = (string) $title;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setOverlay($overlay)
	{
		$this->overlay = (bool) $overlay;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getOverlay()
	{
		return $this->overlay;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setParameters(array $parameter)
	{
		$this->validateState(__FUNCTION__);
		$this->phraseAdapter->setParameters($parameter);
		$this->message = NULL;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setCount($count)
	{
		$this->validateState(__FUNCTION__);
		$this->phraseAdapter->setCount($count);
		$this->message = NULL;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setDisplayed($displayed = TRUE)
	{
		$this->displayed = (bool) $displayed;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function isDisplayed()
	{
		return $this->displayed === TRUE ? TRUE : FALSE;
	}

	/**
	 * @param string $method
	 *
	 * @throws Exceptions\InvalidStateException
	 */
	private function validateState($method)
	{
		if ($this->isUnserialized()) {
			throw new Exceptions\InvalidStateException("You cannot call method $method on unserialized Entities\\Message object");
		}
	}

	/**
	 * @return bool
	 */
	private function isUnserialized()
	{
		return $this->translator === NULL;
	}

	public function __sleep()
	{
		$this->message = $this->getMessage();

		return ['message', 'level', 'title', 'overlay', 'displayed'];
	}
}
<?php
/**
 * KdybyPhraseAdapter.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:FlashMessages!
 * @subpackage	Adapters
 * @since		5.0
 *
 * @date		06.02.15
 */

namespace IPub\FlashMessages\Adapters;

use Nette;
use Nette\Localization;

use Kdyby;
use Kdyby\Translation;

class KdybyPhraseAdapter extends Nette\Object implements IPhraseAdapter
{
	/**
	 * @var Translation\Phrase
	 */
	protected $phrase;

	/**
	 * @param Translation\Phrase $phrase
	 */
	public function __construct(Translation\Phrase $phrase)
	{
		$this->phrase = $phrase;
	}

	/**
	 * {@inheritdoc}
	 */
	public function translate(Localization\ITranslator $translator)
	{
		return $this->phrase->translate($translator);
	}

	/**
	 * {@inheritdoc}
	 */
	public function setMessage($message)
	{
		$this->phrase->message = $message;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setCount($count)
	{
		$this->phrase->count = $count;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setParameters($parameters)
	{
		$this->phrase->parameters = $parameters;
	}
}
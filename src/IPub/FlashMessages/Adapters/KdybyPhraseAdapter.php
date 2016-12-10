<?php
/**
 * KdybyPhraseAdapter.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:FlashMessages!
 * @subpackage     Adapters
 * @since          1.0.0
 *
 * @date           06.02.15
 */

declare(strict_types = 1);

namespace IPub\FlashMessages\Adapters;

use Nette;
use Nette\Localization;

use Kdyby;
use Kdyby\Translation;

/**
 * Kdyby\Translator extension translator phrase adapter
 *
 * @package        iPublikuj:FlashMessages!
 * @subpackage     Adapters
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
class KdybyPhraseAdapter extends Nette\Object implements IPhraseAdapter
{
	/**
	 * @var Translation\Phrase
	 */
	private $phrase;

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
		if ($translator instanceof Translation\Translator) {
			return $this->phrase->translate($translator);
		}

		return NULL;
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

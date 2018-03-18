<?php
/**
 * KdybyPhraseAdapter.php
 *
 * @copyright      More in license.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
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

use Kdyby\Translation;

/**
 * Kdyby\Translator extension translator phrase adapter
 *
 * @package        iPublikuj:FlashMessages!
 * @subpackage     Adapters
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
class KdybyPhraseAdapter implements IPhraseAdapter
{
	/**
	 * Implement nette smart magic
	 */
	use Nette\SmartObject;

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
	public function translate(Localization\ITranslator $translator) : ?string
	{
		if ($translator instanceof Translation\Translator) {
			return $this->phrase->translate($translator);
		}

		return NULL;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setMessage($message) : void
	{
		$this->phrase->message = $message;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setCount($count) : void
	{
		$this->phrase->count = $count;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setParameters($parameters) : void
	{
		$this->phrase->parameters = $parameters;
	}
}

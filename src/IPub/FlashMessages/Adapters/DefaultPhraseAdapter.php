<?php
/**
 * DefaultPhraseAdapter.php
 *
 * @copyright      More in license.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec https://www.ipublikuj.eu
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

/**
 * Default translator phrase adapter
 *
 * @package        iPublikuj:FlashMessages!
 * @subpackage     Adapters
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
class DefaultPhraseAdapter implements IPhraseAdapter
{
	/**
	 * Implement nette smart magic
	 */
	use Nette\SmartObject;

	/**
	 * @var string
	 */
	private $message;

	/**
	 * @var int
	 */
	private $count;

	/**
	 * @var array
	 */
	private $parameters;

	/**
	 * @param string $message
	 * @param int $count
	 * @param array $parameters
	 */
	public function __construct($message, $count, $parameters = [])
	{
		$this->parameters = $parameters;
		$this->count = $count;
		$this->message = $message;
	}

	/**
	 * {@inheritdoc}
	 */
	public function translate(Localization\ITranslator $translator) : ?string
	{
		return $translator->translate($this->message, $this->count, $this->parameters);
	}

	/**
	 * {@inheritdoc}
	 */
	public function setMessage($message) : void
	{
		$this->message = $message;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setCount($count) : void
	{
		$this->count = $count;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setParameters($parameters) : void
	{
		$this->parameters = $parameters;
	}
}

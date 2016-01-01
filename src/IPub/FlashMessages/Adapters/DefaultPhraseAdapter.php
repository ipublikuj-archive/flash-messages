<?php
/**
 * DefaultPhraseAdapter.php
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

namespace IPub\FlashMessages\Adapters;

use Nette;
use Nette\Localization;

/**
 * Default translator phrase adapter
 *
 * @package        iPublikuj:FlashMessages!
 * @subpackage     Adapters
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
class DefaultPhraseAdapter extends Nette\Object implements IPhraseAdapter
{
	/**
	 * @var string
	 */
	protected $message;

	/**
	 * @var int
	 */
	protected $count;

	/**
	 * @var array
	 */
	protected $parameters;

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
	public function translate(Localization\ITranslator $translator)
	{
		return $translator->translate($this->message, $this->count, $this->parameters);
	}

	/**
	 * {@inheritdoc}
	 */
	public function setMessage($message)
	{
		$this->message = $message;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setCount($count)
	{
		$this->count = $count;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setParameters($parameters)
	{
		$this->parameters = $parameters;
	}
}

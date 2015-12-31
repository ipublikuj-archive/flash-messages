<?php
/**
 * IPhraseAdapter.php
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
 * Translator phrase adapter interface
 *
 * @package        iPublikuj:FlashMessages!
 * @subpackage     Adapters
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
interface IPhraseAdapter
{
	/**
	 * @param Localization\ITranslator $translator
	 *
	 * @return string
	 */
	public function translate(Localization\ITranslator $translator);

	/**
	 * @param string $message
	 *
	 * @return $this
	 */
	public function setMessage($message);

	/**
	 * @param int $count
	 *
	 * @return $this
	 */
	public function setCount($count);

	/**
	 * @param array $parameters
	 *
	 * @return $this
	 */
	public function setParameters($parameters);
}

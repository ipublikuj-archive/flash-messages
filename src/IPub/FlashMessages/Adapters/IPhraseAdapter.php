<?php
/**
 * IPhraseAdapter.php
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

use Nette\Localization;

/**
 * Translator phrase adapter interface
 *
 * @package        iPublikuj:FlashMessages!
 * @subpackage     Adapters
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
interface IPhraseAdapter
{
	/**
	 * @param Localization\ITranslator $translator
	 *
	 * @return string|NULL
	 */
	public function translate(Localization\ITranslator $translator) : ?string;

	/**
	 * @param string $message
	 *
	 * @return void
	 */
	public function setMessage($message) : void;

	/**
	 * @param int $count
	 *
	 * @return void
	 */
	public function setCount($count) : void;

	/**
	 * @param array $parameters
	 *
	 * @return void
	 */
	public function setParameters($parameters) : void;
}

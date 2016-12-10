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
use IPub\FlashMessages\Exceptions;

/**
 * Flash message entity interface
 *
 * @package        iPublikuj:FlashMessages!
 * @subpackage     Entities
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
interface IMessage
{
	const LEVEL_INFO = 'info';
	const LEVEL_SUCCESS = 'success';
	const LEVEL_WARNING = 'warning';
	const LEVEL_ERROR = 'error';

	/**
	 * @param string $message
	 *
	 * @return void
	 */
	function setMessage(string $message);

	/**
	 * @return string
	 */
	function getMessage() : string;

	/**
	 * @param string $level
	 *
	 * @return void
	 */
	function setLevel(string $level);

	/**
	 * @return string
	 */
	function getLevel() : string;

	/**
	 * @return void
	 */
	function info();

	/**
	 * @return void
	 */
	function success();

	/**
	 * @return void
	 */
	function warning();

	/**
	 * @return void
	 */
	function error();

	/**
	 * @param string|NULL $title
	 *
	 * @return void
	 */
	function setTitle(string $title = NULL);

	/**
	 * @return string|NULL
	 */
	function getTitle();

	/**
	 * @param bool $overlay
	 *
	 * @return void
	 */
	function setOverlay(bool $overlay);

	/**
	 * @return bool
	 */
	function hasOverlay() : bool;

	/**
	 * @param array $parameter
	 *
	 * @return void
	 *
	 * @throws Exceptions\InvalidStateException when object is unserialized
	 */
	function setParameters(array $parameter);

	/**
	 * @param int $count
	 *
	 * @return void
	 *
	 * @throws Exceptions\InvalidStateException when object is unserialized
	 */
	function setCount(int $count);

	/**
	 * @param bool $displayed
	 *
	 * @return void
	 */
	function setDisplayed(bool $displayed = TRUE);

	/**
	 * @return bool
	 */
	function isDisplayed() : bool;
}

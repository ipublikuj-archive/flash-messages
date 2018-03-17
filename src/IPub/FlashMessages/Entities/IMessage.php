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
	public const LEVEL_INFO = 'info';
	public const LEVEL_SUCCESS = 'success';
	public const LEVEL_WARNING = 'warning';
	public const LEVEL_ERROR = 'error';

	/**
	 * @param string $message
	 *
	 * @return void
	 */
	public function setMessage(string $message) : void;

	/**
	 * @return string
	 */
	public function getMessage() : string;

	/**
	 * @param string $level
	 *
	 * @return void
	 */
	public function setLevel(string $level) : void;

	/**
	 * @return string
	 */
	public function getLevel() : string;

	/**
	 * @return void
	 */
	public function info() : void;

	/**
	 * @return void
	 */
	public function success() : void;

	/**
	 * @return void
	 */
	public function warning() : void;

	/**
	 * @return void
	 */
	public function error() : void;

	/**
	 * @param string|NULL $title
	 *
	 * @return void
	 */
	public function setTitle(string $title = NULL) : void;

	/**
	 * @return string|NULL
	 */
	public function getTitle() : ?string;

	/**
	 * @param bool $overlay
	 *
	 * @return void
	 */
	public function setOverlay(bool $overlay) : void;

	/**
	 * @return bool
	 */
	public function hasOverlay() : bool;

	/**
	 * @param array $parameter
	 *
	 * @return void
	 *
	 * @throws Exceptions\InvalidStateException when object is unserialized
	 */
	public function setParameters(array $parameter) : void;

	/**
	 * @param int $count
	 *
	 * @return void
	 *
	 * @throws Exceptions\InvalidStateException when object is unserialized
	 */
	public function setCount(int $count) : void;

	/**
	 * @param bool $displayed
	 *
	 * @return void
	 */
	public function setDisplayed(bool $displayed = TRUE) : void;

	/**
	 * @return bool
	 */
	public function isDisplayed() : bool;
}

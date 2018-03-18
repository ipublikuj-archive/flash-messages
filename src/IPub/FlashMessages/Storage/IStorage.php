<?php
/**
 * IStorage.php
 *
 * @copyright      More in license.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 * @package        iPublikuj:FlashMessages!
 * @subpackage     Storage
 * @since          2.1.0
 *
 * @date           26.07.14
 */

declare(strict_types = 1);

namespace IPub\FlashMessages\Storage;

/**
 * Message status storage interface
 *
 * @package        iPublikuj:FlashMessages!
 * @subpackage     Storage
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
interface IStorage
{
	/**
	 * Define session keys
	 */
	public const KEY_MESSAGES = 'messages';
	public const KEY_IMPORTANT = 'important';

	/**
	 * Stores the given ($key, $value) pair, so that future calls to
	 * get($key) return $value. This call may be in another request.
	 *
	 * @param string $key
	 * @param mixed $value
	 *
	 * @return void
	 */
	public function set(string $key, $value) : void;

	/**
	 * Get the data for $key
	 *
	 * @param string $key    The key of the data to retrieve
	 * @param mixed $default The default value to return if $key is not found
	 *
	 * @return mixed
	 */
	public function get(string $key, $default = FALSE);

	/**
	 * Clear the data with $key from the persistent storage
	 *
	 * @param string $key
	 *
	 * @return void
	 */
	public function clear(string $key) : void;

	/**
	 * Clear all data from the persistent storage
	 *
	 * @return void
	 */
	public function clearAll() : void;
}

<?php
/**
 * IStorage.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:FlashMessages!
 * @subpackage     Storage
 * @since          2.1.0
 *
 * @date           26.07.14
 */

declare(strict_types = 1);

namespace IPub\FlashMessages\Storage;

use Nette;
use Nette\Http;

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
	const KEY_MESSAGES = 'messages';
	const KEY_IMPORTANT = 'important';

	/**
	 * Stores the given ($key, $value) pair, so that future calls to
	 * get($key) return $value. This call may be in another request.
	 *
	 * @param string $key
	 * @param mixed $value
	 *
	 * @return void
	 */
	function set(string $key, $value);

	/**
	 * Get the data for $key
	 *
	 * @param string $key    The key of the data to retrieve
	 * @param mixed $default The default value to return if $key is not found
	 *
	 * @return mixed
	 */
	function get(string $key, $default = FALSE);

	/**
	 * Clear the data with $key from the persistent storage
	 *
	 * @param string $key
	 *
	 * @return void
	 */
	function clear(string $key);

	/**
	 * Clear all data from the persistent storage
	 *
	 * @return void
	 */
	function clearAll();
}

<?php
/**
 * SessionStorage.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:FlashMessages!
 * @subpackage     common
 * @since          1.0.0
 *
 * @date           05.02.15
 */

namespace IPub\FlashMessages;

use Nette;
use Nette\Http;

/**
 * Flash message session storage
 *
 * @package        iPublikuj:FlashMessages!
 * @subpackage     common
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
class SessionStorage extends Nette\Object
{
	/**
	 * Define session keys
	 */
	const KEY_MESSAGES = 'messages';
	const KEY_IMPORTANT = 'important';

	/**
	 * @var Http\SessionSection
	 */
	protected $session;

	/**
	 * @param Http\Session $session
	 */
	public function __construct(Http\Session $session)
	{
		$this->session = $session->getSection('ipub.flash-messages');
	}

	/**
	 * Stores the given ($key, $value) pair, so that future calls to
	 * get($key) return $value. This call may be in another request.
	 *
	 * @param string $key
	 * @param mixed $value
	 *
	 * @return $this
	 */
	public function set($key, $value)
	{
		$this->session->$key = $value;

		return $this;
	}

	/**
	 * Get the data for $key
	 *
	 * @param string $key    The key of the data to retrieve
	 * @param mixed $default The default value to return if $key is not found
	 *
	 * @return mixed
	 */
	public function get($key, $default = FALSE)
	{
		return isset($this->session->$key) ? $this->session->$key : $default;
	}

	/**
	 * Clear the data with $key from the persistent storage
	 *
	 * @param string $key
	 *
	 * @return $this
	 */
	public function clear($key)
	{
		unset($this->session->$key);

		return $this;
	}

	/**
	 * Clear all data from the persistent storage
	 *
	 * @return $this
	 */
	public function clearAll()
	{
		$this->session->remove();

		return $this;
	}

	/**
	 * @param string $name
	 *
	 * @return mixed
	 */
	public function &__get($name)
	{
		$value = $this->get($name);

		return $value;
	}

	/**
	 * @param string $name
	 * @param mixed $value
	 *
	 * @return $this
	 */
	public function __set($name, $value)
	{
		$this->set($name, $value);

		return $this;
	}

	/**
	 * @param string $name
	 *
	 * @return bool
	 */
	public function __isset($name)
	{
		return isset($this->session->$name);
	}

	/**
	 * @param string $name
	 *
	 * @return $this
	 */
	public function __unset($name)
	{
		$this->clear($name);

		return $this;
	}
}

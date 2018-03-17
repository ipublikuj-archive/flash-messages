<?php
/**
 * SessionStorage.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:FlashMessages!
 * @subpackage     Storage
 * @since          1.0.0
 *
 * @date           08.06.14
 */

declare(strict_types = 1);

namespace IPub\FlashMessages\Storage;

use Nette\Http;

/**
 * Message session status storage
 *
 * @package        iPublikuj:FlashMessages!
 * @subpackage     Storage
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
final class Session implements IStorage
{
	/**
	 * @var Http\SessionSection
	 */
	private $session;

	/**
	 * @param Http\Session $session
	 */
	public function __construct(Http\Session $session)
	{
		$this->session = $session->getSection('ipub.flash-messages');
	}

	/**
	 * {@inheritdoc}
	 */
	public function set(string $key, $value) : void
	{
		$this->session->$key = $value;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get(string $key, $default = FALSE)
	{
		return isset($this->session->$key) ? $this->session->$key : $default;
	}

	/**
	 * {@inheritdoc}
	 */
	public function clear(string $key) : void
	{
		unset($this->session->$key);
	}

	/**
	 * {@inheritdoc}
	 */
	public function clearAll() : void
	{
		$this->session->remove();
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
	 * @return void
	 */
	public function __set($name, $value)
	{
		$this->set($name, $value);
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
	 * @return void
	 */
	public function __unset($name)
	{
		$this->clear($name);
	}
}

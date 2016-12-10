<?php
/**
 * OnResponseHandler.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:FlashMessages!
 * @subpackage     Events
 * @since          1.0.0
 *
 * @date           06.02.15
 */

declare(strict_types = 1);

namespace IPub\FlashMessages\Events;

use IPub;
use IPub\FlashMessages;
use IPub\FlashMessages\Entities;
use IPub\FlashMessages\Storage;

/**
 * Flash message storage events
 *
 * @package        iPublikuj:FlashMessages!
 * @subpackage     Events
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
class OnResponseHandler
{
	/**
	 * @var Storage\IStorage
	 */
	private $storage;

	/**
	 * @param Storage\IStorage $storage
	 */
	public function __construct(Storage\IStorage $storage)
	{
		$this->storage = $storage;
	}

	/**
	 * @return void
	 */
	public function __invoke()
	{
		/** @var Entities\IMessage[] $messages */
		$messages = $this->storage->get(Storage\IStorage::KEY_MESSAGES, []);

		foreach ($messages as $key => $message) {
			if ($message->isDisplayed()) {
				unset($messages[$key]);
			}
		}

		// Update messages in session
		$this->storage->set(Storage\IStorage::KEY_MESSAGES, $messages);
	}
}

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

namespace IPub\FlashMessages\Events;

use IPub;
use IPub\FlashMessages;
use IPub\FlashMessages\Entities;

/**
 * Flash message storage events
 *
 * @package        iPublikuj:FlashMessages!
 * @subpackage     Events
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
class OnResponseHandler
{
	/**
	 * @var FlashMessages\SessionStorage
	 */
	private $sessionStorage;

	/**
	 * @param FlashMessages\SessionStorage $sessionStorage
	 */
	public function __construct(FlashMessages\SessionStorage $sessionStorage)
	{
		$this->sessionStorage = $sessionStorage;
	}

	/**
	 *
	 */
	public function __invoke()
	{
		/** @var Entities\IMessage[] $messages */
		$messages = $this->sessionStorage->get(FlashMessages\SessionStorage::KEY_MESSAGES, []);

		foreach ($messages as $key => $message) {
			if ($message->isDisplayed()) {
				unset($messages[$key]);
			}
		}

		// Update messages in session
		$this->sessionStorage->set(FlashMessages\SessionStorage::KEY_MESSAGES, $messages);
	}
}

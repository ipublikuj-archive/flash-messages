<?php
/**
 * OnResponseHandler.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:FlashMessages!
 * @subpackage	Events
 * @since		5.0
 *
 * @date		06.02.15
 */

namespace IPub\FlashMessages\Events;

use Nette;
use Nette\Application;

use IPub;
use IPub\FlashMessages;

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
	 * @param Application\Application $application
	 */
	public function __invoke(Application\Application $application)
	{
		$messages = $this->sessionStorage->get(FlashMessages\SessionStorage::KEY_MESSAGES, []);

		foreach($messages as $key => $message) {
			if ($message->isDisplayed()) {
				unset($messages[$key]);
			}
		}

		// Update messages in session
		$this->sessionStorage->set(FlashMessages\SessionStorage::KEY_MESSAGES, $messages);
	}
}
<?php
/**
 * TFlashMessages.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:FlashMessages!
 * @subpackage     common
 * @since          1.0.0
 *
 * @date           01.02.15
 */

namespace IPub\FlashMessages;

use Nette;

use IPub;
use IPub\FlashMessages\Components;

/**
 * Flash message helper trait
 *
 * @package        iPublikuj:FlashMessages!
 * @subpackage     common
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
trait TFlashMessages
{
	/**
	 * @var Components\IControl
	 */
	protected $flashMessagesFactory;

	/**
	 * @var FlashNotifier
	 */
	protected $flashNotifier;

	/**
	 * @param Components\IControl $flashMessagesFactory
	 * @param FlashNotifier $flashNotifier
	 *
	 * @return void
	 */
	public function injectFlashMessages(Components\IControl $flashMessagesFactory, FlashNotifier $flashNotifier) : void
	{
		$this->flashMessagesFactory = $flashMessagesFactory;
		$this->flashNotifier = $flashNotifier;
	}

	/**
	 * Store flash message
	 *
	 * @param string $message
	 * @param string $level
	 * @param string|NULL $title
	 * @param bool $overlay
	 * @param int|NULL $count
	 * @param array|NULL $parameters
	 *
	 * @return Entities\IMessage
	 */
	public function flashMessage($message, $level = 'info', $title = NULL, $overlay = FALSE, $count = NULL, $parameters = []) : Entities\IMessage
	{
		return $this->flashNotifier->message($message, $level, $title, $overlay, $count, $parameters);
	}

	/**
	 * Flash messages component
	 *
	 * @return IPub\FlashMessages\Components\Control
	 */
	protected function createComponentFlashMessages() : IPub\FlashMessages\Components\Control
	{
		return $this->flashMessagesFactory->create();
	}
}

<?php
/**
 * TVisualPaginator.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:VisualPaginator!
 * @subpackage	common
 * @since		5.0
 *
 * @date		01.02.15
 */

namespace IPub\FlashMessages;

use Nette;
use Nette\Application;

use IPub;
use IPub\FlashMessages\Components;

trait TFlashMessages
{
	/**
	 * @var Components\IControl
	 */
	protected $flashMessagesFactory;

	/**
	 * @param Components\IControl $flashMessagesFactory
	 */
	public function injectFlashMessages(Components\IControl $flashMessagesFactory) {
		$this->flashMessagesFactory = $flashMessagesFactory;
	}
}

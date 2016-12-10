<?php
/**
 * IControl.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:FlashMessages!
 * @subpackage     Components
 * @since          1.0.0
 *
 * @date           12.03.14
 */

declare(strict_types = 1);

namespace IPub\FlashMessages\Components;

/**
 * Flash messages control factory interface
 *
 * @package        iPublikuj:FlashMessages!
 * @subpackage     Components
 */
interface IControl
{
	/**
	 * @param string|NULL $templateFile
	 *
	 * @return Control
	 */
	public function create($templateFile = NULL) : Control;
}

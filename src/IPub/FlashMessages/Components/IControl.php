<?php
/**
 * IControl.php
 *
 * @copyright      More in license.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
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
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
interface IControl
{
	/**
	 * @param string|NULL $templateFile
	 *
	 * @return Control
	 */
	public function create(?string $templateFile = NULL) : Control;
}

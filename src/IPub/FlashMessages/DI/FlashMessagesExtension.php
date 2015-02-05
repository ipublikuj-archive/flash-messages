<?php
/**
 * FlashMessagesExtension.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:FlashMessages!
 * @subpackage	DI
 * @since		5.0
 *
 * @date		05.02.15
 */

namespace IPub\FlashMessages\DI;

use Nette;
use Nette\DI;
use Nette\PhpGenerator as Code;

class FlashMessagesExtension extends DI\CompilerExtension
{
	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();

		// Session storage
		$builder->addDefinition($this->prefix('session'))
			->setClass('IPub\FlashMessages\SessionStorage');

		// Define components
		$builder->addDefinition($this->prefix('messages'))
			->setClass('IPub\FlashMessages\Components\Control')
			->setImplement('IPub\FlashMessages\Components\IControl')
			->addTag('cms.components');
	}

	/**
	 * @param Nette\Configurator $config
	 * @param string $extensionName
	 */
	public static function register(Nette\Configurator $config, $extensionName = 'flashMessages')
	{
		$config->onCompile[] = function (Nette\Configurator $config, Nette\DI\Compiler $compiler) use ($extensionName) {
			$compiler->addExtension($extensionName, new FlashMessagesExtension());
		};
	}
}
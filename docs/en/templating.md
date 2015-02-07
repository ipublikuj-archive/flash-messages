# Custom templates

This extension come with three predefined templates:

* bootstrap.latte if you are using [Twitter Bootstrap](http://getbootstrap.com/)
* uikit.latte if you are using [YooTheme UIKit](http://getuikit.com/)
* default.latte for custom styling (this template is loaded as default)

If you are using one of the front-end framework you can just define it:

```php
namespace Your\Coool\Namespace\Presenter;

use IPub\FlashMessages;

class SomePresenter
{
	/**
	 * Insert extension trait (only for PHP 5.4+)
	 */
	use FlashMessages\TFlashMessages;

	/**
	 * Component for displaying messages
	 *
	 * @return FlashMessages\Control
	 */
	protected function createComponentFlashMessages()
	{
		// Create control
		$control = $this->flashMessagesFactory->create();

		// Define template
		$control->setTemplateFile('bootstrap.latte');

		return $control;
	}
}
```

With method **setTemplateFile** you can define one of the three predefined templates.

## Custom templates

If you don't want to use one of the predefined template from extension, you can define your own custom template. The way how to handle is same as in predefined templates:

```php
namespace Your\Coool\Namespace\Presenter;

use IPub\FlashMessages;

class SomePresenter
{
	/**
	 * Insert extension trait (only for PHP 5.4+)
	 */
	use FlashMessages\TFlashMessages;

	/**
	 * Component for displaying messages
	 *
	 * @return FlashMessages\Control
	 */
	protected function createComponentFlashMessages()
	{
		// Create control
		$control = $this->flashMessagesFactory->create();

		$control->setTemplateFile('path/to/your/template.latte');
		
		....
	}
}
```

## Define template with factory

Factory which is creating component can pass info about what template should be used. So if you know which template use when you are creating flash messages component, you can pass it:

```php
namespace Your\Coool\Namespace\Presenter;

use IPub\FlashMessages;

class SomePresenter
{
	/**
	 * Insert extension trait (only for PHP 5.4+)
	 */
	use FlashMessages\TFlashMessages;

	/**
	 * Component for displaying messages
	 *
	 * @return FlashMessages\Control
	 */
	protected function createComponentFlashMessages()
	{
		// Create control
		$control = $this->flashMessagesFactory->create('bootstrap.latte');

		// or

		$control = $this->flashMessagesFactory->create('path/to/your/template.latte');

		....
	}
}
```

## Define template in the configuration

Another way how to configure template is in extension configuration.

```neon
	flashMessages:
		templateFile	: bootstrap.latte // uikit.latte // default.latte // or/path/to/your/template.latte
```

System will automatically asset this template into component 
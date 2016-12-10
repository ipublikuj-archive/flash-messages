# Quickstart

This extension replace default flash messages handling. If you want to use one interface for displaying messages, use this extension. For eg. if you are showing messages in modal windows, but sometimes this windows are deactivated, you can reach it with this extension. It store messages in one place and check if were displayed or not.

## Installation

The best way to install ipub/flash-messages is using  [Composer](http://getcomposer.org/):

```sh
$ composer require ipub/flash-messages
```

After that you have to register extension in config.neon.

```neon
extensions:
    flashMessages: IPub\FlashMessages\DI\FlashMessagesExtension
```

Package contains trait, which you will have to use in presenters or components to implement Flash messages component factory. This works only for PHP 5.4+, for older version you can simply copy trait content and paste it into class where you want to use it.

```php
<?php

class BasePresenter extends Nette\Application\UI\Presenter
{
    use IPub\FlashMessages\TFlashMessages;

    // ...
}
```

## Usage

### Create component in Presenter or Component

At first you have to create component as usual, like other component in Nette:

```php
namespace Your\Coool\Namespace\Presenter;

class SomePresenter extends Nette\Application\UI\Presenter
{
	/**
	 * Insert extension trait (only for PHP 5.4+)
	 */
	use \IPub\FlashMessages\TFlashMessages;

	/**
	 * Component for displaying messages
	 *
	 * @return FlashMessages\Component
	 */
	protected function createComponentFlashMessages()
	{
		// Init action confirm
		$control = $this->flashMessagesFactory->create();

		return $control;
	}
}
```

### Add component to template

Now put this new component into your template:

```html
<document>
<head>
	.....
</head>
<body>
	// ...you template content

	{control flashMessages}

	<div class="content">
		...
	</div>
</body>
```

### Create flash message

And if you want to display some message you can use Nette default method to store flash messages. This default method is overwritten in trait.

```php
<?php

class SomePresenter extends Nette\Application\UI\Presenter
{
	/**
	 * Insert extension trait (only for PHP 5.4+)
	 */
	use \IPub\FlashMessages\TFlashMessages;

	public function actionSome()
	{
		$this->flashMessage('Message text', 'warning');
	}
}
```

Component will display classic warning message with default template.

### Special messages

This extension has its own methods to create flash messages, which support more parameters. You can replace default **flashMessage** method wit this special:

```php
<?php

class SomePresenter extends Nette\Application\UI\Presenter
{
	/**
	 * Insert extension trait (only for PHP 5.4+)
	 */
	use \IPub\FlashMessages\TFlashMessages;

	public function actionSome()
	{
		$this->flashNotifier->message('Message text', 'warning', 'My message title');

		// or

		$this->flashNotifier->warning('Message text', 'My message title');
	}
}
```

Available methods to store message:

* $this->flashNotifier->success($messageContent, $messageTitle)
* $this->flashNotifier->info($messageContent, $messageTitle)
* $this->flashNotifier->warning($messageContent, $messageTitle)
* $this->flashNotifier->error($messageContent, $messageTitle)

Message title is optional parameter.

### Messages in modal windows with overlay

If you want to create important message and display it in modal window, you can use special option or special method:

```php
<?php

class SomePresenter extends Nette\Application\UI\Presenter
{
	/**
	 * Insert extension trait (only for PHP 5.4+)
	 */
	use FlashMessages\TFlashMessages;

	public function actionSome()
	{
		$this->flashNotifier->overlay('Message text', 'warning', 'My message title');

		// or

		$this->flashNotifier->overlay('Message text', 'My message title'); // Without level info message will be created

		// or

		$this->flashNotifier->message('Message text', 'warning', 'My message title', TRUE);
	}
}
```

## More

- [Read more how to translate messages](https://github.com/iPublikuj/flash-messages/blob/master/docs/en/translators.md)
- [Read more about templating system](https://github.com/iPublikuj/flash-messages/blob/master/docs/en/templating.md)

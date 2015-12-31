
## Usage

### Create component in Presenter or Control

At first you have to create component as usual, like other component in Nette:

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

class BasePresenter extends Nette\Application\UI\Presenter
{
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

class BasePresenter extends Nette\Application\UI\Presenter
{
	public function actionSome()
	{
		$this->flashNotifier->message('Message text', 'warning', 'My message title');

		// or

		$this->warning('Message text', 'My message title');
	}
}
```

Available methods to store message:

* $this->flashNotifier->success($messageContent, $messageTitle)
* $this->flashNotifier->info($messageContent, $messageTitle)
* $this->flashNotifier->warning($messageContent, $messageTitle)
* $this->flashNotifier->error($messageContent, $messageTitle)

Message title is optional parameter.

### Important modal messages

If you want to create important message and display it in modal window, you can use special option for it: *overlay*

# Using translators

If you are using translator, then you can pass untranslated strings into this extensions, and it will automatically translate them into right phrase.

> NOTE: This extension have native support of [Kdyby/Translation](https://github.com/Kdyby/Translation)

For translations, there are additional parameters, because you may need to change phrase according to count or replace some parameters.

```php
$this->flashMessage('Message text to display info about %replaceItWithSomething%', 'success', 'Optional title', $overlay, $count, $parameters)
```

or

```php
$this->flashNotifier->message('Message text to display info about %replaceItWithSomething%', 'success', 'Optional title', $overlay, $count, $parameters)
```

Translator will translate you message with depending on the *$count* variable and replace placeholders with defined parameters.

If you are using Kdyby/Translation you can pass [Kdyby\Translation\Phrase](https://github.com/Kdyby/Translation/blob/master/src/Kdyby/Translation/Phrase.php) object as message.

## More

- [Read more how to implement flash messages extension](https://github.com/iPublikuj/flash-messages/blob/master/docs/en/index.md)
- [Read more about templating system](https://github.com/iPublikuj/flash-messages/blob/master/docs/en/templating.md)

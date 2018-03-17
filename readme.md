# Flash Messages

[![Build Status](https://img.shields.io/travis/iPublikuj/flash-messages.svg?style=flat-square)](https://travis-ci.org/iPublikuj/flash-messages)
[![Scrutinizer Code Coverage](https://img.shields.io/scrutinizer/coverage/g/iPublikuj/flash-messages.svg?style=flat-square)](https://scrutinizer-ci.com/g/iPublikuj/flash-messages/?branch=master)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/iPublikuj/flash-messages.svg?style=flat-square)](https://scrutinizer-ci.com/g/iPublikuj/flash-messages/?branch=master)
[![Latest Stable Version](https://img.shields.io/packagist/v/ipub/flash-messages.svg?style=flat-square)](https://packagist.org/packages/ipub/flash-messages)
[![Composer Downloads](https://img.shields.io/packagist/dt/ipub/flash-messages.svg?style=flat-square)](https://packagist.org/packages/ipub/flash-messages)
[![License](https://img.shields.io/packagist/l/ipub/flash-messages.svg?style=flat-square)](https://packagist.org/packages/ipub/flash-messages)

Flash messages handler for [Nette Framework](http://nette.org/)

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

Package contains trait, which you will have to use in presenters or components to implement Flash messages component factory.

```php
<?php

class BasePresenter extends Nette\Application\UI\Presenter
{
    use IPub\FlashMessages\TFlashMessages;

    // ...
}
```

## Documentation

Learn how to use flash messages in different way in [documentation](https://github.com/iPublikuj/flash-messages/blob/master/docs/en/index.md).

***
Homepage [http://www.ipublikuj.eu](http://www.ipublikuj.eu) and repository [http://github.com/iPublikuj/flash-messages](http://github.com/iPublikuj/flash-messages).

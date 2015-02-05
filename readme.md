# Flash Messages

[![Build Status](https://img.shields.io/travis/iPublikuj/flash-messages.svg?style=flat-square)](https://travis-ci.org/iPublikuj/flash-messages)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/iPublikuj/flash-messages.svg?style=flat-square)](https://scrutinizer-ci.com/g/iPublikuj/flash-messages/?branch=master)
[![Latest Stable Version](https://img.shields.io/packagist/v/ipub/flash-messages.svg?style=flat-square)](https://packagist.org/packages/ipub/flash-messages)
[![Composer Downloads](https://img.shields.io/packagist/dt/ipub/flash-messages.svg?style=flat-square)](https://packagist.org/packages/ipub/flash-messages)

Handling system flash messages for [Nette Framework](http://nette.org/)

## Installation

The best way to install ipub/flash-messages is using  [Composer](http://getcomposer.org/):

```json
{
	"require": {
		"ipub/flash-messages": "dev-master"
	}
}
```

or

```sh
$ composer require ipub/flash-messages:@dev
```

After that you have to register extension in config.neon.

```neon
extensions:
	visualPaginator: IPub\FlashMessages\DI\FlashMessagesExtension
```

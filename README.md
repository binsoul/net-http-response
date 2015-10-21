# net-http-response

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Total Downloads][ico-downloads]][link-downloads]

## Install

Via composer:

``` bash
$ composer require binsoul/net-http-response
```

This package provides PSR-7 ResponseInterface compatible response classes specialized for known HTTP status codes and body implementations which extend the PSR-7 StreamInterface with some useful methods. The included response emitter can output responses to different target environments.

## Usage

Output a text response on a web server: 

``` php
<?php

use BinSoul\Net\Http\Response\Body\Type\TextBody;
use BinSoul\Net\Http\Response\Emitter\DefaultEmitter;
use BinSoul\Net\Http\Response\Emitter\Target\SapiTarget;
use BinSoul\Net\Http\Response\Type\Success\OkResponse;

require 'vendor/autoload.php';

$response = new OkResponse(new TextBody('Hello world!'));
$emitter = new DefaultEmitter();
$emitter->emit($response, new SapiTarget());
```

Capture and display response headers and body: 

``` php
<?php

use BinSoul\Net\Http\Response\Body\Type\TextBody;
use BinSoul\Net\Http\Response\Emitter\DefaultEmitter;
use BinSoul\Net\Http\Response\Emitter\Target\CaptureTarget;
use BinSoul\Net\Http\Response\Type\Success\OkResponse;

require 'vendor/autoload.php';

// make var_export HTML friendly
function dump($value)
{
    return htmlentities(preg_replace("/\s+=>\s+/m", ' => ', var_export($value, true)));
}

$response = new OkResponse(new TextBody('Hello world!'));
$target = new CaptureTarget();
$emitter = new DefaultEmitter();
$emitter->emit($response, $target);

?>
<!DOCTYPE html>
<html>
<body>
    <h1>Headers</h1>
    <pre><?= dump($target->getHeaders()) ?></pre>
    <h1>Body</h1>
    <pre><?= dump($target->getBody()) ?></pre>
</body>
</html>
```

## Testing

``` bash
$ composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/binsoul/net-http-response.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/binsoul/net-http-response.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/binsoul/net-http-response
[link-downloads]: https://packagist.org/packages/binsoul/net-http-response
[link-author]: https://github.com/binsoul

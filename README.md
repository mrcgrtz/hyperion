# Hyperion

> Meta data :arrow_forward: JSON

![Packagist Version](https://img.shields.io/packagist/v/marcgoertz/hyperion)
![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/marcgoertz/hyperion)
![Packagist License](https://img.shields.io/packagist/l/marcgoertz/hyperion)


Super-simple meta data fetching using [php-ogp](https://github.com/mapkyca/php-ogp) and [php-mf2](https://github.com/microformats/php-mf2).

## Installation

I recommend using Composer for installing and using Hyperion:

```bash
composer require marcgoertz/hyperion
```

Of course you can also just require it in your scripts directly.

## Usage

```php
<?php

use Marcgoertz\Hyperion\Parser;

$hyperion = new Parser('https://example.com/');
if ($hyperion->hasMetadata()) {
    print_r($hyperion->toArray());
}
```

## License

WTFPL © Marc Görtz

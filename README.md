# Hyperion

> Meta data :arrow_forward: JSON

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

<?php

declare(strict_types=1);

namespace Marcgoertz\Hyperion;

use ogp\Parser as OgpParser;
use Mf2 as Mf2Parser;

/**
 * Hyperion Parser
 *
 * Meta data parser for URLs.
 * @author Marc Görtz
 * @license WTFPL
 */
final class Parser
{
    private const MF2_ITEMS = 'items';
    private const MF2_RELS = 'rels';
    private const MF2_REL_URLS = 'rel-urls';

    protected $metadata = [];

    /**
     * Fetch and parse a URL’s meta data.
     * @param string $url URL
     */
    public function __construct(string $url)
    {
        if ($input = \file_get_contents($url)) {
            $ogp = OgpParser::parse($input);
            $mf2 = Mf2Parser\parse($input, $url);

            if ($this->isAnyFilled($ogp, $mf2[self::MF2_ITEMS], $mf2[self::MF2_RELS], $mf2[self::MF2_REL_URLS])) {
                $this->metadata = $ogp;
                $this->metadata[self::MF2_ITEMS] = $mf2[self::MF2_ITEMS];
                $this->metadata[self::MF2_RELS] = $mf2[self::MF2_RELS];
                $this->metadata[self::MF2_REL_URLS] = $mf2[self::MF2_REL_URLS];
            }
        }
    }

    /**
     * Returns meta data as a JSON-encoded string.
     */
    public function toJSON(): string
    {
        return \json_encode($this->metadata);
    }

    /**
     * Returns meta data as array.
     */
    public function toArray(): array
    {
        return $this->metadata;
    }

    /**
     * Checks if meta data is available.
     */
    public function hasMetadata(): bool
    {
        return $this->isAnyFilled($this->metadata);
    }

    /**
     * Checks if any input is not empty.
     * @param mixed $vars Input data
     */
    private static function isAnyFilled(...$vars): bool
    {
        $filled = false;

        foreach ($vars as $var) {
            if ($filled = !empty($var)) {
                break;
            }
        }

        return $filled;
    }
}

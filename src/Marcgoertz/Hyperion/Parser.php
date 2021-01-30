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

    public function __construct(string $url)
    {
        if ($input = \file_get_contents($url)) {
            $ogp = OgpParser::parse($input);
            $mf2 = Mf2Parser\parse($input, $url);

            if ($this->isFilled($ogp, $mf2[self::MF2_ITEMS], $mf2[self::MF2_RELS], $mf2[self::MF2_REL_URLS])) {
                $this->metadata = $ogp;
                $this->metadata[self::MF2_ITEMS] = $mf2[self::MF2_ITEMS];
                $this->metadata[self::MF2_RELS] = $mf2[self::MF2_RELS];
                $this->metadata[self::MF2_REL_URLS] = $mf2[self::MF2_REL_URLS];
            }
        }
    }

    public function toJSON(): string
    {
        return \json_encode($this->metadata);
    }

    public function toArray(): array
    {
        return $this->metadata;
    }

    public function hasMetadata(): bool
    {
        return $this->isFilled($this->metadata);
    }

    private static function isFilled(...$vars): bool
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

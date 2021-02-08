<?php

declare(strict_types=1);

namespace Marcgoertz\Hyperion;

use JsonException;
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

    protected $url;
    protected $metadata = [];

    /**
     * Fetch and parse a URL’s meta data.
     * @param string $url URL
     */
    public function __construct(string $url)
    {
        $this->url = $url;

        if ($input = $this->fetch($this->url)) {
            $ogp = OgpParser::parse($input);
            $mf2 = Mf2Parser\parse($input, $this->url);

            if ($this->isAnyFilled($ogp, $mf2[self::MF2_ITEMS], $mf2[self::MF2_RELS], $mf2[self::MF2_REL_URLS])) {
                $this->metadata = $ogp;
                $this->metadata[self::MF2_ITEMS] = $mf2[self::MF2_ITEMS];
                $this->metadata[self::MF2_RELS] = $mf2[self::MF2_RELS];
                $this->metadata[self::MF2_REL_URLS] = $mf2[self::MF2_REL_URLS];
            }
        }
    }

    /**
     * Fetches the URL (following up to 5 redirects) and, if the
     * Content-Type appears to be text/html, returns content.
     */
    protected function fetch()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: text/html'
        ]);
        $html = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        if (strpos(strtolower($info['content_type']), 'html') === false) {
            // content was not delivered as HTML, do not attempt to parse it
            return null;
        }

        # update URL to ensure the final one is used to resolve relative URLs
        $this->url = $info['url'];

        return $html;
    }

    /**
     * Returns meta data as a JSON-encoded string.
     */
    public function toJSON(): string
    {
        try {
            return \json_encode($this->metadata, JSON_INVALID_UTF8_IGNORE + JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            return \json_encode([
                'error' => $e->getMessage(),
            ]);
        }
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
    protected static function isAnyFilled(...$vars): bool
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

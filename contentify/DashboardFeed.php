<?php

namespace Contentify;

class DashboardFeed
{
    /**
     * Decode and normalize messages from one remote dashboard feed.
     *
     * @param string $content
     * @param string $fallbackUrl
     * @return array|null Null indicates invalid JSON or an invalid root value.
     */
    public static function decode(string $content, string $fallbackUrl): ?array
    {
        $items = json_decode($content);

        if (! is_array($items)) {
            return null;
        }

        $messages = [];

        foreach ($items as $item) {
            if (! is_object($item) or ! isset($item->text, $item->timestamp)) {
                continue;
            }

            $text = trim((string) $item->text);
            $timestamp = filter_var($item->timestamp, FILTER_VALIDATE_INT);

            if ($text === '' or $timestamp === false or $timestamp < 1) {
                continue;
            }

            $url = isset($item->url) ? (string) $item->url : '';
            if (! self::isHttpUrl($url)) {
                $url = $fallbackUrl;
            }

            $icon = isset($item->icon) ? (string) $item->icon : 'info-circle';
            if (! preg_match('/\A[a-z0-9-]+\z/', $icon)) {
                $icon = 'info-circle';
            }

            $messages[] = (object) [
                'url'       => $url,
                'text'      => $text,
                'timestamp' => (int) $timestamp,
                'icon'      => $icon,
            ];
        }

        return $messages;
    }

    protected static function isHttpUrl(string $url): bool
    {
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            return false;
        }

        return in_array(strtolower((string) parse_url($url, PHP_URL_SCHEME)), ['http', 'https'], true);
    }
}

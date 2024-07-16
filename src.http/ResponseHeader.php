<?php

/*
 * Copyright (c) delight.im (https://www.delight.im/)
 * Licensed under the MIT License (https://opensource.org/licenses/MIT)
 */

namespace Arris\DelightAuth\Http;

/** HTTP response headers sent by the server */
final class ResponseHeader
{

    private function __construct()
    {
    }

    /**
     * Returns the header with the specified name (and optional value prefix)
     *
     * @param string $name the name of the header
     * @param string $valuePrefix the optional string to match at the beginning of the header's value
     * @return string|null the header (if found) or `null`
     */
    public static function get(string $name, string $valuePrefix = ''): ?string
    {
        if (empty($name)) {
            return null;
        }

        $nameLength = \strlen($name);
        $headers = \headers_list();

        foreach ($headers as $header) {
            if (\strcasecmp(\substr($header, 0, $nameLength + 1), ($name . ':')) === 0) {
                $headerValue = \trim(\substr($header, $nameLength + 1), "\t ");

                if (empty($valuePrefix) || str_starts_with($headerValue, $valuePrefix)) {
                    return $header;
                }
            }
        }

        return null;
    }

    /**
     * Returns the value of the header with the specified name (and optional value prefix)
     *
     * @param string $name the name of the header
     * @param string $valuePrefix the optional string to match at the beginning of the header's value
     * @return string|null the value of the header (if found) or `null`
     */
    public static function getValue(string $name, string $valuePrefix = ''): ?string
    {
        $header = ResponseHeader::get($name, $valuePrefix);

        if (!empty($header)) {
            $nameLength = \strlen($name);
            $headerValue = \substr($header, $nameLength + 1);
            $headerValue = \trim($headerValue, "\t ");

            return $headerValue;
        } else {
            return null;
        }
    }

    /**
     * Sets the header with the specified name and value
     *
     * If another header with the same name has already been set previously, that header will be overwritten
     *
     * @param string $name the name of the header
     * @param string $value the corresponding value for the header
     */
    public static function set(string $name, string $value): void
    {
        \header($name . ': ' . $value, true);
    }

    /**
     * Adds the header with the specified name and value
     *
     * If another header with the same name has already been set previously, both headers (or header values) will be sent
     *
     * @param string $name the name of the header
     * @param string $value the corresponding value for the header
     */
    public static function add(string $name, string $value): void
    {
        \header($name . ': ' . $value, false);
    }

    /**
     * Removes the header with the specified name (and optional value prefix)
     *
     * @param string $name the name of the header
     * @param string $valuePrefix the optional string to match at the beginning of the header's value
     * @return bool whether a header, as specified, has been found and removed
     */
    public static function remove(string $name, string $valuePrefix = ''): bool
    {
        return ResponseHeader::take($name, $valuePrefix) !== null;
    }

    /**
     * Returns and removes the header with the specified name (and optional value prefix)
     *
     * @param string $name the name of the header
     * @param string $valuePrefix the optional string to match at the beginning of the header's value
     * @return string|null the header (if found) or `null`
     */
    public static function take(string $name, string $valuePrefix = ''): ?string
    {
        if (empty($name)) {
            return null;
        }

        $nameLength = \strlen($name);
        $headers = \headers_list();

        $first = null;
        $homonyms = [];

        foreach ($headers as $header) {
            if (\strcasecmp(\substr($header, 0, $nameLength + 1), ($name . ':')) === 0) {
                $headerValue = \trim(\substr($header, $nameLength + 1), "\t ");

                if ((empty($valuePrefix) || str_starts_with($headerValue, $valuePrefix)) && $first === null) {
                    $first = $header;
                } else {
                    $homonyms[] = $header;
                }
            }
        }

        if ($first !== null) {
            \header_remove($name);

            foreach ($homonyms as $homonym) {
                \header($homonym, false);
            }
        }

        return $first;
    }

    /**
     * Returns the value of and removes the header with the specified name (and optional value prefix)
     *
     * @param string $name the name of the header
     * @param string $valuePrefix the optional string to match at the beginning of the header's value
     * @return string|null the value of the header (if found) or `null`
     */
    public static function takeValue(string $name, string $valuePrefix = ''): ?string
    {
        $header = ResponseHeader::take($name, $valuePrefix);

        if (!empty($header)) {
            $nameLength = \strlen($name);
            $headerValue = \substr($header, $nameLength + 1);
            $headerValue = \trim($headerValue, "\t ");

            return $headerValue;
        } else {
            return null;
        }
    }

}

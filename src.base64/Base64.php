<?php

/*
 * PHP-Base64 (https://github.com/delight-im/PHP-Base64)
 * Copyright (c) delight.im (https://www.delight.im/)
 * Licensed under the MIT License (https://opensource.org/licenses/MIT)
 */

namespace Arris\DelightAuth\Base64;

use Arris\DelightAuth\Base64\Throwable\DecodingError;
use Arris\DelightAuth\Base64\Throwable\EncodingError;

/** Utilities for encoding and decoding data using Base64 and variants thereof */
final class Base64
{

    /**
     * The last three characters from the alphabet of the standard implementation
     *
     * @var string
     */
    public const LAST_THREE_STANDARD = '+/=';

    /**
     * The last three characters from the alphabet of the URL-safe implementation
     *
     * @var string
     */
    public const LAST_THREE_URL_SAFE = '-_~';

    /**
     * Encodes the supplied data to a URL-safe variant of Base64
     *
     * @param mixed $data
     * @return string
     * @throws EncodingError if the input has been invalid
     */
    public static function encodeUrlSafe($data): string
    {
        $encoded = self::encode($data);

        return \strtr(
            $encoded,
            self::LAST_THREE_STANDARD,
            self::LAST_THREE_URL_SAFE
        );
    }

    /**
     * Encodes the supplied data to Base64
     *
     * @param mixed $data
     * @return string
     * @throws EncodingError if the input has been invalid
     */
    public static function encode($data): string
    {
        $encoded = \base64_encode($data);

        if ($encoded === false) {
            throw new EncodingError();
        }

        return $encoded;
    }

    /**
     * Encodes the supplied data to a URL-safe variant of Base64 without padding
     *
     * @param mixed $data
     * @return string
     * @throws EncodingError if the input has been invalid
     */
    public static function encodeUrlSafeWithoutPadding($data): string
    {
        $encoded = self::encode($data);

        $encoded = \rtrim(
            $encoded,
            \substr(self::LAST_THREE_STANDARD, -1)
        );

        return \strtr(
            $encoded,
            \substr(self::LAST_THREE_STANDARD, 0, -1),
            \substr(self::LAST_THREE_URL_SAFE, 0, -1)
        );
    }

    /**
     * Decodes the supplied data from a URL-safe variant of Base64 without padding
     *
     * @param string $data
     * @return mixed
     * @throws DecodingError if the input has been invalid
     */
    public static function decodeUrlSafeWithoutPadding(string $data)
    {
        return self::decodeUrlSafe($data);
    }

    /**
     * Decodes the supplied data from a URL-safe variant of Base64
     *
     * @param string $data
     * @return mixed
     * @throws DecodingError if the input has been invalid
     */
    public static function decodeUrlSafe(string $data)
    {
        $data = \strtr(
            $data,
            self::LAST_THREE_URL_SAFE,
            self::LAST_THREE_STANDARD
        );

        return self::decode($data);
    }

    /**
     * Decodes the supplied data from Base64
     *
     * @param string $data
     * @return mixed
     * @throws DecodingError if the input has been invalid
     */
    public static function decode(string $data)
    {
        $decoded = \base64_decode($data, true);

        if ($decoded === false) {
            throw new DecodingError();
        }

        return $decoded;
    }

}

<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\PrintNode\Util;

use const E_USER_WARNING;

use Rawilk\Printing\Exceptions\InvalidArgument;

/**
 * @internal
 */
abstract class Util
{
    private static ?bool $isMbstringAvailable = null;

    /**
     * Determine whether the provided array (or other) is a list rather than
     * a dictionary. A list is defined as an array for which all the keys
     * are consecutive integers starting at 0. Empty arrays are considered
     * to be lists.
     */
    public static function isList(mixed $array): bool
    {
        if (! is_array($array)) {
            return false;
        }

        return array_is_list($array);
    }

    /**
     * Converts a response from the PrintNode API to the corresponding PHP object.
     *
     * @param  array|mixed  $response  the response from the PrintNode API
     * @param  null|class-string<\Rawilk\Printing\Api\PrintNode\PrintNodeObject>  $expectedResource  the expected resource class for the response
     */
    public static function convertToPrintNodeObject(mixed $response, array|null|RequestOptions $opts, ?string $expectedResource = null): mixed
    {
        if (self::isList($response)) {
            $mapped = [];

            foreach ($response as $responseValue) {
                $mapped[] = self::convertToPrintNodeObject($responseValue, $opts, $expectedResource);
            }

            return $mapped;
        }

        if (is_array($response) && $expectedResource !== null) {
            throw_unless(
                class_exists($expectedResource),
                InvalidArgument::class,
                'PrintNode resource class "' . $expectedResource . '" does not exist',
            );

            return $expectedResource::make($response, $opts);
        }

        return $response;
    }

    public static function normalizeId(mixed $id): array
    {
        if (is_array($id)) {
            if (! isset($id['id'])) {
                return [null, $id];
            }

            $params = $id;
            $id = $params['id'];
            unset($params['id']);
        } else {
            $params = [];
        }

        return [$id, $params];
    }

    /**
     * @param  mixed|string  $value  a string to UTF-8 encode
     * @return mixed|string the UTF-8 encoded string, or the object passed in if it wasn't a string
     */
    public static function utf8(mixed $value): mixed
    {
        if (self::$isMbstringAvailable === null) {
            self::$isMbstringAvailable = function_exists('mb_detect_encoding')
                && function_exists('mb_convert_encoding');

            if (! self::$isMbstringAvailable) {
                trigger_error(
                    <<<'TXT'
                    It looks like the mbstring extension is not enabled.
                    UTF-8 strings will not be properly encoded. Ask your
                    system administrator to enable the mbstring extension.
                    TXT,
                    E_USER_WARNING,
                );
            }
        }

        if (
            is_string($value) &&
            self::$isMbstringAvailable &&
            mb_detect_encoding($value, 'UTF-8', true) !== 'UTF-8'
        ) {
            return mb_convert_encoding($value, 'UTF-8', 'ISO-8859-1');
        }

        return $value;
    }
}

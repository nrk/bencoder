<?php

namespace Bencoder;

class Bencode {
    const VERSION = '1.0.0';

    /* encoding */

    public static function serialize($array) {
        $serialized = "";
        self::decideEncode($array, $serialized);
        return $serialized;
    }

    private static function decideEncode($object, &$serialized) {
        switch ($type = gettype($object)) {
            case 'integer':
                $serialized .= "i{$object}e";
                break;
            case 'string':
                self::encodeString($object, $serialized);
                break;
            case 'array':
                $function = self::getArraySerializer($object);
                self::$function($object, $serialized);
                break;
            case 'boolean':
                if ($object === true) {
                    $serialized .= "de";
                }
                break;
            default:
                throw new SerializationException("Invalid type for serialization: $type", $object);
        }
    }

    private static function encodeString($string, &$serialized) {
        $serialized .= strlen($string) . ":$string";
    }

    private static function encodeList($list, &$serialized) {
        if (empty($list)) {
            $serialized .= "le";
            return;
        }
        $serialized .= "l";
        for ($i = 0; isset($list[$i]); $i++) {
            self::decideEncode($list[$i], $serialized);
        }
        $serialized .= "e";
    }

    private static function encodeDictionary($dictionary, &$serialized) {
        if (is_bool($dictionary)) {
            $serialized .= "de";
            return;
        }
        $serialized .= "d";
        foreach(self::sortDictionary($dictionary) as $key => $value) {
            self::encodeString($key, $serialized);
            self::decideEncode($value, $serialized);
        }
        $serialized .= "e";
    }

    private static function getArraySerializer($array) {
        if (empty($array)) {
            return 'encodeList';
        }
        foreach (array_keys($array) as $key) {
            if (is_string($key)) {
                return 'encodeDictionary';
            }
        }
        return 'encodeList';
    }

    private static function sortDictionary($dictionary) {
        if (!ksort($dictionary, SORT_STRING)) {
            throw new SerializationException('Failed to sort dictionary', $dictionary);
        }
        return $dictionary;
    }

    /* decoding */

    public static function unserialize($buffer) {
        $offset = 0;
        return self::decodeEntry($buffer, $offset);
    }

    private static function decodeEntry($buffer, &$offset) {
        $offsetMarker = $offset;
        switch ($byte = $buffer[$offset++]) {
            case 'd':
                $dictionary = array();
                for (;;) {
                    if ($buffer[$offset] === 'e') {
                        $offset++;
                        break;
                    }
                    $key = self::decodeEntry($buffer, $offset);
                    if (!is_string($key) && !is_numeric($key)) {
                        throw new DeserializationException("One of the dictionary keys is not a string or an integer: " . gettype($key), $offset);
                    }
                    $dictionary[$key] = self::decodeEntry($buffer, $offset);
                }
                return $dictionary;
            case 'l':
                $list = array();
                for (;;) {
                    if ($buffer[$offset] === 'e') {
                        $offset++;
                        break;
                    }
                    $list[] = self::decodeEntry($buffer, $offset);
                }
                return $list;
            case 'e':
            case 'i':
                return self::getIntegerFromBuffer($buffer, $offset);
            case '0':
            case '1':
            case '2':
            case '3':
            case '4':
            case '5':
            case '6':
            case '7':
            case '8':
            case '9':
                $number = self::getStringFromBuffer($buffer, $offsetMarker);
                $offset = $offsetMarker;
                return $number;
            default:
                throw new DeserializationException("Unknown prefix: $byte", $offset);
        }
    }

    private static function getStringFromBuffer($buffer, &$offset) {
        if (($length = self::getIntegerFromBuffer($buffer, $offset, ':')) < 0) {
            throw new DeserializationException("Invalid string length: $length", $offset);
        }
        $string = substr($buffer, $offset, $length);
        $offset += $length;
        return $string;
    }

    private static function getIntegerFromBuffer($buffer, &$offset, $delimiter = 'e') {
        $numeric = '';
        $offsetMarker = $offset;
        while (($byte = $buffer[$offset++]) !== $delimiter) {
            $numeric .= $byte;
        }
        $integer = (int) $numeric;
        if ($numeric != $integer) {
            throw new DeserializationException("Invalid integer: $numeric", $offsetMarker);
        }
        return $integer;
    }
}

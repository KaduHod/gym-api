<?php

namespace Utils;

class General {
    static function get(string $key, mixed $value) {
        if(!is_array($value) && !is_object($value)) {
            throw new \Exception("Value must be an array or object");
        }
        if(is_array($value)) {
            return array_key_exists($key, $value) ? $value[$key] : null;
        }
        return property_exists($value, $key) ? $value->$key : null;
    }
}

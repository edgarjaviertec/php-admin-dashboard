<?php

if (!function_exists('config')) {
    function config($key, $fallback = null)
    {
        static $config;
        if (is_null($config)) {
            $config = include 'configs.php';
        }
        return array_key_exists($key, $config)
            ? $config[$key]
            : $fallback;
    }
}

if (!function_exists('env')) {
    function env($key, $fallback = null)
    {
        $env_array = $_ENV;
        $value = null;
        if (array_key_exists($key, $env_array)) {
            $value = $env_array[$key];
            if (preg_match('/\A([\'"])(.*)\1\z/', $value, $matches)) {
                $value = $matches[2];
            }
            switch (strtolower($value)) {
                case 'true':
                case '(true)':
                    $value = true;
                    break;
                case 'false':
                case '(false)':
                    $value = false;
                    break;
                case 'empty':
                case '(empty)':
                    $value = '';
                    break;
                case 'null':
                case '(null)':
                    $value = null;
                    break;
            }
            return $value;
        } else {
            return $fallback;
        }
    }
}
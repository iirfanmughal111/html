<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Util;

class Arr extends \XF\Util\Arr
{
    /**
     * @param mixed $array
     * @param string $key
     * @param null|mixed $default
     * @return mixed|null
     */
    public static function get($array, string $key, $default = null)
    {
        if (!is_array($array)) {
            return $key;
        }

        if (array_key_exists($key, $array)) {
            return $array[$key];
        }

        $segments = explode('.', $key);

        while ($segments) {
            /** @var mixed $segment */
            $segment = array_shift($segments);
            if ($segment === null) {
                return $default;
            }

            if (array_key_exists($segment, $array)) {
                $array = $array[$segment];
            } else {
                return $default;
            }
        }

        return $array;
    }
}

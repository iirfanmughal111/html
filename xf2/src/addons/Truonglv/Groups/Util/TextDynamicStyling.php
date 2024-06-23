<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Util;

class TextDynamicStyling
{
    /**
     * @var array
     */
    protected static $textDynamicStylingCache = [];
    /**
     * @var string
     */
    protected static $letterRegex = '/[^\(\)\{\}\[\]\<\>\-\.\+\:\=\*\!\|\^\/\\\\\'`"_,#~ ]/u';

    /**
     * @param string $string
     * @return mixed
     */
    public static function getDynamicStyling(string $string)
    {
        if (!isset(static::$textDynamicStylingCache[$string])) {
            $bytes = md5($string, true);

            $r = dechex(intval(round(5 * ord($bytes[0]) / 255) * 0x33));
            $g = dechex(intval(round(5 * ord($bytes[1]) / 255) * 0x33));
            $b = dechex(intval(round(5 * ord($bytes[2]) / 255) * 0x33));

            $hexBgColor = sprintf('%02s%02s%02s', $r, $g, $b);

            $hslBgColor = \XF\Util\Color::hexToHsl($hexBgColor);

            $bgChanged = false;
            if ($hslBgColor[1] > 60) {
                $hslBgColor[1] = 60;
                $bgChanged = true;
            } elseif ($hslBgColor[1] < 15) {
                $hslBgColor[1] = 15;
                $bgChanged = true;
            }

            if ($hslBgColor[2] > 85) {
                $hslBgColor[2] = 85;
                $bgChanged = true;
            } elseif ($hslBgColor[2] < 15) {
                $hslBgColor[2] = 15;
                $bgChanged = true;
            }

            if ($bgChanged) {
                $hexBgColor = \XF\Util\Color::hslToHex($hslBgColor);
            }

            $hslColor = \XF\Util\Color::darkenOrLightenHsl($hslBgColor, 35);
            $hexColor = \XF\Util\Color::hslToHex($hslColor);

            $bgColor = '#' . $hexBgColor;
            $color = '#' . $hexColor;

            if (preg_match(static::$letterRegex, $string, $match) === 1) {
                $innerContent = htmlspecialchars(utf8_strtoupper($match[0]));
            } else {
                $innerContent = '?';
            }

            static::$textDynamicStylingCache[$string] = [
                'bgColor' => $bgColor,
                'color' => $color,
                'innerContent' => $innerContent
            ];
        }

        return static::$textDynamicStylingCache[$string];
    }
}

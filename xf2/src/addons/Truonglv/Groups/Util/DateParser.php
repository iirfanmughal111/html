<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Util;

class DateParser
{
    const DATE_FORMAT = 'Y-m-d H:i:s';

    /**
     * @param string $date
     * @param null|string|\DateTimeZone $timeZone
     * @return int
     * @throws \Exception
     */
    public static function fromString(string $date, $timeZone = null)
    {
        if ($timeZone === null) {
            $timeZone = \XF::options()->guestTimeZone;
        }

        $tz = $timeZone instanceof \DateTimeZone
            ? $timeZone
            : new \DateTimeZone($timeZone);

        $dt = \DateTime::createFromFormat(self::DATE_FORMAT, $date, $tz);
        if ($dt === false) {
            throw new \InvalidArgumentException('Cannot parse date provided. $date=' . $date);
        }

        return $dt->getTimestamp();
    }

    /**
     * @param int $timestamp
     * @param null|string $timeZone
     * @return string
     * @throws \Exception
     */
    public static function toISO8601(int $timestamp, $timeZone = null)
    {
        $timeZone = $timeZone !== null ? $timeZone : \XF::visitor()->timezone;

        $dt = new \DateTime('@' . $timestamp);

        try {
            $tz = new \DateTimeZone($timeZone);
        } catch (\Exception $e) {
            // force to use current visitor timezone
            $tz = new \DateTimeZone(\XF::visitor()->timezone);
        }
        $dt->setTimezone($tz);

        return $dt->format(\DATE_ISO8601);
    }
}

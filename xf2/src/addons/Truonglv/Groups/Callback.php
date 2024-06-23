<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups;

use XF;
use XF\PrintableException;
use XF\Template\Templater;
use InvalidArgumentException;
use Truonglv\Groups\Data\Badge;
use Truonglv\Groups\Entity\Group;
use Truonglv\Groups\Util\TextDynamicStyling;

class Callback
{
    /**
     * @param string $null
     * @param array $params
     * @param Templater $templater
     * @return mixed|string
     * @throws PrintableException
     */
    public static function renderAvatar($null, array $params, Templater $templater)
    {
        $params = array_replace([
            'group' => null,
            'full' => true,
            'forceImage' => false
        ], $params);

        if (!($params['group'] instanceof Group)) {
            throw new PrintableException('Param group must be instanced of (' . Group::class . ')');
        }

        $group = $params['group'];

        $avatarText = null;
        $attrs = [];

        if ($group->AvatarAttachment === null) {
            if ($params['forceImage'] === true) {
                return null;
            }

            $textDynamicStyling = TextDynamicStyling::getDynamicStyling($group->name);
            list($bgColor, $color, $avatarText) = array_values($textDynamicStyling);

            $attrs[] = 'style="background-color:' . $bgColor . ';color:' . $color . '"';
        }

        return $templater->renderMacro('public:tlg_group_macros', 'avatar_html', [
            'group' => $params['group'],
            'full' => $params['full'],
            'avatarText' => $avatarText,
            'attrs' => $templater->preEscaped(implode(' ', $attrs), 'html')
        ]);
    }

    /**
     * @param string $null
     * @param array $params
     * @param Templater $templater
     * @return mixed|string
     * @throws PrintableException
     */
    public static function renderCover($null, array $params, Templater $templater)
    {
        $params = array_replace([
            'group' => null,
            'forceHeight' => 0,
            'isRepositioning' => false
        ], $params);

        if (!($params['group'] instanceof Group)) {
            throw new PrintableException('Param group must be instanced of (' . Group::class . ')');
        }

        $group = $params['group'];
        $attrs = [];
        $imgAttrs = [
            'data-debug="' . (XF::$debugMode ? 1 : 0) . '"'
        ];
        $forceHeight = strval($params['forceHeight']);
        $isRepositioning = strval($params['isRepositioning']) === '1';

        if ($group->CoverAttachment !== null) {
            $imgAttrs[] = 'data-width="' . $group->CoverAttachment->width . '"';
            $imgAttrs[] = 'data-height="' . $group->CoverAttachment->height . '"';
            $imgAttrs[] = 'alt="' . htmlspecialchars($group->name) . '"';
            $top = $group->getCoverCropData('y', 0);
            if ($forceHeight > 0) {
                $top = preg_replace('/[^\-0-9\.+]/', '', $top);
                $top = (float) $top;

                $cropHeight = (int) $group->getCoverCropData('h');
                $cropHeight = max(200, $cropHeight);

                $ratio = $forceHeight/$cropHeight;
                $top = ($top * $ratio) . 'px';
            }

            $imgAttrs[] = 'style="top:' . $top . '"';
            $coverUrl = $group->getCoverUrl(true) !== null
                ? htmlspecialchars($group->getCoverUrl(true))
                : '';
            if ($coverUrl === '') {
                throw new InvalidArgumentException('Group did not have cover!');
            }
            $imgAttrs[] = ($isRepositioning || App::getOption('lazyLoadCover') == 0)
                ? ('src="' . $coverUrl . '"')
                : ('data-src="' . $coverUrl . '"');
        } else {
            list($bgColor, $color) = array_values(TextDynamicStyling::getDynamicStyling($group->name));
            $attrs[] = 'style="background-color:' . $bgColor . ';color:' . $color . '"';
        }

        return $templater->renderMacro('public:tlg_group_macros', 'cover_html', [
            'group' => $group,
            'repositioning' => $isRepositioning,
            'attrs' => $templater->preEscaped(implode(' ', $attrs), 'html'),
            'imgAttrs' => $templater->preEscaped(implode(' ', $imgAttrs), 'html'),
            'forceHeight' => ($forceHeight > 0) ? $forceHeight : null,
            'lazy' => App::getOption('lazyLoadCover'),
        ]);
    }

    /**
     * @param mixed $groupId
     * @param array $params
     * @param Templater $templater
     * @return string|null
     */
    public static function renderBadge($groupId, array $params, Templater $templater): ?string
    {
        /** @var Badge $badgeData */
        $badgeData = XF::app()->data('Truonglv\Groups:Badge');
        $group = $badgeData->getGroup($groupId);
        if ($group === null) {
            return null;
        }

        if (!App::isEnabledBadge(XF::visitor())) {
            return null;
        }

        return $templater->renderMacro('public:tlg_group_macros', 'user_info_badge', [
            'group' => $group,
        ]);
    }

    public static function renderHourSelect(string $name, array $params): string
    {
        $hours = [];
        $seconds = 0;
        while ($seconds < 86400) {
            $hour = floor($seconds / 3600);
            $minute = floor(($seconds - $hour * 3600) / 60);
            $key = sprintf('%02d:%02d', $hour, $minute);

            if (static::has24HTimeFormat()) {
                $hours[$key] = sprintf(
                    '%02d:%02d',
                    $hour,
                    $minute
                );
            } else {
                if ($seconds >= 12 * 3600) {
                    $hour -= 12;
                    $hours[$key] = sprintf(
                        '%02d:%02d %s',
                        $hour === 0.0 ? 12 : $hour,
                        $minute,
                        XF::phrase('time_pm_upper')
                    );
                } else {
                    $hours[$key] = sprintf(
                        '%02d:%02d %s',
                        $hour,
                        $minute,
                        XF::phrase('time_am_upper')
                    );
                }
            }

            $seconds += 15 * 60;
        }

        $templater = XF::app()->templater();
        $controlOptions = [
            'name' => $name,
            'value' => $params['date']['hour'],
            'class' => 'date-input--field',
        ];

        return $templater->formSelect($controlOptions, $templater->mergeChoiceOptions([], $hours));
    }

    /**
     * @return boolean
     */
    public static function has24HTimeFormat()
    {
        $visitor = XF::visitor();
        $language = XF::app()->language($visitor->language_id);

        $timeFormat = $language->offsetGet('time_format');

        return strpos($timeFormat, 'g') === false;
    }

    /**
     * @param int $time
     * @param array $params
     * @return float|int
     */
    public static function getCountdownUnit($time, array $params)
    {
        if (!isset($params['unit'])) {
            throw new InvalidArgumentException('Must be set `unit`');
        }

        $diff = $time - XF::$time;
        if ($diff <= 0) {
            return 0;
        }

        switch ($params['unit']) {
            case 'days':
                return floor($diff / 86400);
            case 'hours':
                $diff -= self::getCountdownUnit($time, ['unit' => 'days']) * 86400;

                return floor($diff / 3600);
            case 'minutes':
                $diff -= self::getCountdownUnit($time, ['unit' => 'days']) * 86400;
                $diff -= self::getCountdownUnit($time, ['unit' => 'hours']) * 3600;

                return floor($diff / 60);
            case 'seconds':
                $diff -= self::getCountdownUnit($time, ['unit' => 'days']) * 86400;
                $diff -= self::getCountdownUnit($time, ['unit' => 'hours']) * 3600;
                $diff -= self::getCountdownUnit($time, ['unit' => 'minutes']) * 60;

                return $diff;
        }

        return 0;
    }
}

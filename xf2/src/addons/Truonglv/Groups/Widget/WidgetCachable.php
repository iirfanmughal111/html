<?php

namespace Truonglv\Groups\Widget;

use XF;
use function json_encode;

trait WidgetCachable
{
    abstract protected function getCacheId(): string;

    protected function getCacheData(): ?array
    {
        $cache = XF::app()->cache();
        if ($cache === null) {
            return null;
        }

        $data = $cache->fetch($this->getCacheId());
        if ($data === false) {
            return null;
        }

        return json_decode($data, true);
    }

    protected function saveData(array $data, int $expires): void
    {
        $cache = XF::app()->cache();
        if ($cache === null) {
            return;
        }

        $cache->save($this->getCacheId(), json_encode($data), $expires);
    }
}

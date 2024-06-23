<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Data;

use function max;
use function count;
use XFMG\Entity\MediaItem;

class Album
{
    /**
     * @var array
     */
    protected $data = [];
    /**
     * @var array
     */
    protected $total = [];
    /**
     * @var int
     */
    protected $remainTotal = 0;

    /**
     * @param \XFMG\Entity\Album $album
     * @param MediaItem $mediaItem
     * @return void
     */
    public function setMediaItem(\XFMG\Entity\Album $album, MediaItem $mediaItem)
    {
        $this->data[$album->album_id][$mediaItem->media_id] = $mediaItem;
        $this->total[$album->album_id] = $album->media_count;
    }

    /**
     * @param int $albumId
     * @return array
     */
    public function getMediaItems($albumId)
    {
        if (isset($this->data[$albumId])) {
            $this->remainTotal = (int) max(0, $this->total[$albumId] - count($this->data[$albumId]));

            return $this->data[$albumId];
        } else {
            $this->remainTotal = 0;

            return [];
        }
    }

    /**
     * @param int $albumId
     * @return int
     */
    public function count($albumId)
    {
        return count($this->getMediaItems($albumId));
    }

    /**
     * @return int|null
     */
    public function getRemainTotal()
    {
        return $this->remainTotal > 0 ? $this->remainTotal : null;
    }
}

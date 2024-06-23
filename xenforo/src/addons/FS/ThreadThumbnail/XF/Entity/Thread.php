<?php

namespace FS\ThreadThumbnail\XF\Entity;

use XF\Mvc\Entity\Structure;

class Thread extends XFCP_Thread
{
    
     public static function getStructure(Structure $structure)
    {
        $structure = parent::getStructure($structure);

       
        $structure->columns['thumbnail_title'] =  ['type' => self::STR, 'default' => null];
        $structure->columns['thumbnail_ext'] =  ['type' => self::STR, 'default' => null];


        return $structure;
    }
    
    public function getAbstractedCustomThumbnailSvgPath($extension) {

        $thread_id = $this->thread_id;

        return sprintf('data://ThreadThumbnail/%d/%d.' . $extension, floor($thread_id / 1000), $thread_id);
    }

    public function getThumbnailPath($canonical = true) {

        $thread_id = $this->thread_id;

        $file_ex = $this->thumbnail_ext;

        $path = sprintf('ThreadThumbnail/%d/%d.' . $file_ex, floor($thread_id / 1000), $thread_id);

        $path = \XF::app()->applyExternalDataUrl($path, $canonical);

        $path .= "?" . \xf::$time;

        return $path;
    }

    public function getThumbnailExit() {

        $file_ex = $this->thumbnail_ext;

        $thread_id = $this->thread_id;

        $fileexit = sprintf('data://ThreadThumbnail/%d/%d.' . $file_ex, floor($thread_id / 1000), $thread_id);

        return \XF\Util\File::abstractedPathExists($fileexit);
    }
}
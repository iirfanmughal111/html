<?php

namespace FS\ThreadChangeArticle\XF\Entity;

use XF\Mvc\Entity\Structure;

class Thread extends XFCP_Thread
{
    
     public static function getStructure(Structure $structure)
    {
        $structure = parent::getStructure($structure);

        $structure->columns['is_view_change'] =  ['type' => self::UINT, 'default' => 0];
        $structure->columns['img_ex'] =  ['type' => self::STR, 'default' => null];

        return $structure;
    }
    
    public function getAbstractedCustomDepositSvgPath($extension) {

        $thread_id = $this->thread_id;

        return sprintf('data://ThreadView/%d/%d.' . $extension, floor($thread_id / 1000), $thread_id);
    }

    public function getImgPath($canonical = true) {

        $thread_id = $this->thread_id;

        $file_ex = $this->img_ex;

        $path = sprintf('ThreadView/%d/%d.' . $file_ex, floor($thread_id / 1000), $thread_id);

        $path = \XF::app()->applyExternalDataUrl($path, $canonical);

        $path .= "?" . \xf::$time;

        return $path;
    }

    public function getimageExit() {

        $file_ex = $this->img_ex;

        $thread_id = $this->thread_id;

        $fileexit = sprintf('data://ThreadView/%d/%d.' . $file_ex, floor($thread_id / 1000), $thread_id);

      
        return \XF\Util\File::abstractedPathExists($fileexit);
    }
}

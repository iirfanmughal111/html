<?php

namespace ExtendMediaUpload\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

class EWRMedia extends \EWR\Medio\Entity\Media {

    public static function getStructure(Structure $structure) {

        $structure = parent::getStructure($structure);

        $structure->columns['uniq_code'] = ['type' => self::STR, 'length' => '100', 'default' => 0];
        
        $structure->columns['status'] = ['type' => self::STR, 'length' => '100', 'default' => 'process'];

        $structure->columns["service_id"]["required"] = false;
        $structure->columns["service_val1"]["required"] = false;
        $structure->columns["media_duration"]["required"] = false;

        return $structure;
    }

    public function getEmbed() {

        if ($this->uniq_code) {


            $uniq_code = $this->uniq_code;

//            $videoUrl = \XF::options()->Domainbunnynet . '/play/' . \XF::options()->videolibraryid . '/' . $this->uniq_code;

            $videoLibraryId = \XF::options()->videolibraryid;

            $url = '<iframe src="https://iframe.mediadelivery.net/embed/' . $videoLibraryId . '/' . $uniq_code . '?autoplay=true" loading="lazy" 
                                                              style="border: none;  height: 100%; width: 100%;" allow="accelerometer; gyroscope; autoplay; encrypted-media; picture-in-picture;" allowfullscreen="true"></iframe>';
        } else {
            $newValues = [
                '1', 'true', 'yes', '0', 'false', 'no',
                $this->image, $this->service_val1, $this->service_val2,
                \XF::options()->boardUrl, $_SERVER['HTTP_HOST'],
                $this->app()->templater->getStyle() ? $this->app()->templater->getStyle()->getProperty('styleType') : 'dark'
            ];

            $url = str_replace($this->oldValues, $newValues, $this->Service->service_embed);
        }
        
       
  


        return $url;
    }

}

<?php

namespace XenBulletins\Tournament\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

class Tournament extends Entity {

    public static function getStructure(Structure $structure) {
        $structure->table = 'xf_tournament';
        $structure->shortName = 'XenBulletins\Tournament:Tournament';
        $structure->contentType = 'xf_tournament';
        $structure->primaryKey = 'tourn_id';
        $structure->columns = [
            'tourn_id' => ['type' => self::UINT, 'autoIncrement' => true],
            'tourn_domain' => ['type' => self::STR, 'maxLength' => 255],
            'tourn_title' => ['type' => self::STR, 'maxLength' => 255, 'required' => true],
            'tourn_startdate' => ['type' => self::INT, 'maxLength' => 10, 'default' => '0'],
            'tourn_enddate' => ['type' => self::INT, 'maxLength' => 10, 'default' => '0'],
            'tourn_starttime' => ['type' => self::INT, 'maxLength' => 10, 'default' => '0'],
            'tourn_endtime' => ['type' => self::INT, 'maxLength' => 10, 'default' => '0'],
            'tourn_icon' => ['type' => self::STR, 'maxLength' => 255, 'default' => ''],
            'tourn_header' => ['type' => self::STR, 'maxLength' => 255, 'default' => ''],
            'tourn_main_price' => ['type' => self::STR, 'maxLength' => 255],
            'tourn_desc' => ['type' => self::STR],
            'tourn_prizes' => ['type' => self::JSON_ARRAY, 'maxLength' => 255, 'default' => []],
             'conversation' => ['type' => self::STR, 'maxLength' => 10, 'default' => '0'],
        ];

        $structure->relations = [];
        $structure->defaultWith = [];
        $structure->getters = [];
        $structure->behaviors = [];

        return $structure;
    }

    public function getStartTime() {


        $tz = new \DateTimeZone("Europe/London");

        $date = new \DateTime('@' . $this->tourn_startdate, $tz);

        return $date->format("H:i");
    }

    
  
    
    public function getStartDate($format="Y-m-d") {

        $tz = new \DateTimeZone("Europe/London");
        $date = new \DateTime('@' . $this->tourn_startdate, $tz);
        return $date->format($format);
    }

    public function getEndTime() {


        $tz = new \DateTimeZone("Europe/London");

        $date = new \DateTime('@' . $this->tourn_enddate, $tz);

        return $date->format("H:i");
    }

    public function getEndDate($format="Y-m-d") {

        $tz = new \DateTimeZone("Europe/London");
        $date = new \DateTime('@' . $this->tourn_enddate, $tz);
        return $date->format($format);
    }

    public function getAbstractedCustomImgPath($upload, $type) {
        if ($type == 'icon') {
            $fn = 'icon';
        } elseif ($type == 'header') {
            $fn = 'header';
        }

        $tournament_id = $this->tourn_id;
        return sprintf('data://Tournament/' . $fn . '/%d/%d.jpg', floor($tournament_id / 1000), $tournament_id);
    }

    public function getImgUrl($canonical = true, $type) {
        if ($type == 'icon') {
            $fn = 'icon';
        } else if ($type == 'header') {
            $fn = 'header';
        }
        $tournament_id = $this->tourn_id;
        $path = sprintf('Tournament/' . $fn . '/%d/%d.jpg', floor($tournament_id / 1000), $tournament_id);
        return \XF::app()->applyExternalDataUrl($path, $canonical);
    }

}

<?php

namespace Z61\Classifieds\XF\Entity;

use XF\Mvc\Entity\Structure;
use Z61\Classifieds\Entity\Feedback;
use Z61\Classifieds\Entity\UserFeedback;

/**
 * Class User
 *
 * @package Z61\Classifieds\XF\Entity
 * @property Feedback[] ClassifiedsFeedback
 * @property UserFeedback ClassifiedsFeedbackInfo
 */
class User extends XFCP_User
{
    public function canViewClassifieds(&$error = null)
    {
        return $this->hasPermission('classifieds', 'view');
    }

    public function canAddClassified()
    {
        return ($this->user_id && $this->hasPermission('classifieds', 'add'));
    }

    public function hasClassifiedsCategoryPermission($contentId, $permission)
    {
        return $this->hasContentPermission('classifieds_category', $contentId, $permission);
    }

    public function canFeatureListings()
    {
        return $this->hasPermission('classifieds', 'purchaseFeature');
    }

    public function canViewClassifiedsFeedback()
    {
        return $this->hasPermission('classifieds', 'viewFeedback');
    }

    public function getFeedbackCount()
    {
        return $this->getRelationFinder('ClassifiedsFeedback')->total();
    }

    public function canViewAwaitingPayments()
    {
        return $this->hasPermission( 'classifieds', 'viewAwaitingPayment');
    }

    public static function getStructure(Structure $structure)
    {
        $structure = parent::getStructure($structure);

        $structure->columns['z61_classifieds_listing_count'] = ['type' => self::UINT, 'default' => 0, 'forced' => true, 'changeLog' => false];
        $structure->getters['feedback_count'] = 'true';
        $structure->relations['ClassifiedsFeedback'] = [
            'entity' => 'Z61\Classifieds:Feedback',
            'type' => self::TO_MANY,
            'conditions' => [
                ['to_user_id', '=', '$user_id']
            ]
        ];
        $structure->relations['ClassifiedsFeedbackInfo'] = [
            'entity' => 'Z61\Classifieds:UserFeedback',
            'type' => self::TO_ONE,
            'conditions' => 'user_id'
        ];
        $structure->relations['ClassifiedsListings'] = [
            'entity' => 'Z61\Classifieds:Listing',
            'type' => self::TO_MANY,
            'conditions' => 'user_id'
        ];
        return $structure;
    }
}
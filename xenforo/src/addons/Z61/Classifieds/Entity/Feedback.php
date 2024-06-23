<?php

namespace Z61\Classifieds\Entity;


use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;
use Z61\Classifieds\XF\Entity\User;

/**
 * COLUMNS
 *
 * @property int feedback_id
 * @property int from_user_id
 * @property string from_username
 * @property int to_user_id
 * @property string to_username
 * @property int listing_id
 * @property string feedback
 * @property string rating
 * @property string role
 * @property int feedback_date
 * @property int last_edit_date
 * @property int last_edit_user_id
 *
 * RELATIONS
 * @property User FromUser
 * @property User ToUser
 * @property UserFeedback UserFeedback
 * @property Listing Listing
 */
class Feedback extends Entity
{
    public function canView()
    {
        return $this->Listing->Category->canView();
    }
    public function canEdit()
    {
        return ($this->hasPermission('updateOwnFeedback') && $this->from_user_id == \XF::visitor()->user_id) ||
                $this->hasPermission('editAnyFeedback');
    }

    public function canDelete()
    {
        return ($this->hasPermission('deleteOwnFeedback' && $this->from_user_id == \XF::visitor()->user_id)) ||
                $this->hasPermission('deleteAnyFeedback');
    }

    public function hasPermission($permission)
    {
        /** @var \Z61\Classifieds\XF\Entity\User $visitor */
        $visitor = \XF::visitor();
        return $visitor->hasClassifiedsCategoryPermission($this->Listing->category_id, $permission);
    }

    protected function _preSave()
    {
        if ($this->isUpdate() &&( $this->isChanged('rating') || $this->isChanged('feedback') ) && !$this->isChanged('last_edit_date'))
        {
            $this->set('last_edit_date', \XF::$time);
            $this->set('last_edit_user_id', \XF::visitor()->user_id);
        }
    }

    protected function _postSave()
    {
        if ($this->isInsert() || $this->isChanged('rating'))
        {
            $userFeedback = $this->getRelationOrDefault('UserFeedback', false);
            $rating = $this->get('rating');

            $userFeedback->set('user_id', $this->get('to_user_id'));

            switch($rating)
            {
                case 'positive':
                    $userFeedback->set('positive', $userFeedback->positive + 1); break;
                case 'negative':
                    $userFeedback->set('negative', $userFeedback->negative + 1); break;
                case 'neutral':
                    $userFeedback->set('neutral', $userFeedback->neutral + 1); break;
            }

            $userFeedback->save();
        }
    }


    public static function getStructure(Structure $structure)
    {
        $structure->table = 'xf_z61_classifieds_feedback';
        $structure->shortName = 'Z61\Classifieds:Feedback';
        $structure->primaryKey = 'feedback_id';
        $structure->columns = [
            'feedback_id' => ['type' => self::UINT, 'autoIncrement' => true],
            'from_user_id' => ['type' => self::UINT],
            'from_username' => ['type' => self::STR, 'maxLength' => 50, 'required' => 'please_enter_valid_name'],
            'to_user_id' => ['type' => self::UINT],
            'to_username' => ['type' => self::STR, 'maxLength' => 50, 'required' => 'please_enter_valid_name'],
            'listing_id' => ['type' => self::UINT, 'required' => true],
            'feedback' => ['type' => self::STR, 'required' => true, 'maxLength' => 80],
            'rating' => ['type' => self::STR, 'default' => 'neutral', 'allowedValues' => ['positive', 'neutral', 'negative']],
            'role' => ['type' => self::STR, 'default' => 'buyer', 'allowedValues' => ['buyer', 'seller', 'trader']],
            'feedback_date' => ['type' => self::UINT, 'default' => \XF::$time],
            'last_edit_date' => ['type' => self::UINT, 'default' => 0],
            'last_edit_user_id' => ['type' => self::UINT, 'default' => 0],
        ];
        $structure->relations = [
            'FromUser' => [
                'entity' => 'XF:User',
                'type' => self::TO_ONE,
                'conditions' => [
                    ['user_id', '=', '$from_user_id']
                ]
            ],
            'ToUser' => [
                'entity' => 'XF:User',
                'type' => self::TO_ONE,
                'conditions' => [
                    ['user_id', '=', '$to_user_id']
                ]
            ],
            'UserFeedback' =>  [
                'entity' => 'Z61\Classifieds:UserFeedback',
                'type' => self::TO_ONE,
                'conditions' => [
                    ['user_id', '=', '$to_user_id']
                ]
            ],
            'Listing' => [
                'entity' => 'Z61\Classifieds:Listing',
                'type' => self::TO_ONE,
                'conditions' => 'listing_id'
            ],
        ];

        return $structure;
    }
}
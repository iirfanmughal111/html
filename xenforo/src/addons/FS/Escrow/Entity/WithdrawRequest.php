<?php

namespace FS\Escrow\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;
/*
* @property \XF\Entity\ApprovalQueue $ApprovalQueue
*/
class WithdrawRequest extends Entity
{

    public static function getStructure(Structure $structure)
    {
        $structure->table = 'fs_escrow_request_withdraw';
        $structure->shortName = 'FS\Escrow:WithdrawRequest';
        $structure->contentType = 'fs_escrow_withdrawRequest';
        $structure->primaryKey = 'req_id';
        $structure->columns = [
            'req_id' => ['type' => self::UINT, 'autoIncrement' => true],
            'user_id' => ['type' => self::UINT, 'required' => true],
            'amount' => ['type' => self::FLOAT, 'required' => true],
            'address_from' => ['type' => self::STR, 'default' => NULL],
            'address_to' =>  ['type' => self::STR, 'default' => NULL],
        //    'otp' => ['type' => self::UINT,'default' => 0],
            'verfiy_at' => ['type' => self::UINT, 'default' => 0],
            'is_proceed' => ['type' => self::UINT, 'default' => 0],
            'transaction_id' => ['type' => self::UINT, 'default' => 0],
            'request_state'             => [
				'type'          => self::STR,
				'default'       => 'visible',
				'allowedValues' => ['visible', 'moderated', 'deleted'],
			],
            'created_at' => ['type' => self::UINT, 'default' => \XF::$time],
        ];

        $structure->relations = [
            'User' => [
                'entity' => 'XF:User',
                'type' => self::TO_ONE,
                'conditions' => 'user_id',
            ],

            'Transaction' => [
                'entity' => 'FS\Escrow:Transaction',
                'type' => self::TO_ONE,
                'conditions' => 'transaction_id',
            ],

            'ApprovalQueue' => [
                'entity' => 'XF:ApprovalQueue',
                'type' => self::TO_ONE,
                'conditions' => [
                    ['content_type', '=', 'fs_escrow_withdrawRequest'],
                    ['content_id', '=', '$req_id']
                ],
                'primary' => true
            ],

            
        ];
        $structure->defaultWith = ['User'];
        $structure->getters = [];
        $structure->behaviors = [];

        return $structure;
    }

    protected function _postSave()
	{
		$approvalChange = $this->isStateChanged('request_state', 'moderated');
		if ($this->isUpdate())
		{
			if ($approvalChange == 'leave' && $this->ApprovalQueue)
			{
				$this->ApprovalQueue->delete();
			}
		}
        if ($approvalChange == 'enter')
		{
            if (!$this->app()->options()->fs_escrow_approval_que_option){
                /** @var \XF\Entity\ApprovalQueue $approvalQueue */
                $approvalQueue = $this->getRelationOrDefault('ApprovalQueue', false);
                $approvalQueue->content_date = \XF::$time;
                $approvalQueue->save();
            }
			
		}
    }
    public function canView( )
	{
        return true;
    }

    public function canApproveUnapprove(&$error = null): bool
	{
		return true;
	}
}
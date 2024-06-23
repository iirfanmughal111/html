<?php

namespace FS\AuctionPlugin\Service\Auction;

use XF\Mvc\FormAction;



class ForumAndFields extends \XF\Service\AbstractService
{
    public $nodeId;

    public function createCustomFields($nodeId)
    {
        $this->nodeId = $nodeId;
        foreach ($this->customFieldAuction() as $customField) {
            $this->fieldSaveProcess(\xf::app()->em()->create('XF:ThreadField'), $customField)->run();
        }
    }

    public function customFieldAuction()
    {

        return [

            'starting_bid' => [
                'title' => \XF::phrase('starting_bid'),
                'description' => \XF::phrase('auction_bid_explain'),
                'display_order' => '20',
                'field_type' => 'textbox',
                'required' => 'false',
                'field_id' => 'starting_bid',
                'match_type' => 'number',
                'fieldChoices' => [],
                'fieldChoicesText' => [],
                'match_params' => [
                    'number_min' => '1',
                    'number_max' => '',
                    'number_integer' => '1',
                ],
            ],

            'minimum_bid_increament' => [
                'title' => \XF::phrase('bid_increments'),
                'description' => '',
                'display_order' => '30',
                'field_type' => 'select',
                'required' => 'true',
                'field_id' => 'bid_increament',
                'match_type' => 'none',
                'fieldChoices' => [
                    '0' => '5',
                    '1' => '10',
                    '2' => '',
                ],
                'fieldChoicesText' => [
                    '0' => '5',
                    '1' => '10',
                    '2' => '',
                ],
                'match_params' => [],
            ],

            'payment_methods' => [
                'title' => \XF::phrase('payment_methods'),
                'description' => \XF::phrase('auction_payment_explain'),
                'display_order' => '40',
                'field_type' => 'checkbox',
                'required' => 'true',
                'field_id' => 'payment_methods',
                'match_type' => 'none',
                'fieldChoices' => [
                    '0' => 'First_Method',
                    '1' => 'Second_Method',
                    '2' => '',
                ],
                'fieldChoicesText' => [
                    '0' => 'First Method',
                    '1' => 'Second Method',
                    '2' => '',
                ],
                'match_params' => [],
            ],

            'shipping_term' => [
                'title' => \XF::phrase('shippingTerms'),
                'description' => \XF::phrase('auction_shippingTerms_explain'),
                'display_order' => '50',
                'field_type' => 'select',
                'required' => 'true',
                'field_id' => 'shipping_term',
                'match_type' => 'none',
                'fieldChoices' => [
                    '0' => 'Term_1',
                    '1' => 'Term_2',
                    '2' => '',
                ],
                'fieldChoicesText' => [
                    '0' => 'Term 1',
                    '1' => 'Term 2',
                    '2' => '',
                ],
                'match_params' => [],
            ],

            'ships_via' => [
                'title' => \XF::phrase('shippingVia'),
                'description' => \XF::phrase('auction_shippingVia_explain'),
                'display_order' => '60',
                'field_type' => 'select',
                'required' => 'true',
                'field_id' => 'ships_via',
                'match_type' => 'none',
                'fieldChoices' => [
                    '0' => 'ships_1',
                    '1' => 'ships_2',
                    '2' => '',
                ],
                'fieldChoicesText' => [
                    '0' => 'ships 1',
                    '1' => 'ships 2',
                    '2' => '',
                ],
                'match_params' => [],
            ],

            'auction_guidelines' => [
                'title' => \XF::phrase('guidlines'),
                'description' => \XF::phrase('auction_guidlines_explain'),
                'display_order' => '70',
                'field_type' => 'checkbox',
                'required' => 'true',
                'field_id' => 'auction_guidelines',
                'match_type' => 'none',
                'fieldChoices' => [
                    '0' => '0',
                    '1' => '',
                ],
                'fieldChoicesText' => [
                    '0' => \XF::phrase('guidlines_statement'),
                    '1' => '',
                ],
                'match_params' => [],
            ],

            'bumping_rules' => [
                'title' => \XF::phrase('bumpingRule'),
                'description' => \XF::phrase('auction_bumpingRule_explain'),
                'display_order' => '80',
                'field_type' => 'checkbox',
                'required' => 'true',
                'field_id' => 'bumping_rules',
                'match_type' => 'none',
                'fieldChoices' => [
                    '0' => '0',
                    '1' => '',
                ],
                'fieldChoicesText' => [
                    '0' => \XF::phrase('bumpingRule_statement'),
                    '1' => '',
                ],
                'match_params' => [],
            ],
        ];
    }

    protected function fieldSaveProcess(\XF\Entity\AbstractField $field, $customField)
    {
        $form =  \xf::app()->formAction();

        $input = [
            'display_group' => 'after',
            'display_order' => $customField['display_order'],
            'field_type' => $customField['field_type'],
            'field_choices' => '[]',
            'max_length' => '0',
            'required' => $customField['required'],
            'moderator_editable' => 'false',
            'display_template' => '0',
            'wrapper_template' => '0'
        ];
        $input['field_id'] = $customField['field_id'];
        $input['match_type'] = $customField['match_type'];
        $input['user_editable'] = 'never';

        // this input has some values which may not always be present, so make sure we remove those
        $structure = $field->structure();
        foreach ($input as $key => $null) {
            if (!isset($structure->columns[$key])) {
                unset($input[$key]);
            }
        }

        // if (isset($field->editable_user_group_ids)) {
        // 	$editableUserGroups = $this->filter('editable_user_group', 'str');
        // 	if ($editableUserGroups == 'all') {
        // 		$input['editable_user_group_ids'] = [-1];
        // 	} else {
        // 		$input['editable_user_group_ids'] = $this->filter('editable_user_group_ids', 'array-uint');
        // 	}
        // }

        $input['editable_user_group_ids'] = [
            '0' => -1
        ];

        $input['match_params'] = $customField['match_params'];

        $fieldChoices = $customField['fieldChoices'];
        $fieldChoicesText = $customField['fieldChoicesText'];
        $fieldChoicesCombined = [];

        foreach ($fieldChoices as $key => $choice) {
            if (isset($fieldChoicesText[$key]) && $fieldChoicesText[$key] !== '') {
                $fieldChoicesCombined[$choice] = $fieldChoicesText[$key];
            }
        }
        $input['field_choices'] = $fieldChoicesCombined;

        $form->basicEntitySave($field, $input);
        $this->saveAdditionalData($form, $field);

        $phraseInput = [
            'title' => $customField['title'],
            'description' => $customField['description'],
        ];
        $form->validate(function (FormAction $form) use ($phraseInput) {
            if ($phraseInput['title'] === '') {
                $form->logError(\XF::phrase('please_enter_valid_title'), 'title');
            }
        });
        $form->apply(function () use ($phraseInput, $field) {
            $title = $field->getMasterPhrase(true);
            $title->phrase_text = $phraseInput['title'];
            $title->save();

            $description = $field->getMasterPhrase(false);
            $description->phrase_text = $phraseInput['description'];
            $description->save();
        });

        return $form;
    }

    protected function saveAdditionalData(FormAction $form, \XF\Entity\AbstractField $field)
    {
        $nodeIds = [
            '0' => (int) $this->nodeId
        ];

        /** @var \XF\Entity\ThreadField $field */
        $form->complete(function () use ($field, $nodeIds) {
            /** @var \XF\Repository\ForumField $repo */
            $repo = \xf::app()->repository('XF:ForumField');
            $repo->updateFieldAssociations($field, $nodeIds);
        });

        return $form;
    }

    public function createNode()
    {

        $node = \xf::app()->em()->create('XF:Node');
        $node->node_type_id = "Forum";
        $this->createAcutionNode($node)->run();
        return $node;
    }


    protected function createAcutionNode(\XF\Entity\Node $node)
    {
        $form = \xf::app()->formAction();

        $input = [
            'node' => [
                'title' => 'Acution',
                'node_name' => '',
                'description' => '',
                'parent_node_id' => '',
                'display_order' => '',
                'display_in_list' => false,
                'style_id' => '',
                'navigation_id' => ''
            ]
        ];

        $data = $node->getDataRelationOrDefault(false);
        $node->addCascadedSave($data);

        $form->basicEntitySave($node, $input['node']);
        $this->saveTypeData($form, $node, $data);

        return $form;
    }

    protected function getForumTypeHandlerForAddEdit(\XF\Entity\Node $node)
    {
        /** @var \XF\Entity\Forum $forum */
        $forum = $node->getDataRelationOrDefault(false);

        if (!$node->exists()) {

            return $this->app->forumType("discussion", false);
        } else {
            return $forum->TypeHandler;
        }
    }

    protected function saveTypeData(FormAction $form, \XF\Entity\Node $node, \XF\Entity\AbstractNode $data)
    {
        $forumType = $this->getForumTypeHandlerForAddEdit($node);
        if (!$forumType) {
            $form->logError(\XF::phrase('forum_type_handler_not_found'), 'forum_type_id');
            return;
        }

        $forumInput = [
            'allow_posting' => 'true',
            'moderate_threads' => 'false',
            'moderate_replies' => 'false',
            'count_messages' => 'true',
            'find_new' => 'true',
            'allowed_watch_notifications' => 'all',
            'default_sort_order' => 'last_post_date',
            'default_sort_direction' => 'desc',
            'list_date_limit_days' => 0,
            'default_prefix_id' => 0,
            'require_prefix' => false,
            'min_tags' => 0,
            'allow_index' => 'allow'
        ];

        $forumInput['index_criteria'] = $this->filterIndexCriteria();

        /** @var \XF\Entity\Forum $data */
        $data->bulkSet($forumInput);
        $data->forum_type_id = $forumType->getTypeId();

        $typeConfig = $forumType->setupTypeConfigSave($form, $node, $data, \xf::app()->request);
        if ($typeConfig instanceof \XF\Mvc\Entity\ArrayValidator) {
            if ($typeConfig->hasErrors()) {
                $form->logErrors($typeConfig->getErrors());
            }
            $typeConfig = $typeConfig->getValuesForced();
        }

        $data->type_config = $typeConfig;


        $prefixIds = \XF::app()->finder('XF:ThreadPrefix')->pluckfrom('prefix_id')->fetch()->toArray();

        $form->complete(function () use ($data, $prefixIds) {
            /** @var \XF\Repository\ForumPrefix $repo */
            $repo = $this->repository('XF:ForumPrefix');
            $repo->updateContentAssociations($data->node_id, $prefixIds);
        });
    }

    protected function filterIndexCriteria()
    {
        $criteria = [];

        $input =

            [
                'max_days_post' => [
                    'enabled' => false,
                    'value' => 0
                ],
                'max_days_last_post' => [
                    'enabled' => false,
                    'value' => 0
                ],
                'min_replies' => [
                    'enabled' => false,
                    'value' => 0
                ],
                'min_reaction_score' => [
                    'enabled' => false,
                    'value' => 0
                ]
            ];



        foreach ($input as $rule => $criterion) {
            if (!$criterion['enabled']) {
                continue;
            }

            $criteria[$rule] = $criterion['value'];
        }

        return $criteria;
    }

    public function updateOptionsforum($nodeId)
    {

        $optionIndex = \xf::app()->finder('XF:Option')->where('option_id', 'fs_auction_applicable_forum')->fetchOne();

        if ($optionIndex) {

            $optionIndex->option_value = $nodeId;
            $optionIndex->save();
        }
    }

    public function permissionRebuild()
    {

        // $userGroup = \xf::app()->finder('XF:UserGroup')->whereId(2)->fetchOne();


        // $permissions = [
        //     'general' => [

        //         'viewNode' => 'content_allow',
        //     ],
        //     'forum' => [

        //         'postThread' => 'content_allow',
        //         'postReply' => 'content_allow',
        //     ]
        // ];



        // $permissionUpdater = \xf::app()->service('XF:UpdatePermissions');
        // $permissionUpdater->setContent("node", $node->node_id)->setUserGroup($userGroup);
        // $permissionUpdater->updatePermissions($permissions);

        $userGroups = $this->getUserGroupRepo()->findUserGroupsForList()->fetch();

        if (count($userGroups)) {
            //
            $permissionUpdater = \xf::app()->service('XF:UpdatePermissions');
            foreach ($userGroups as $group) {

                $permissionUpdater->setUserGroup($group)->setGlobal();
                if (\xf::app()->container()->isCached('permission.builder')) {
                    \xf::app()->permissionBuilder()->refreshData();
                }

                $permissionUpdater->triggerCacheRebuild();
            }
        }
    }

    public function getUserGroupRepo()
    {
        return \xf::app()->repository('XF:UserGroup');
    }
}

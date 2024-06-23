<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Service\Group;

use XF;
use function time;
use LogicException;
use XF\Entity\User;
use Truonglv\Groups\App;
use function array_merge;
use XF\Service\AbstractService;
use Truonglv\Groups\Entity\Category;
use XF\Service\ValidateAndSavableTrait;

class Creator extends AbstractService
{
    use ValidateAndSavableTrait;

    /**
     * @var Category
     */
    protected $category;

    /**
     * @var \Truonglv\Groups\Entity\Group
     */
    protected $group;

    /**
     * @var \Truonglv\Groups\Entity\Member
     */
    protected $member;

    /**
     * @var Preparer
     */
    protected $preparer;

    /**
     * @var User
     */
    protected $user;

    public function __construct(\XF\App $app, Category $category)
    {
        parent::__construct($app);

        $this->category = $category;
        $this->setupDefaults();
    }

    /**
     * @return \Truonglv\Groups\Entity\Group
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param string|array $tags
     * @return void
     */
    public function setTags($tags)
    {
        $this->preparer->setTags($tags);
    }

    /**
     * @return Preparer
     */
    public function getPreparer()
    {
        return $this->preparer;
    }

    /**
     * @param User $user
     * @return void
     */
    protected function setUser(User $user)
    {
        if (!$user->exists()) {
            throw new LogicException('User must be exists.');
        }

        $this->user = $user;
    }

    /**
     * @param string $message
     * @param bool $format
     * @return void
     */
    public function setDescription(string $message, $format = true)
    {
        $this->preparer->setDescription($message, $format);
    }

    /**
     * @param array $customFields
     * @return void
     */
    public function setCustomFields(array $customFields)
    {
        $this->preparer->setCustomFields($customFields);
    }

    public function setIsAutomated(): void
    {
        $this->preparer->setPerformValidations(false);
    }

    /**
     * @return array
     */
    protected function _validate()
    {
        $this->finalizeSetup();

        $this->group->preSave();
        $errors = $this->group->getErrors();

        $errors = array_merge($errors, $this->preparer->validate());

        return $errors;
    }

    /**
     * @return \Truonglv\Groups\Entity\Group
     * @throws \XF\PrintableException
     */
    protected function _save()
    {
        $db = $this->db();
        $group = $this->group;

        $db->beginTransaction();

        $group->save(true, false);

        $this->preparer->afterInsert();
        $db->commit();

        return $group;
    }

    /**
     * @return void
     */
    protected function finalizeSetup()
    {
        $time = time();

        $visitor = XF::visitor();

        $group = $this->group;
        $member = $this->member;

        $group->owner_user_id = $this->user->user_id;
        $group->owner_username = $this->user->username;
        $group->member_count = 1;
        $group->group_state = $this->category->getNewGroupState();

        if ($group->created_date > 0) {
            $joinDate = $group->created_date;
        } else {
            $group->created_date = $time;
            $joinDate = $time;
        }

        $languageAll = $this->app->container('language.all');
        if (isset($languageAll[$visitor->language_id])) {
            $group->language_code = $languageAll[$visitor->language_id]->language_code;
        }

        $member->member_state = App::MEMBER_STATE_VALID;
        $member->member_role_id = App::MEMBER_ROLE_ID_ADMIN;
        $member->user_id = $this->user->user_id;
        $member->username = $this->user->username;
        $member->joined_date = $joinDate;
    }

    /**
     * @return void
     */
    protected function setupDefaults()
    {
        $this->group = $this->category->getNewGroup();

        $this->member = $this->group->getNewMember();
        $this->group->addCascadedSave($this->member);
        $this->member->hydrateRelation('Group', $this->group);

        /** @var Preparer $preparer */
        $preparer = $this->service('Truonglv\Groups:Group\Preparer', $this->group, $this->category);
        $this->preparer = $preparer;

        $this->setUser(XF::visitor());
    }
}

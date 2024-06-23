<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Service\Group;

use LogicException;
use function array_merge;
use XF\Service\AbstractService;
use Truonglv\Groups\Entity\Group;
use XF\Service\ValidateAndSavableTrait;

class Editor extends AbstractService
{
    use ValidateAndSavableTrait;

    /**
     * @var Group
     */
    protected $group;

    /**
     * @var Preparer
     */
    protected $preparer;

    public function __construct(\XF\App $app, Group $group)
    {
        parent::__construct($app);

        if (!$group->exists()) {
            throw new LogicException('Group must be exists.');
        }

        $this->group = $group;
        $this->setupDefaults();
    }

    /**
     * @return Group
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @return Preparer
     */
    public function getPreparer()
    {
        return $this->preparer;
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
        $this->group->preSave();

        $errors = $this->group->getErrors();
        $errors = array_merge($errors, $this->preparer->validate());

        return $errors;
    }

    /**
     * @return Group
     * @throws \XF\PrintableException
     */
    protected function _save()
    {
        $db = $this->db();
        $db->beginTransaction();

        $group = $this->group;

        $group->save(true, false);
        $this->preparer->afterUpdate();

        $db->commit();

        return $group;
    }

    /**
     * @return void
     */
    protected function setupDefaults()
    {
        /** @var Preparer $preparer */
        $preparer = $this->service('Truonglv\Groups:Group\Preparer', $this->group);
        $this->preparer = $preparer;
    }
}

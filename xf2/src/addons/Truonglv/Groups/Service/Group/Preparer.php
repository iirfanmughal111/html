<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Service\Group;

use function count;
use function array_keys;
use Truonglv\Groups\App;
use function array_merge;
use XF\Service\Tag\Changer;
use InvalidArgumentException;
use XF\Service\AbstractService;
use Truonglv\Groups\Entity\Group;
use Truonglv\Groups\Entity\Category;

class Preparer extends AbstractService
{
    /**
     * @var Group
     */
    protected $group;

    /**
     * @var Category
     */
    protected $category;

    /**
     * @var \XF\Service\Tag\Changer
     */
    protected $tagChanger;

    /**
     * @var \XF\Service\Message\Preparer
     */
    protected $preparer;
    /**
     * @var bool
     */
    protected $performValidations = true;

    public function __construct(\XF\App $app, Group $group, Category $category = null)
    {
        parent::__construct($app);

        $this->group = $group;
        if ($category !== null) {
            $this->category = $category;
        } elseif ($group->Category !== null) {
            $this->category = $group->Category;
        } else {
            throw new InvalidArgumentException('Cannot determine category');
        }

        /** @var Changer $tagChanger */
        $tagChanger = $this->service(
            'XF:Tag\Changer',
            App::CONTENT_TYPE_GROUP,
            $group->exists() ? $group : $this->category
        );
        $this->tagChanger = $tagChanger;

        /** @var \XF\Service\Message\Preparer $preparer */
        $preparer = $this->service('XF:Message\Preparer', App::CONTENT_TYPE_GROUP, $this->group);
        $this->preparer = $preparer;
    }

    /**
     * @return Group
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param bool $performValidations
     * @return void
     */
    public function setPerformValidations(bool $performValidations)
    {
        $this->performValidations = $performValidations;
    }

    /**
     * @param string|array $tags
     * @return void
     */
    public function setTags($tags)
    {
        if ($this->tagChanger->canEdit()) {
            $this->tagChanger->setEditableTags($tags);
        }
    }

    /**
     * @param string $message
     * @param bool $format
     * @return bool
     */
    public function setDescription($message, $format = true)
    {
        if (!$format) {
            $this->preparer->disableAllFilters();
        }

        $structure = $this->group->structure();
        if (array_key_exists('required', $structure->columns['description'])) {
            $required = (bool) $structure->columns['description']['required'];
            $this->preparer->setConstraint('allowEmpty', !$required);
        }
        $this->group->description = $this->preparer->prepare($message);

        return $this->preparer->pushEntityErrorIfInvalid($this->group, 'description');
    }

    /**
     * @param array $customFields
     * @return void
     */
    public function setCustomFields(array $customFields)
    {
        $group = $this->group;

        /** @var \XF\CustomField\Set $fieldSet */
        $fieldSet = $group->custom_fields;
        $fieldDefinition = $fieldSet->getDefinitionSet()
            ->filterEditable($fieldSet, 'user')
            ->filterOnly($this->category->field_cache);

        $customFieldsShown = array_keys($fieldDefinition->getFieldDefinitions());

        if (count($customFieldsShown) > 0) {
            $fieldSet->bulkSet($customFields, $customFieldsShown);
        }
    }

    /**
     * @return array
     */
    public function validate()
    {
        $errors = [];

        if ($this->performValidations) {
            if ($this->tagChanger->canEdit()) {
                if ($this->tagChanger->hasErrors()) {
                    $errors = array_merge($errors, $this->tagChanger->getErrors());
                }
            }
        }

        return $errors;
    }

    /**
     * @return void
     */
    public function afterInsert()
    {
        if ($this->tagChanger->canEdit()) {
            $this->tagChanger
                ->setContentId($this->group->group_id, true)
                ->save($this->performValidations);
        }
    }

    /**
     * @return void
     */
    public function afterUpdate()
    {
        if ($this->tagChanger->canEdit()) {
            $this->tagChanger->save($this->performValidations);
        }
    }
}

<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Search\Data;

use Truonglv\Groups\App;
use XF\Mvc\Entity\Entity;
use XF\Search\IndexRecord;
use XF\Search\Data\AbstractData;
use XF\Search\MetadataStructure;
use Truonglv\Groups\Entity\Category;

class Event extends AbstractData
{
    /**
     * @param mixed $forView
     * @return array
     */
    public function getEntityWith($forView = false)
    {
        $with = ['FirstComment', 'Group', 'Group.Category'];
        $forViewBool = (bool) $forView;
        if ($forViewBool) {
            $with[] = 'User';
        }

        return $with;
    }

    /**
     * @param Entity $entity
     * @return int
     */
    public function getResultDate(Entity $entity)
    {
        if (!($entity instanceof \Truonglv\Groups\Entity\Event)) {
            return 0;
        }

        return $entity->created_date;
    }

    /**
     * @param Entity $entity
     * @return IndexRecord|null
     */
    public function getIndexData(Entity $entity)
    {
        if (!($entity instanceof \Truonglv\Groups\Entity\Event)) {
            return null;
        }

        if ($entity->FirstComment === null) {
            return null;
        }

        /** @var \Truonglv\Groups\Entity\Group|null $group */
        $group = $entity->Group;
        /** @var Category|null $category */
        $category = $group !== null ? $group->Category : null;

        if ($category === null) {
            return null;
        }

        $index = IndexRecord::create(App::CONTENT_TYPE_EVENT, $entity->event_id, [
            'title' => $entity->event_name_,
            'message' => $entity->FirstComment->message_,
            'date' => $entity->created_date,
            'user_id' => $entity->user_id,
            'discussion_id' => $entity->event_id,
            'metadata' => $this->getMetaData($entity)
        ]);

        if (!$entity->isVisible()) {
            $index->setHidden();
        }

        if (count($entity->tags) > 0) {
            $index->indexTags($entity->tags);
        }

        return $index;
    }

    /**
     * @param Entity $entity
     * @param array $options
     * @return array
     */
    public function getTemplateData(Entity $entity, array $options = [])
    {
        return [
            'event' => $entity,
            'options' => $options
        ];
    }

    /**
     * @param MetadataStructure $structure
     * @return void
     */
    public function setupMetadataStructure(MetadataStructure $structure)
    {
        $structure->addField('event', MetadataStructure::INT);
        $structure->addField('group', MetadataStructure::INT);
        $structure->addField('category', MetadataStructure::INT);
    }

    /**
     * @return string
     */
    public function getTemplateName()
    {
        return 'public:tlg_search_result_event';
    }

    /**
     * @param Entity $entity
     * @param mixed $error
     * @return bool
     */
    public function canUseInlineModeration(Entity $entity, & $error = null)
    {
        if (!($entity instanceof \Truonglv\Groups\Entity\Event)) {
            return false;
        }

        return $entity->canUseInlineModeration($error);
    }

    /**
     * @param \Truonglv\Groups\Entity\Event $entity
     * @return array
     */
    protected function getMetaData(\Truonglv\Groups\Entity\Event $entity)
    {
        $metadata = [
            'event' => $entity->event_id,
            'group' => $entity->group_id,
        ];
        if ($entity->Group !== null) {
            $metadata['category'] = $entity->Group->category_id;
        }

        return $metadata;
    }
}

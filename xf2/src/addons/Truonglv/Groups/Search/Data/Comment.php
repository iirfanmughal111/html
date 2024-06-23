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

class Comment extends AbstractData
{
    /**
     * @param mixed $forView
     * @return array
     */
    public function getEntityWith($forView = false)
    {
        return ['full'];
    }

    /**
     * @param Entity $entity
     * @return int
     */
    public function getResultDate(Entity $entity)
    {
        if (!($entity instanceof \Truonglv\Groups\Entity\Comment)) {
            return 0;
        }

        return $entity->comment_date;
    }

    /**
     * @param Entity $entity
     * @return IndexRecord|null
     */
    public function getIndexData(Entity $entity)
    {
        if (!($entity instanceof \Truonglv\Groups\Entity\Comment)) {
            return null;
        }

        if ($entity->Group === null) {
            return null;
        }

        $index = IndexRecord::create(App::CONTENT_TYPE_COMMENT, $entity->comment_id, [
            'message' => $entity->message_,
            'date' => $entity->comment_date,
            'user_id' => $entity->user_id,
            'discussion_id' => $entity->content_id,
            'metadata' => $this->getMetaData($entity)
        ]);

        if (!$entity->isVisible()) {
            $index->setHidden();
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
            'comment' => $entity,
            'options' => $options
        ];
    }

    /**
     * @param MetadataStructure $structure
     * @return void
     */
    public function setupMetadataStructure(MetadataStructure $structure)
    {
        $structure->addField('comment', MetadataStructure::INT);
        $structure->addField('content_id', MetadataStructure::INT);
        $structure->addField('content_type', MetadataStructure::STR);
        $structure->addField('group', MetadataStructure::INT);
    }

    /**
     * @param Entity $entity
     * @param mixed $error
     * @return bool
     */
    public function canUseInlineModeration(Entity $entity, & $error = null)
    {
        if (!($entity instanceof \Truonglv\Groups\Entity\Comment)) {
            return false;
        }

        return $entity->canUseInlineModeration($error);
    }

    /**
     * @return string
     */
    public function getTemplateName()
    {
        return 'public:tlg_search_result_comment';
    }

    /**
     * @param \Truonglv\Groups\Entity\Comment $entity
     * @return array
     */
    protected function getMetaData(\Truonglv\Groups\Entity\Comment $entity)
    {
        $metadata = [
            'comment' => $entity->comment_id,
            'content_id' => $entity->content_id,
            'content_type' => $entity->content_type,
        ];
        if ($entity->Group !== null) {
            $metadata['group'] = $entity->Group->group_id;
        }

        return $metadata;
    }
}

<?php

namespace Truonglv\Groups\Search\Data;

use Truonglv\Groups\App;
use XF\Mvc\Entity\Entity;
use XF\Search\IndexRecord;
use XF\Search\Data\AbstractData;
use XF\Search\MetadataStructure;

class ResourceItem extends AbstractData
{
    /**
     * @param mixed $forView
     * @return array
     */
    public function getEntityWith($forView = false)
    {
        $with = ['Group', 'Group.Category'];
        if ((bool) $forView) {
            $with[] = 'User';
        }

        return $with;
    }

    /**
     * @param Entity $entity
     * @return IndexRecord|null
     */
    public function getIndexData(Entity $entity)
    {
        if (!$entity instanceof \Truonglv\Groups\Entity\ResourceItem) {
            return null;
        }

        if ($entity->FirstComment === null) {
            return null;
        }

        return IndexRecord::create(App::CONTENT_TYPE_RESOURCE, $entity->resource_id, [
            'title' => $entity->title_,
            'message' => $entity->FirstComment->message_,
            'date' => $entity->resource_date,
            'user_id' => $entity->user_id,
            'discussion_id' => $entity->resource_id,
            'metadata' => $this->getMetadata($entity)
        ]);
    }

    /**
     * @param MetadataStructure $structure
     * @return void
     */
    public function setupMetadataStructure(MetadataStructure $structure)
    {
        $structure->addField('resource', MetadataStructure::INT);
        $structure->addField('group', MetadataStructure::INT);
        $structure->addField('category', MetadataStructure::INT);
    }

    /**
     * @param Entity $entity
     * @return int
     */
    public function getResultDate(Entity $entity)
    {
        return $entity->get('resource_date');
    }

    /**
     * @return string
     */
    public function getTemplateName()
    {
        return 'public:tlg_search_result_resource';
    }

    /**
     * @param Entity $entity
     * @param mixed $error
     * @return bool
     */
    public function canUseInlineModeration(Entity $entity, & $error = null)
    {
        return false;
    }

    /**
     * @param Entity $entity
     * @param array $options
     * @return array
     */
    public function getTemplateData(Entity $entity, array $options = [])
    {
        return [
            'resource' => $entity,
            'options' => $options
        ];
    }

    /**
     * @param \Truonglv\Groups\Entity\ResourceItem $resource
     * @return array
     */
    protected function getMetadata(\Truonglv\Groups\Entity\ResourceItem $resource)
    {
        $metadata = [
            'resource' => $resource->resource_id,
            'group' => $resource->group_id,
        ];
        if ($resource->Group !== null) {
            $metadata['category'] = $resource->Group->category_id;
        }

        return $metadata;
    }
}

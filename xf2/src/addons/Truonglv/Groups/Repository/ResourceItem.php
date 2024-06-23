<?php

namespace Truonglv\Groups\Repository;

use XF;
use function time;
use Truonglv\Groups\App;
use XF\Mvc\Entity\Repository;

class ResourceItem extends Repository
{
    /**
     * @param \Truonglv\Groups\Entity\Group $group
     * @return \XF\Mvc\Entity\Finder
     */
    public function findResourcesForList(\Truonglv\Groups\Entity\Group $group)
    {
        $finder = $this->finder('Truonglv\Groups:ResourceItem');
        $finder->with('fullList');
        $finder->where('group_id', $group->group_id);
        $finder->setDefaultOrder('resource_date', 'DESC');

        return $finder;
    }

    /**
     * @param \Truonglv\Groups\Entity\ResourceItem $resource
     * @return \Truonglv\Groups\Finder\Comment
     */
    public function findCommentsForView(\Truonglv\Groups\Entity\ResourceItem $resource)
    {
        $finder = App::commentRepo()->findCommentsForView($resource->getCommentContentType(), $resource->resource_id);
        $finder->skipIgnored();

        return $finder;
    }

    /**
     * @param \Truonglv\Groups\Entity\ResourceItem $resource
     * @throws \XF\Db\Exception
     * @return void
     */
    public function logView(\Truonglv\Groups\Entity\ResourceItem $resource)
    {
        $db = $this->db();
        $db->query('
            INSERT INTO `xf_tl_group_resource_view`
                (`resource_id`, `total`)
            VALUES
                (?, 1)
            ON DUPLICATE KEY UPDATE
                `total` = `total` + 1
        ', [$resource->resource_id]);
    }

    /**
     * @throws \XF\Db\Exception
     * @return void
     */
    public function batchUpdateResourceViews()
    {
        $db = $this->db();

        $db->query('
			UPDATE xf_tl_group_resource AS r
			INNER JOIN xf_tl_group_resource_view AS v ON (r.resource_id = v.resource_id)
			SET r.view_count = r.view_count + v.total
		');

        $db->emptyTable('xf_tl_group_resource_view');
    }

    /**
     * @throws \XF\Db\Exception
     * @return void
     */
    public function batchUpdateResourceDownloads()
    {
        $db = $this->db();

        $db->query('
			UPDATE xf_tl_group_resource AS r
			INNER JOIN xf_tl_group_resource_download AS d ON (r.resource_id = d.resource_id)
			SET r.download_count = r.download_count + d.total
		');

        $db->emptyTable('xf_tl_group_resource_download');
    }

    /**
     * @param int $resourceId
     * @throws \XF\Db\Exception
     * @return void
     */
    public function logDownload($resourceId)
    {
        if (XF::visitor()->user_id > 0) {
            $this->db()->query('
                INSERT IGNORE INTO `xf_tl_group_resource_download_log`
                    (`user_id`, `resource_id`, `download_date`, `total`)
                VALUES
                    (?, ?, ?, 1)
                ON DUPLICATE KEY UPDATE
                    `download_date` = VALUES(`download_date`),
                    `total` = `total` + 1
            ', [
                XF::visitor()->user_id,
                $resourceId,
                time()
            ]);
        }

        $this->db()->query('
            INSERT IGNORE INTO `xf_tl_group_resource_download`
                (`resource_id`, `total`)
            VALUES
                (?, 1)
            ON DUPLICATE KEY UPDATE
                `total` = `total` + 1
        ', [$resourceId]);
    }
}

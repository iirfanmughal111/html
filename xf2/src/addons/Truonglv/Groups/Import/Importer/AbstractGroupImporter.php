<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Import\Importer;

use XF;
use function count;
use function is_dir;
use function implode;
use function file_exists;
use XF\Db\Mysqli\Adapter;
use function array_replace_recursive;
use XF\Import\Importer\AbstractCoreImporter;

abstract class AbstractGroupImporter extends AbstractCoreImporter
{
    /**
     * @var \XF\Db\Mysqli\Adapter
     */
    protected $sourceDb;

    /**
     * @return bool
     */
    public function canRetainIds()
    {
        if (!parent::canRetainIds()) {
            return false;
        }

        $db = $this->app->db();

        if ($db->fetchOne('SELECT MAX(category_id) FROM xf_tl_group_category')) {
            return false;
        }

        if ($db->fetchOne('SELECT MAX(group_id) FROM xf_tl_group')) {
            return false;
        }

        if ($db->fetchOne('SELECT MAX(event_id) FROM xf_tl_group_event')) {
            return false;
        }

        return true;
    }

    /**
     * @param array $steps
     * @param array $stepConfig
     * @param array $errors
     * @return bool
     */
    public function validateStepConfig(array $steps, array & $stepConfig, array & $errors)
    {
        return true;
    }

    /**
     * @throws \XF\Db\Exception
     * @return void
     */
    public function resetDataForRetainIds()
    {
        $db = $this->app->db();

        foreach ([
            'xf_tl_group',
            'xf_tl_group_category',
            'xf_tl_group_comment',
            'xf_tl_group_event',
            'xf_tl_group_feature',
            'xf_tl_group_member',
            'xf_tl_group_post',

            'xf_tl_group_field',
            'xf_tl_group_field_value',
            'xf_tl_group_category_field'
         ] as $table) {
            $db->query("TRUNCATE TABLE {$table}");
        }
    }

    /**
     * @return array
     */
    protected function getBaseConfigDefault()
    {
        return [
            'db' => [
                'host' => '',
                'username' => '',
                'password' => '',
                'dbname' => '',
                'port' => 3306
            ],
            'data_dir' => '',
            'internal_data_dir' => ''
        ];
    }

    /**
     * @return array
     */
    protected function getStepConfigDefault()
    {
        return [];
    }

    /**
     * @return void
     */
    protected function doInitializeSource()
    {
        $this->sourceDb = new \XF\Db\Mysqli\Adapter(
            $this->baseConfig['db'],
            $this->app->config('fullUnicode')
        );
    }

    /**
     * @param array $baseConfig
     * @param array $errors
     * @return bool
     */
    public function validateBaseConfig(array & $baseConfig, array & $errors)
    {
        $fullConfig = array_replace_recursive($this->getBaseConfigDefault(), $baseConfig);
        $missingFields = false;

        if ($fullConfig['db']['host'] !== '') {
            $validDbConnection = false;
            /** @var Adapter|null $db */
            $db = null;

            try {
                $db = new Adapter($fullConfig['db'], false);
                $db->getConnection();
                $validDbConnection = true;
            } catch (\XF\Db\Exception $e) {
                $errors[] = XF::phrase('source_database_connection_details_not_correct_x', ['message' => $e->getMessage()]);
            }

            // @phpstan-ignore-next-line
            if ($validDbConnection && $db !== null) {
                try {
                    $versionId = $db->fetchOne('SELECT team_id FROM xf_team');
                    if (!$versionId) {
                        $errors[] = XF::phrase('tlg_database_does_not_contains_addon_data');
                    }
                } catch (\XF\Db\Exception $e) {
                    if ($fullConfig['db']['dbname'] === '') {
                        $errors[] = XF::phrase('please_enter_database_name');
                    } else {
                        $errors[] = XF::phrase('table_prefix_or_database_name_is_not_correct');
                    }
                }
            }
        } else {
            $missingFields = true;
        }

        if ($fullConfig['data_dir'] !== '') {
            $data = \rtrim($fullConfig['data_dir'], '/\\ ');

            if (!file_exists($data) || !is_dir($data)) {
                $errors[] = XF::phrase('directory_x_not_found_is_not_readable', ['dir' => $data]);
            }

            $baseConfig['data_dir'] = $data; // to make sure it takes the format we expect
        } else {
            $missingFields = true;
        }

        if ($fullConfig['internal_data_dir'] !== '') {
            $internalData = rtrim($fullConfig['internal_data_dir'], '/\\ ');

            if (!file_exists($internalData) || !is_dir($internalData)) {
                $errors[] = XF::phrase('directory_x_not_found_is_not_readable', ['dir' => $internalData]);
            }

            $baseConfig['internal_data_dir'] = $internalData; // to make sure it takes the format we expect
        } else {
            $missingFields = true;
        }

        if ($missingFields) {
            $errors[] = XF::phrase('please_complete_required_fields');
        }

        return count($errors) > 0;
    }

    /**
     * @return array
     */
    public function getSteps()
    {
        return [
            'categories' => [
                'title' => XF::phrase('tlg_categories')
            ],
            'groups' => [
                'title' => XF::phrase('tlg_groups'),
                'depends' => ['categories']
            ]
        ];
    }

    /**
     * @param string $oldContentType
     * @param int $oldContentId
     * @param string $newContentType
     * @param int $newContentId
     * @return void
     */
    protected function updateAttachments($oldContentType, $oldContentId, $newContentType, $newContentId)
    {
        $this->db()->update(
            'xf_attachment',
            ['content_type' => $newContentType, 'content_id' => $newContentId],
            'content_type = ? AND content_id = ?',
            [$oldContentType, $oldContentId]
        );
    }

    /**
     * @param array $member
     * @return mixed
     * @throws \XF\Db\Exception
     */
    protected function insertMember(array $member)
    {
        $cols = [];
        $sqlValues = [];
        $bind = [];

        foreach ($member as $key => $value) {
            $cols[] = "`{$key}`";
            $bind[] = $value;
            $sqlValues[] = '?';
        }

        $sql = 'INSERT IGNORE INTO `xf_tl_group_member`
            (' . implode(', ', $cols) . ')
        VALUES
            (' . implode(', ', $sqlValues) . ')';

        return $this->db()->query($sql, $bind)->rowsAffected();
    }

    /**
     * @param array $stepsRun
     * @return array
     */
    public function getFinalizeJobs(array $stepsRun)
    {
        return [
            'Truonglv\Groups:CategoryRebuild',
            'Truonglv\Groups:GroupRebuild',
            'Truonglv\Groups:MemberRebuild',
            'Truonglv\Groups:EventRebuild',
            'Truonglv\Groups:PostRebuild',
            'Truonglv\Groups:CommentRebuild'
        ];
    }
}

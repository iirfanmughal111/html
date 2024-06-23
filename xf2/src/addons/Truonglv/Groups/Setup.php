<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups;

use XF;
use XF\Util\File;
use XF\Entity\Phrase;
use XF\Db\Schema\Alter;
use XF\Db\Schema\Create;
use XF\AddOn\AbstractSetup;
use XF\Install\InstallHelperTrait;
use XF\AddOn\StepRunnerInstallTrait;
use XF\AddOn\StepRunnerUpgradeTrait;
use XF\AddOn\StepRunnerUninstallTrait;
use Truonglv\Groups\DevHelper\SetupTrait;

class Setup extends AbstractSetup
{
    use SetupTrait;
    use InstallHelperTrait;
    use StepRunnerInstallTrait;
    use StepRunnerUpgradeTrait;
    use StepRunnerUninstallTrait;

    public function installStep1()
    {
        $this->doCreateTables($this->getTables());
        $this->doAlterTables($this->getAlters());
    }

    public function installStep2()
    {
        // Manual setup to prevent install error.
        Listener::app_setup($this->app);

        $memberRoleRepo = App::memberRoleRepo();
        $handlers = $memberRoleRepo->getMemberRoleHandlers();

        /** @var \Truonglv\Groups\Entity\MemberRole $adminRole */
        $adminRole = XF::em()->create('Truonglv\Groups:MemberRole');
        $adminRoles = [];

        foreach ($handlers as $roleKey => $roles) {
            $adminRoles[$roleKey] = [];

            foreach ($roles as $roleId => $role) {
                $adminRoles[$roleKey][$roleId] = 1;
            }
        }

        $adminRole->role_permissions = $adminRoles;
        $adminRole->display_order = 10;
        $adminRole->member_role_id = App::MEMBER_ROLE_ID_ADMIN;
        $adminRole->save();

        /** @var Phrase $adminRolePhrase */
        $adminRolePhrase = $adminRole->getMasterPhrase(true);
        $adminRolePhrase->phrase_text = 'Admin';
        $adminRolePhrase->save();

        $modRoles = [];
        foreach ([
                     App::MEMBER_ROLE_PERM_KEY_GROUP,
                     App::MEMBER_ROLE_PERM_KEY_EVENT,
                     App::MEMBER_ROLE_PERM_KEY_MEMBER,
                     App::MEMBER_ROLE_PERM_KEY_COMMENT
                 ] as $roleGroup) {
            $modRoles[$roleGroup] = [];

            foreach ($handlers[$roleGroup] as $roleId => $role) {
                $modRoles[$roleGroup][$roleId] = 1;
            }
        }

        /** @var \Truonglv\Groups\Entity\MemberRole $modRole */
        $modRole = XF::em()->create('Truonglv\Groups:MemberRole');
        $modRole->display_order = 50;
        $modRole->role_permissions = $modRoles;
        $modRole->member_role_id = App::MEMBER_ROLE_ID_MODERATOR;
        $modRole->save();

        /** @var Phrase $modRolePhrase */
        $modRolePhrase = $modRole->getMasterPhrase(true);
        $modRolePhrase->phrase_text = 'Moderator';
        $modRolePhrase->save();

        /** @var \Truonglv\Groups\Entity\MemberRole $memberRole */
        $memberRole = XF::em()->create('Truonglv\Groups:MemberRole');
        $memberRole->display_order = 100;
        $memberRole->role_permissions = [
            App::MEMBER_ROLE_PERM_KEY_EVENT => [
                'add' => 1,
                'editOwn' => 1,
                'deleteOwn' => 1,
                'comment' => 1
            ],
            App::MEMBER_ROLE_PERM_KEY_COMMENT => [
                'editOwn' => 1,
                'deleteOwn' => 1
            ]
        ];
        $memberRole->member_role_id = App::MEMBER_ROLE_ID_MEMBER;
        $memberRole->save();
        /** @var Phrase $memberRolePhrase */
        $memberRolePhrase = $memberRole->getMasterPhrase(true);
        $memberRolePhrase->phrase_text = 'Member';
        $memberRolePhrase->save();

        // creating example category
        /** @var \Truonglv\Groups\Entity\Category $category */
        $category = XF::em()->create('Truonglv\Groups:Category');
        $category->category_title = 'Example Category';
        $category->allow_create_user_group_ids = [-1];
        $category->allow_view_user_group_ids = [-1];
        $category->save();

        $this->applyGlobalPermission(
            App::PERMISSION_GROUP,
            'view',
            'general',
            'view'
        );

        $this->applyGlobalPermission(
            App::PERMISSION_GROUP,
            'editGroupAny',
            'forum',
            'editAnyPost'
        );

        $this->applyGlobalPermission(
            App::PERMISSION_GROUP,
            'deleteGroupAny',
            'forum',
            'deleteAnyPost'
        );

        $this->applyGlobalPermission(
            App::PERMISSION_GROUP,
            'undelete',
            'forum',
            'undelete'
        );

        $this->applyGlobalPermission(
            App::PERMISSION_GROUP,
            'approveUnapprove',
            'forum',
            'approveUnapprove'
        );

        $this->applyGlobalPermission(
            App::PERMISSION_GROUP,
            'bypassViewPrivacy',
            'general',
            'bypassUserPrivacy'
        );

        $this->applyGlobalPermission(
            App::PERMISSION_GROUP,
            'viewModerated',
            'forum',
            'viewModerated'
        );

        $this->applyGlobalPermission(
            App::PERMISSION_GROUP,
            'viewDeleted',
            'forum',
            'viewDeleted'
        );

        $this->applyGlobalPermission(
            App::PERMISSION_GROUP,
            'move',
            'forum',
            'manageAnyThread'
        );

        $this->applyGlobalPermission(
            App::PERMISSION_GROUP,
            'featureUnfeature',
            'forum',
            'stickUnstickThread'
        );

        $this->applyGlobalPermission(
            App::PERMISSION_GROUP,
            'inlineMod',
            'forum',
            'inlineMod'
        );
    }

    public function uninstallStep1()
    {
        $this->doDropColumns($this->getAlters());
        $this->doDropTables($this->getTables());

        $this->query('DROP TABLE IF EXISTS `xf_tl_group_news_feed`');
    }

    public function uninstallStep2()
    {
        $db = $this->db();

        $contentTypesQuoted = $db->quote([
            App::CONTENT_TYPE_GROUP,
            App::CONTENT_TYPE_EVENT,
            App::CONTENT_TYPE_COMMENT,
            App::CONTENT_TYPE_POST
        ]);

        $cleanTables = [
            'xf_user_alert',
            'xf_approval_queue'
        ];
        foreach ($cleanTables as $tableName) {
            $db->delete($tableName, 'content_type IN (' . $contentTypesQuoted . ')');
        }

        $db->update('xf_attachment', ['unassociated' => 1], 'content_type IN (' . $contentTypesQuoted . ')');

        /** @var \XF\Repository\ContentTypeField $contentTypeRepo */
        $contentTypeRepo = $this->app->repository('XF:ContentTypeField');
        $contentTypeRepo->rebuildContentTypeCache();

        File::deleteAbstractedDirectory('data://groups');

        try {
            $this->query('ALTER TABLE `xf_mg_album` DROP COLUMN `tl_group_id`');
        } catch (\XF\Db\Exception $e) {
        }

        try {
            $this->query('ALTER TABLE `xf_node` DROP COLUMN `group_id`');
        } catch (\XF\Db\Exception $e) {
        }

        $this->deleteWidget('tlg_groups_recent');
        $this->deleteWidget('tlg_groups_mostViewed');
    }

    public function upgrade1000800Step1()
    {
        $this->doAlterTables($this->getAlters2());
    }

    public function upgrade2000000Step1()
    {
        $db = $this->db();
        $app = $this->app();

        $avatarGroupIds = $db->fetchAllColumn('
            SELECT group_id
            FROM xf_tl_group
            WHERE avatar_date > ?
        ', [0]);
        $coverGroupIds = $db->fetchAllColumn('
            SELECT group_id
            FROM xf_tl_group
            WHERE cover_date > ?
        ', [0]);

        $app->jobManager()
            ->enqueueUnique('tlgMigrate1000900', 'Truonglv\Groups:Migrate1000900', [
                'avatars' => $avatarGroupIds,
                'covers' => $coverGroupIds
            ]);

        $this->entityColumnsToJson(
            'Truonglv\Groups:Comment',
            ['embed_metadata', 'like_users'],
            0,
            [],
            true
        );
        $this->entityColumnsToJson(
            'Truonglv\Groups:Event',
            ['tags'],
            0,
            [],
            true
        );
        $this->entityColumnsToJson(
            'Truonglv\Groups:Group',
            ['tags', 'custom_fields'],
            0,
            [],
            true
        );

        $this->renameLikeAlertsToReactions([
            App::CONTENT_TYPE_COMMENT
        ]);
        $this->migrateTableToReactions('xf_tl_group_comment');
    }

    public function upgrade2000000Step2()
    {
        $this->doCreateTables($this->getTables2());
    }

    public function upgrade2000000Step3()
    {
        $this->doAlterTables($this->getAlters3());
    }

    public function upgrade2000000Step4()
    {
        $this->query("
            UPDATE `xf_tl_group_comment`
            SET `content_type` = 'event'
            WHERE content_id > '0'
        ");

        try {
            $this->query('
                INSERT INTO xf_tl_group_forum (group_id, node_id)
                SELECT group_id, node_id
                FROM xf_node
                WHERE group_id > ?
                ORDER BY node_id
            ', [0]);
        } catch (\XF\Db\Exception $e) {
        }

        try {
            $this->query('
                INSERT INTO xf_tl_group_mg_album (group_id, album_id)
                SELECT tl_group_id, album_id
                FROM xf_mg_album
                WHERE group_id > ?
                ORDER BY album_id
            ', [0]);
        } catch (\XF\Db\Exception $e) {
        }
    }

    public function upgrade2000100Step1()
    {
        $alters = $this->getAlters3();
        unset($alters['xf_tl_group_comment'], $alters['xf_tl_group']);

        $this->doAlterTables($alters);
    }

    public function upgrade2000500Step1()
    {
        $this->doCreateTables($this->getTables3());
        $this->doAlterTables($this->getAlters4());
    }

    public function upgrade2000500Step2()
    {
        $this->query("
            INSERT INTO `xf_tl_group_event_guest` (`event_id`, `user_id`, `intend`)
            SELECT `event_id`, `user_id`, 'going' FROM `xf_tl_group_event`
        ");
    }

    public function upgrade2000700Step1()
    {
        $this->doAlterTables($this->getAlters5());
    }

    public function upgrade2000900Step1()
    {
        $this->doAlterTables($this->getAlters6());
    }

    public function upgrade2000970Step1()
    {
        $this->doAlterTables($this->getAlters7());
    }

    public function upgrade2010700Step1()
    {
        $this->doCreateTables($this->getTables4());
        $this->doAlterTables($this->getAlters8());
    }

    public function upgrade2010700Step2()
    {
        $data = $this->db()->fetchAll('
            SELECT `owner_user_id`, COUNT(*) AS `total`
            FROM `xf_tl_group`
            GROUP BY 1
        ');
        foreach ($data as $item) {
            $this->db()->update(
                'xf_user',
                ['tlg_total_own_groups' => $item['total']],
                'user_id = ?',
                $item['owner_user_id']
            );
        }
    }

    public function upgrade2010800Step1()
    {
        $this->doCreateTables($this->getTables5());
        $this->doAlterTables($this->getAlters9());
    }

    public function upgrade2020000Step1()
    {
        $this->doCreateTables($this->getTables6());
    }

    public function upgrade2020300Step1()
    {
        $this->doAlterTables($this->getAlters10());
    }

    public function upgrade2020400Step1()
    {
        $this->doAlterTables($this->getAlters11());
    }

    public function upgrade2020600Step1()
    {
        $this->doAlterTables($this->getAlters12());
    }

    public function upgrade2020600Step2()
    {
        $this->executeUpgradeQuery('
            UPDATE `xf_tl_group_event`
            SET `virtual_address` = `address`,
                `location_type` = \'virtual\',
                `address` = \'\'
            WHERE `latitude` = 0 AND `longitude` = 0
        ');
    }

    public function upgrade2030000Step1()
    {
        $this->doCreateTables($this->getTables7());
    }

    public function upgrade3000800Step1()
    {
        $this->doAlterTables($this->getAlters13());
    }

    public function upgrade3010000Step1()
    {
        $this->doAlterTables($this->getAlters14());
    }

    public function upgrade3010100Step1()
    {
        $this->doAlterTables($this->getAlters15());
    }

    public function upgrade3010400Step1(): void
    {
        $this->doAlterTables($this->getAlters16());
    }

    public function upgrade3020200Step1(): void
    {
        $this->doAlterTables($this->getAlters17());
    }

    public function upgrade3020500Step1(): void
    {
        $this->doAlterTables($this->getAlters18());
    }

    public function upgrade3020600Step1(): void
    {
        $this->doAlterTables($this->getAlters19());
    }

    /**
     * @return array
     */
    protected function getTables1()
    {
        $tableList = [];
        $addUserColumns = function (Create $table, $idColumn = 'user_id', $nameColumn = 'username') {
            $table->addColumn($idColumn, 'int')->unsigned();
            $table->addColumn($nameColumn, 'varchar', 50);
        };

        $tableList['xf_tl_group_category'] = function (Create $table) {
            $table->addColumn('category_id', 'int')->unsigned()->autoIncrement();
            $table->addColumn('category_title', 'varchar', 150);
            $table->addColumn('description', 'varchar', 255);
            $table->addColumn('parent_category_id', 'int')->unsigned()->setDefault(0);
            $table->addColumn('depth', 'smallint')->unsigned()->setDefault(0);
            $table->addColumn('lft', 'int')->unsigned()->setDefault(0);
            $table->addColumn('rgt', 'int')->unsigned()->setDefault(0);
            $table->addColumn('display_order', 'int')->unsigned()->setDefault(0);
            $table->addColumn('breadcrumb_data', 'blob');
            $table->addColumn('group_count', 'int')->unsigned()->setDefault(0);
            $table->addColumn('allow_view_user_group_ids', 'varchar', 255);
            $table->addColumn('allow_create_user_group_ids', 'varchar', 255);
            $table->addColumn('min_tags', 'int')->unsigned()->setDefault(0);
            $table->addColumn('always_moderate', 'tinyint')->unsigned()->setDefault(0);
            $table->addColumn('field_cache', 'blob');

            $table->addKey(['parent_category_id', 'lft']);
            $table->addKey(['lft', 'rgt']);
            $table->addKey('display_order');
            $table->addKey('parent_category_id');
        };

        $tableList['xf_tl_group'] = function (Create $table) use ($addUserColumns) {
            $table->addColumn('group_id', 'int')->unsigned()->autoIncrement();
            $table->addColumn('name', 'varchar', 100);
            $table->addColumn('short_description', 'varchar', 255);
            $table->addColumn('description', 'mediumtext');

            $table->addColumn('category_id', 'int')->unsigned();

            // owner_user_id, owner_username
            $addUserColumns($table, 'owner_user_id', 'owner_username');

            $table->addColumn('privacy', 'varbinary', 25)->setDefault('public');
            $table->addColumn('group_state', 'enum', ['visible', 'moderated', 'deleted'])->setDefault('visible');

            $table->addColumn('created_date', 'int')->unsigned()->setDefault(0);

            // counters
            $table->addColumn('member_count', 'int')->unsigned()->setDefault(0);
            $table->addColumn('event_count', 'int')->unsigned()->setDefault(0);
            $table->addColumn('discussion_count', 'int')->unsigned()->setDefault(0);
            $table->addColumn('node_count', 'int')->unsigned()->setDefault(0);

            $table->addColumn('avatar_date', 'int')->unsigned()->setDefault(0);
            $table->addColumn('cover_date', 'int')->unsigned()->setDefault(0);

            $table->addColumn('tags', 'blob')->nullable();
            $table->addColumn('language_code', 'varchar', 32);
            $table->addColumn('member_cache', 'mediumblob');
            $table->addColumn('view_count', 'int')->unsigned()->setDefault(0);

            $table->addColumn('custom_fields', 'mediumblob');

            $table->addColumn('last_updated_date', 'int')->unsigned()->setDefault(0);
            $table->addColumn('member_moderated_count', 'int')->unsigned()->setDefault(0);
            $table->addColumn('always_moderate_join', 'tinyint')->unsigned()->setDefault(0);
            $table->addColumn('cover_crop_data', 'blob');
            $table->addColumn('album_count', 'int')->unsigned()->setDefault(0);

            $table->addKey('owner_user_id');
            $table->addKey(['category_id', 'group_state']);
            $table->addKey('privacy');
        };

        $tableList['xf_tl_group_member'] = function (Create $table) use ($addUserColumns) {
            $table->addColumn('member_id', 'int')->autoIncrement()->primaryKey();
            $table->addColumn('group_id', 'int')->unsigned();

            $addUserColumns($table);

            $table->addColumn('member_state', 'varbinary', 50);
            $table->addColumn('member_role_id', 'varbinary', 50);
            $table->addColumn('joined_date', 'int')->unsigned()->setDefault(0);
            $table->addColumn('alert', 'enum', ['all', 'email', 'alert', 'off'])->setDefault('all');
            $table->addColumn('ban_end_date', 'int')->unsigned()->setDefault(0);

            $table->addUniqueKey(['user_id', 'group_id']);

            $table->addKey('member_state');
        };

        $tableList['xf_tl_group_member_role'] = function (Create $table) {
            $table->addColumn('member_role_id', 'varbinary', 50);
            $table->addColumn('role_permissions', 'blob');
            $table->addColumn('display_order', 'int')->unsigned()->setDefault(0);
            $table->addColumn('user_group_ids', 'blob');

            $table->addPrimaryKey('member_role_id');
            $table->addUniqueKey('member_role_id');
        };

        $tableList['xf_tl_group_view_log'] = function (Create $table) {
            $table->engine('MEMORY');

            $table->addColumn('group_id', 'int')->unsigned();
            $table->addColumn('total', 'int')->unsigned()->setDefault(0);

            $table->addPrimaryKey('group_id');
        };

        $tableList['xf_tl_group_feature'] = function (Create $table) {
            $table->addColumn('group_id', 'int')->unsigned();
            $table->addColumn('feature_date', 'int')->unsigned()->setDefault(0);
            $table->addColumn('expire_date', 'int')->unsigned()->setDefault(0);

            $table->addPrimaryKey('group_id');
            $table->addUniqueKey('group_id');
        };

        $tableList['xf_tl_group_event'] = function (Create $table) {
            $table->addColumn('event_id', 'int')->unsigned()->autoIncrement();
            $table->addColumn('event_name', 'varchar', 150);
            $table->addColumn('group_id', 'int')->unsigned();
            $table->addColumn('user_id', 'int')->unsigned();
            $table->addColumn('username', 'varchar', 50);
            $table->addColumn('first_comment_id', 'int')->unsigned()->setDefault(0);
            $table->addColumn('created_date', 'int')->unsigned()->setDefault(0);

            $table->addColumn('begin_date', 'int')->unsigned()->setDefault(0);
            $table->addColumn('end_date', 'int')->unsigned()->setDefault(0);
            $table->addColumn('timezone', 'varchar', 25)->setDefault('');

            $table->addColumn('cover_date', 'int')->unsigned()->setDefault(0);
            $table->addColumn('comment_count', 'int')->unsigned()->setDefault(0);

            $table->addColumn('address', 'varchar', '150');
            $table->addColumn('tags', 'blob')->nullable();

            $table
                ->addColumn('latitude', 'decimal', '11,7')
                ->unsigned(false)
                ->setDefault(0);
            $table
                ->addColumn('longitude', 'decimal', '11,7')
                ->unsigned(false)
                ->setDefault(0);

            $table->addColumn('first_comment_likes', 'int')->unsigned()->setDefault(0);

            $table->addColumn('last_comment_id', 'int')->unsigned()->setDefault(0);
            $table->addColumn('last_comment_date', 'int')->unsigned()->setDefault(0);
            $table->addColumn('last_comment_user_id', 'int')->unsigned()->setDefault(0);
            $table->addColumn('last_comment_username', 'varchar', 50)->setDefault('');

            $table->addKey(['group_id', 'begin_date', 'end_date']);
            $table->addKey('user_id');
            $table->addKey('first_comment_id');
        };

        $tableList['xf_tl_group_comment'] = function (Create $table) {
            $table->addColumn('comment_id', 'int')->unsigned()->autoIncrement();
            $table->addColumn('user_id', 'int')->unsigned();
            $table->addColumn('username', 'varchar', 50);
            $table->addColumn('message', 'mediumtext');
            $table->addColumn('position', 'int')->unsigned()->setDefault(0);
            $table->addColumn('event_id', 'int')->unsigned();
            $table->addColumn('comment_date', 'int')->unsigned()->setDefault(0);
            $table->addColumn('embed_metadata', 'blob')->nullable();
            $table->addColumn('attach_count', 'int')->unsigned()->setDefault(0);
            $table->addColumn('reaction_score', 'int')->unsigned()->setDefault(0);
            $table->addColumn('reaction_users', 'blob')->nullable();
            $table->addColumn('ip_id', 'int')->unsigned()->setDefault(0);

            $table->addKey('user_id');
            $table->addKey(['event_id', 'position']);
        };

        $tableList['xf_tl_group_field'] = function (Create $table) {
            $table->addColumn('field_id', 'varbinary', 25);
            $table->addColumn('display_group', 'varchar', 25)->setDefault('about');
            $table->addColumn('display_order', 'int')->unsigned()->setDefault(1);
            $table->addColumn('field_type', 'varbinary', 25);
            $table->addColumn('field_choices', 'blob');
            $table->addColumn('match_type', 'varbinary', 25);
            $table->addColumn('match_params', 'blob');
            $table->addColumn('max_length', 'int')->unsigned()->setDefault(0);
            $table->addColumn('required', 'tinyint')->unsigned()->setDefault(0);
            $table->addColumn('display_template', 'text');

            $table->addPrimaryKey('field_id');
            $table->addKey(['display_group', 'display_order']);
        };

        $tableList['xf_tl_group_field_value'] = function (Create $table) {
            $table->addColumn('group_id', 'int')->unsigned();
            $table->addColumn('field_id', 'varbinary', 25);
            $table->addColumn('field_value', 'mediumtext');

            $table->addPrimaryKey(['group_id', 'field_id']);
            $table->addKey('field_id');
        };

        $tableList['xf_tl_group_category_field'] = function (Create $table) {
            $table->addColumn('category_id', 'int')->unsigned();
            $table->addColumn('field_id', 'varbinary', 25);
            $table->addPrimaryKey(['category_id', 'field_id']);
        };

        $tableList['xf_tl_group_event_watch'] = function (Create $table) {
            $table->addColumn('user_id', 'int')->unsigned();
            $table->addColumn('event_id', 'int')->unsigned();

            $table->addPrimaryKey(['user_id', 'event_id']);
        };

        $tableList['xf_tl_group_view'] = function (Create $table) {
            $table->addColumn('group_id', 'int')->unsigned();
            $table->addColumn('user_id', 'int')->unsigned();
            $table->addColumn('view_date', 'int')->unsigned();

            $table->addPrimaryKey(['group_id', 'user_id']);
            $table->addKey('view_date');

            $table->engine('MEMORY');
        };

        return $tableList;
    }

    /**
     * @return array
     */
    protected function getTables2()
    {
        $tables = [];

        $tables['xf_tl_group_post'] = function (Create $table) {
            $table->addColumn('post_id', 'int')->unsigned()->autoIncrement();
            $table->addColumn('group_id', 'int')->unsigned();
            $table->addColumn('user_id', 'int')->unsigned();
            $table->addColumn('username', 'varchar', 50);

            $table->addColumn('first_comment_id', 'int')->unsigned();
            $table->addColumn('latest_comment_ids', 'blob');

            $table->addColumn('post_date', 'int')->unsigned();
            $table->addColumn('last_comment_date', 'int')->unsigned();
            $table->addColumn('comment_count', 'int')->setDefault(0);

            $table->addKey(['group_id', 'last_comment_date']);
            $table->addKey('user_id');
            $table->addKey('last_comment_date');
        };

        $tables['xf_tl_group_forum'] = function (Create $table) {
            $table->addColumn('group_id', 'int')->unsigned();
            $table->addColumn('node_id', 'int')->unsigned()->primaryKey();
        };

        $tables['xf_tl_group_mg_album'] = function (Create $table) {
            $table->addColumn('group_id', 'int')->unsigned();
            $table->addColumn('album_id', 'int')->unsigned()->primaryKey();
        };

        return $tables;
    }

    /**
     * @return array
     */
    protected function getTables3()
    {
        $tables = [];

        $tables['xf_tl_group_event_guest'] = function (Create $table) {
            $table->addColumn('event_id', 'int')->unsigned();
            $table->addColumn('user_id', 'int')->unsigned();

            // going, not going, maybe
            $table->addColumn('intend', 'varchar', 25)->setDefault('');

            $table->addUniqueKey(['event_id', 'user_id']);
        };

        return $tables;
    }

    /**
     * @return array
     */
    protected function getTables4()
    {
        $tables = [];

        $tables['xf_tl_group_action_log'] = function (Create $table) {
            $table->addColumn('log_id', 'int')->autoIncrement();
            $table->addColumn('group_id', 'int');
            $table->addColumn('content_type', 'varchar', 25);
            $table->addColumn('content_id', 'int');
            $table->addColumn('action', 'varchar', 32);
            $table->addColumn('extra_data', 'mediumblob');
            $table->addColumn('user_id', 'int');
            $table->addColumn('log_date', 'int');

            $table->addKey('group_id');
            $table->addKey(['content_type', 'content_id']);
            $table->addKey('log_date');
        };

        return $tables;
    }

    /**
     * @return array
     */
    protected function getTables5()
    {
        $tables = [];

        $tables['xf_tl_group_activity'] = function (Create $table) {
            $table->addColumn('group_id', 'int');
            $table->addColumn('activity_date', 'int')->setDefault(0);

            $table->addPrimaryKey(['group_id']);

            $table->engine('MEMORY');
        };

        return $tables;
    }

    /**
     * @return array
     */
    protected function getTables6()
    {
        $tables = [];

        $tables['xf_tl_group_user_cache'] = function (Create $table) {
            $table->addColumn('user_id', 'int')->primaryKey();
            $table->addColumn('cache_data', 'mediumtext');
        };

        return $tables;
    }

    /**
     * @return array
     */
    protected function getTables7()
    {
        return [
            'xf_tl_group_resource' => function (Create $table) {
                $table->addColumn('resource_id', 'int')->autoIncrement();
                $table->addColumn('title', 'varchar', 100);
                $table->addColumn('group_id', 'int');
                $table->addColumn('user_id', 'int');
                $table->addColumn('username', 'varchar', 50);
                $table->addColumn('attach_count', 'int')->setDefault(0);
                $table->addColumn('resource_date', 'int')->setDefault(0);
                $table->addColumn('download_count', 'int')->setDefault(0);
                $table->addColumn('view_count', 'int')->setDefault(0);
                $table->addColumn('first_comment_id', 'int')->setDefault(0);
                $table->addColumn('latest_comment_ids', 'blob');
                $table->addColumn('last_comment_date', 'int')->setDefault(0);
                $table->addColumn('comment_count', 'int')->setDefault(0);

                $table->addKey('group_id');
                $table->addKey('user_id');
                $table->addKey('first_comment_id');
            },
            'xf_tl_group_resource_download_log' => function (Create $table) {
                $table->addColumn('user_id', 'int');
                $table->addColumn('resource_id', 'int');
                $table->addColumn('download_date', 'int')->setDefault(0);
                $table->addColumn('total', 'int')->setDefault(1);

                $table->addPrimaryKey(['user_id', 'resource_id']);
            },
            'xf_tl_group_resource_view' => function (Create $table) {
                $table->addColumn('resource_id', 'int');
                $table->addColumn('total', 'int');

                $table->addPrimaryKey('resource_id');
                $table->engine('MEMORY');
            },
            'xf_tl_group_resource_download' => function (Create $table) {
                $table->addColumn('resource_id', 'int');
                $table->addColumn('total', 'int');

                $table->addPrimaryKey('resource_id');
                $table->engine('MEMORY');
            }
        ];
    }

    /**
     * @return array
     */
    protected function getAlters1()
    {
        return [];
    }

    /**
     * @return array
     */
    protected function getAlters2()
    {
        $alters = [];

        $alters['xf_tl_group_event'] = [
            'latitude' => function (Alter $table) {
                $table
                    ->changeColumn('latitude', 'decimal', '11,7')
                    ->unsigned(false)
                    ->setDefault(0);
            },
            'longitude' => function (Alter $table) {
                $table
                    ->changeColumn('longitude', 'decimal', '11,7')
                    ->unsigned(false)
                    ->setDefault(0);
            }
        ];

        return $alters;
    }

    /**
     * @return array
     */
    protected function getAlters3()
    {
        $alters = [];

        $alters['xf_tl_group_comment'] = [
            'content_id' => function (Alter $table) {
                $table->renameColumn('event_id', 'content_id');
            },
            'content_type' => function (Alter $table) {
                $table->addColumn('content_type', 'varchar', 25)->setDefault('');
            },
            'reaction_score' => function (Alter $table) {
                $table->renameColumn('likes', 'reaction_score');
            },
            'reaction_users' => function (Alter $table) {
                $table->renameColumn('like_users', 'reaction_users');
            },
            'message_state' => function (Alter $table) {
                $table->addColumn('message_state', 'enum', ['visible', 'moderated', 'deleted'])
                    ->setDefault('visible');
            },
            'parent_id' => function (Alter $table) {
                $table->addColumn('parent_id', 'int')->unsigned()->setDefault(0);
                $table->addKey(['content_type', 'content_id', 'parent_id'], 'content_type_id_parent');
            },
            'event_id_position' => function (Alter $table) {
                $table->dropIndexes(['event_id_position']);
            },
            'latest_reply_ids' => function (Alter $table) {
                $table->addColumn('latest_reply_ids', 'BLOB')->nullable();
            },
            'reply_count' => function (Alter $table) {
                $table->addColumn('reply_count', 'int')
                    ->unsigned()
                    ->setDefault(0);
            },
            'position' => function (Alter $table) {
                $table->dropColumns(['position']);
            },
            'last_edit_date' => function (Alter $table) {
                $table->addColumn('last_edit_date', 'int')
                    ->unsigned()
                    ->setDefault(0);
            },
            'last_edit_user_id' => function (Alter $table) {
                $table->addColumn('last_edit_user_id', 'int')
                    ->unsigned()
                    ->setDefault(0);
            },
            'edit_count' => function (Alter $table) {
                $table->addColumn('edit_count', 'int')
                    ->unsigned()
                    ->setDefault(0);
            }
        ];
        $alters['xf_tl_group_event'] = [
            'first_comment_reaction_score' => function (Alter $table) {
                $table->dropColumns(['first_comment_likes']);
            },
            'last_comment_user_id' => function (Alter $table) {
                $table->dropColumns(['last_comment_user_id']);
            },
            'last_comment_username' => function (Alter $table) {
                $table->dropColumns(['last_comment_username']);
            },
            'last_comment_id' => function (Alter $table) {
                $table->dropColumns(['last_comment_id']);
            },
            'latest_comment_ids' => function (Alter $table) {
                $table->addColumn('latest_comment_ids', 'BLOB')->nullable();
            }
        ];

        $alters['xf_tl_group'] = [
            'cover_date' => function (Alter $table) {
                $table->dropColumns(['cover_date']);
            },
            'cover_attachment_id' => function (Alter $table) {
                $table->addColumn('cover_attachment_id', 'int')->unsigned()->setDefault(0);
            },
            'avatar_date' => function (Alter $table) {
                $table->dropColumns(['avatar_date']);
            },
            'avatar_attachment_id' => function (Alter $table) {
                $table->addColumn('avatar_attachment_id', 'int')->unsigned()->setDefault(0);
            }
        ];

        if (!$this->schemaManager()->columnExists('xf_tl_group_comment', 'reactions')) {
            $alters['xf_tl_group_comment']['reactions'] = function (Alter $table) {
                $table->addColumn('reactions', 'blob');
            };
        }

        return $alters;
    }

    /**
     * @return array
     */
    protected function getAlters4()
    {
        $alters = [];

        $alters['xf_tl_group_post'] = [
            'sticky' => function (Alter $table) {
                $table->addColumn('sticky', 'tinyint')->unsigned()->setDefault(0);
            }
        ];

        return $alters;
    }

    /**
     * @return array
     */
    protected function getAlters5()
    {
        $alters = [];

        $alters['xf_tl_group_comment'] = [
            'reaction_score' => function (Alter $table) {
                $table->changeColumn('reaction_score', 'int')
                    ->unsigned(false)
                    ->setDefault(0);
            }
        ];

        return $alters;
    }

    /**
     * @return array
     */
    protected function getAlters6()
    {
        $alters = [];

        $alters['xf_tl_group'] = [
            'allow_guest_posting' => function (Alter $table) {
                $table->addColumn('allow_guest_posting', 'tinyint', 1)
                    ->unsigned()
                    ->setDefault(0);
            }
        ];

        return $alters;
    }

    /**
     * @return array
     */
    protected function getAlters7()
    {
        $alters = [];

        $alters['xf_tl_group'] = [
            'last_activity' => function (Alter $table) {
                $table->renameColumn('last_updated_date', 'last_activity');
            }
        ];

        return $alters;
    }

    /**
     * @return array
     */
    protected function getAlters8()
    {
        $this->applyGlobalPermissionInt(
            App::PERMISSION_GROUP,
            'maxCreateGroups',
            5
        );

        $alters = [];

        $alters['xf_user'] = [
            'tlg_total_own_groups' => function (Alter $table) {
                $table->addColumn('tlg_total_own_groups', 'int')->setDefault(0);
            }
        ];

        return $alters;
    }

    /**
     * @return array
     */
    protected function getAlters9()
    {
        $alters = [];

        $this->createWidget(
            'tlg_groups_recent',
            'tl_groups_group',
            [
                'positions' => [
                    'tlg_group_index' => 50,
                    'tlg_category_view' => 50
                ],
                'options' => [
                    'order' => 'created_date',
                    'direction' => 'desc'
                ]
            ],
            'Recent groups'
        );

        $this->createWidget(
            'tlg_groups_mostViewed',
            'tl_groups_group',
            [
                'positions' => [
                    'tlg_group_index' => 150,
                    'tlg_category_view' => 150
                ],
                'options' => [
                    'order' => 'view_count',
                    'direction' => 'desc'
                ]
            ],
            'Most viewed groups'
        );

        return $alters;
    }

    /**
     * @return array
     */
    protected function getAlters10()
    {
        $alters = [];

        $alters['xf_tl_group'] = [
            'index:category_state_privacy' => function (Alter $table) {
                $table->dropIndexes('category_id_group_state');
                $table->dropIndexes('privacy');
                $table->addKey('category_id');
                $table->addKey([
                    'category_id',
                    'group_state',
                    'privacy'
                ], 'category_state_privacy');
            }
        ];

        return $alters;
    }

    /**
     * @return array
     */
    protected function getAlters11()
    {
        $alters = [];

        $alters['xf_tl_group'] = [
            'index:20200602' => function (Alter $table) {
                $table->dropIndexes('category_id');
                $table->dropIndexes('category_state_privacy');
                $table->addKey(['category_id', 'last_activity'], 'category_last_activity');
            }
        ];

        $alters['xf_tl_group_post'] = [
            'index:20200602' => function (Alter $table) {
                $table->dropIndexes('group_id_last_comment_date');
                $table->addKey([
                    'group_id',
                    'sticky',
                    'last_comment_date'
                ], 'group_sticky_last_comment_date');
            }
        ];

        $alters['xf_tl_group_member'] = [
            'index:20200602' => function (Alter $table) {
                $table->dropIndexes('member_state');
                $table->addKey([
                    'group_id',
                    'member_state',
                    'joined_date'
                ]);
            }
        ];

        return $alters;
    }

    /**
     * @return array
     */
    protected function getAlters12()
    {
        $alters = [];

        $alters['xf_tl_group_event'] = [
            'location_type' => function (Alter $table) {
                $table->addColumn('location_type', 'varchar', 25)->setDefault('');
            },
            'virtual_address' => function (Alter $table) {
                $table->addColumn('virtual_address', 'varchar', 255)->setDefault('');
            }
        ];

        return $alters;
    }

    protected function getAlters13(): array
    {
        return [
            'xf_tl_group_resource' => [
                'icon_date' => function (Alter $table) {
                    $table->addColumn('icon_date', 'int')
                        ->setDefault(0);
                },
                'icon_url' => function (Alter $table) {
                    $table->addColumn('icon_url', 'varchar', 255)
                        ->setDefault('');
                },
            ],
            'xf_tl_group_category' => [
                'default_privacy' => function (Alter $table) {
                    $table->addColumn('default_privacy', 'varchar', 25)
                        ->setDefault(App::PRIVACY_PUBLIC);
                }
            ]
        ];
    }

    protected function getAlters14(): array
    {
        return [
            'xf_tl_group_event' => [
                'cancelled_date' => function (Alter $table) {
                    $table->addColumn('cancelled_date', 'int')->setDefault(0);
                },
            ],
        ];
    }

    protected function getAlters15(): array
    {
        return [
            'xf_user_privacy' => [
                'tlg_allow_view_groups' => function (Alter $table) {
                    $table->addColumn(
                        'tlg_allow_view_groups',
                        'enum',
                        ['everyone', 'members', 'followed', 'none']
                    )->setDefault('everyone');
                }
            ]
        ];
    }

    protected function getAlters16(): array
    {
        return [
            'xf_tl_group_event' => [
                'max_attendees' => function (Alter $table) {
                    $table->addColumn('max_attendees', 'int')->setDefault(0);
                },
                'attendee_count' => function (Alter $table) {
                    $table->addColumn('attendee_count', 'int')->setDefault(0);
                }
            ]
        ];
    }

    protected function getAlters17(): array
    {
        return [
            'xf_user' => [
                'tlg_badge_group_id' => function (Alter $table) {
                    $table->addColumn('tlg_badge_group_id', 'int')->setDefault(0);
                }
            ]
        ];
    }

    protected function getAlters18(): array
    {
        return [
            'xf_user_option' => [
                'tlg_show_badge' => function (Alter $table) {
                    $table->addColumn('tlg_show_badge', 'tinyint')->setDefault(1);
                },
            ]
        ];
    }

    protected function getAlters19(): array
    {
        return [
            'xf_tl_group_category' => [
                'disabled_navigation_tabs' => function (Alter $table) {
                    $table->addColumn('disabled_navigation_tabs', 'varchar', 255)->setDefault('');
                },
                'default_tab' => function (Alter $table) {
                    $table->addColumn('default_tab', 'varchar', 16)->setDefault('');
                },
            ],
        ];
    }
}

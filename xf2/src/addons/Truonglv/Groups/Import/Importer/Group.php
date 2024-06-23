<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Import\Importer;

use XF;
use XF\Timer;
use Exception;
use XF\Util\Php;
use XF\Util\File;
use function copy;
use function floor;
use XF\Http\Upload;
use function explode;
use function sprintf;
use XF\Entity\Phrase;
use function basename;
use function array_map;
use Truonglv\Groups\App;
use XF\Import\StepState;
use function file_exists;
use function json_decode;
use function json_encode;
use function unserialize;
use function array_column;
use function array_replace;
use function array_key_exists;
use function array_replace_recursive;
use Truonglv\Groups\Import\Data\Comment;
use Truonglv\Groups\Service\Group\Cover;
use Truonglv\Groups\Service\Group\Avatar;
use XF\Import\Importer\XenForoSourceTrait;

class Group extends AbstractGroupImporter
{
    use XenForoSourceTrait {
        XenForoSourceTrait::getBaseConfigDefault as XFGetBaseConfigDefault;
    }

    /**
     * @param \XF\Db\AbstractAdapter $db
     * @param string $error
     * @return bool
     */
    protected function validateVersion(\XF\Db\AbstractAdapter $db, & $error)
    {
        return true;
    }

    /**
     * @return array
     */
    public static function getListInfo()
    {
        return [
            'target' => '[tl] Social Groups',
            'source' => '[Nobita] Social Groups'
        ];
    }

    /**
     * @return array
     */
    public function getSteps()
    {
        $steps = parent::getSteps();

        $steps['memberRoles'] = ['title' => XF::phrase('tlg_member_roles')];
        $steps['events'] = [
            'title' => XF::phrase('tlg_events'),
            'depends' => ['groups']
        ];
        $steps['posts'] = [
            'title' => XF::phrase('tlg_posts'),
            'depends' => ['groups']
        ];

        $steps['forums'] = [
            'title' => XF::phrase('tlg_forums'),
            'depends' => ['groups']
        ];
        $steps['fields'] = ['title' => XF::phrase('tlg_custom_fields')];

        return $steps;
    }

    /**
     * @param array $vars
     * @return string
     */
    public function renderStepConfigOptions(array $vars)
    {
        return $this->app->templater()->renderTemplate('admin:tlg_import_step_config_xenforo', $vars);
    }

    /**
     * @param array $vars
     * @return string
     */
    public function renderBaseConfigOptions(array $vars)
    {
        $vars = array_replace_recursive([
            'baseConfig' => [
                'suggestion_data_dir' => File::canonicalizePath(XF::config('externalDataPath')),
                'suggestion_internal_data_dir' => File::canonicalizePath(XF::config('internalDataPath'))
            ]
        ], $vars);

        return $this->app->templater()->renderTemplate('admin:tlg_import_config_xenforo', $vars);
    }

    /**
     * @param StepState $state
     * @return StepState
     * @throws Exception
     */
    public function stepCategories(StepState $state)
    {
        $categories = $this->sourceDb->fetchAll('
            SELECT *
            FROM xf_team_category
            ORDER BY team_category_id
        ');

        foreach ($categories as $category) {
            $oldCategoryId = $category['team_category_id'];

            $data = $this->mapKeys($category, [
                'category_title',
                'category_description' => 'description',
                'display_order',
                'min_tags',
                'always_moderate_create' => 'always_moderate'
            ]);

            $data['parent_category_id'] = (int) $this->lookup('tl_group_category', $category['parent_category_id']);
            $data['allow_view_user_group_ids'] = [-1];

            if ($category['allow_team_create']) {
                $data['allow_create_user_group_ids'] =
                    array_map('intval', explode(',', $category['allowed_user_group_ids']));
            } else {
                $data['allow_create_user_group_ids'] = [0];
            }

            /** @var \Truonglv\Groups\Import\Data\Category $import */
            $import = $this->newHandler('Truonglv\Groups:Category');
            $import->bulkSet($data);
            $import->save($oldCategoryId);

            $state->imported++;
        }

        return $state->complete();
    }

    /**
     * @param StepState $state
     * @return StepState
     * @throws \XF\PrintableException
     */
    public function stepMemberRoles(StepState $state)
    {
        $memberRoles = $this->sourceDb->fetchAll("
            SELECT member_role.*, phrase.phrase_text AS title
            FROM xf_team_member_role AS member_role
                INNER JOIN xf_phrase AS phrase 
                    ON (phrase.language_id = 0 AND phrase.title = CONCAT('Teams_member_role_id_', CAST(member_role.member_role_id AS CHAR(100))))
            ORDER BY member_role.member_role_id
        ");

        $existingMemberRoles = $this->db()->fetchPairs('
            SELECT member_role_id, member_role_id
            FROM xf_tl_group_member_role
        ');

        foreach ($memberRoles as $memberRole) {
            if (isset($existingMemberRoles[$memberRole['member_role_id']])) {
                $state->imported++;

                continue;
            }

            /** @var \Truonglv\Groups\Entity\MemberRole $entity */
            $entity = XF::em()->create('Truonglv\Groups:MemberRole');
            $entity->member_role_id = $memberRole['member_role_id'];
            $entity->role_permissions = [];
            $entity->display_order = $memberRole['display_order'];
            if (isset($memberRole['secondary_group_ids'])) {
                $entity->user_group_ids = (array) json_decode($memberRole['secondary_group_ids'], true);
            } elseif (isset($memberRole['user_group_ids'])) {
                $entity->user_group_ids = (array) json_decode($memberRole['user_group_ids'], true);
            }

            $entity->save();

            /** @var Phrase $phrase */
            $phrase = $entity->getMasterPhrase(true);
            $phrase->phrase_text = $memberRole['title'];
            $phrase->save();

            $state->imported++;
        }

        return $state->complete();
    }

    /**
     * @return int
     */
    public function getStepEndGroups()
    {
        return $this->sourceDb->fetchOne('SELECT MAX(team_id) FROM xf_team');
    }

    /**
     * @param StepState $state
     * @param array $stepConfig
     * @param mixed $maxTime
     * @return StepState
     */
    public function stepGroups(StepState $state, array $stepConfig, $maxTime)
    {
        $limit = 500;
        $timer = new Timer($maxTime);

        $groups = $this->sourceDb->fetchAll("
            SELECT team.*
            FROM xf_team AS team
            WHERE team.team_id > ? AND team.team_id <= ?
            ORDER BY team.team_id
            LIMIT {$limit}
        ", [$state->startAfter, $state->end]);

        if (!$groups) {
            return $state->complete();
        }

        foreach ($groups as $group) {
            $oldId = $group['team_id'];
            $state->startAfter = $oldId;

            if ($this->importGroup($group) > 0) {
                $state->imported++;
            }

            if ($timer->limitExceeded()) {
                break;
            }
        }

        return $state->resumeIfNeeded();
    }

    /**
     * @param array $group
     * @return int
     * @throws \XF\Db\Exception
     */
    protected function importGroup(array $group)
    {
        // Fix issue: https://nobita.me/threads/1914/
        $group = array_replace([
            'about' => '',
            'tag_line' => '',
            'language_code' => ''
        ], $group);

        $data = $this->mapKeys($group, [
            'title' => 'name',
            'user_id' => 'owner_user_id',
            'username' => 'owner_username',
            'team_state' => 'group_state',
            'team_date' => 'created_date',
            'view_count',
            'about' => 'description',
            'tag_line' => 'short_description',
            'language_code'
        ]);

        if ($group['privacy_state'] === 'open') {
            $data['privacy'] = 'public';
        } else {
            $data['privacy'] = $group['privacy_state'];
        }

        $data['category_id'] = $this->lookup('tl_group_category', $group['team_category_id']);

        $data['tags'] = unserialize($group['tags']);
        if (!\is_array($data['tags'])) {
            $data['tags'] = [];
        }
        if ($data['description'] === '') {
            $data['description'] = $data['short_description'];
        }

        /** @var \Truonglv\Groups\Import\Data\Group $handler */
        $handler = $this->newHandler('Truonglv\Groups:Group');

        $handler->bulkSet($data);
        $handler->set('always_moderate_join', $group['always_moderate_join']);

        $newId = $handler->save($group['team_id']);
        /** @var \Truonglv\Groups\Entity\Group $groupEntity */
        $groupEntity = $this->app->em()->find('Truonglv\Groups:Group', $newId);

        if ($newId <= 0) {
            return 0;
        }

        $this->importMembers($group['team_id'], $newId);

        if ($group['team_avatar_date'] > 0) {
            $avatarFile = sprintf(
                '%s/nobita/teams/avatars/%d/%d.jpg',
                $this->baseConfig['data_dir'],
                floor($group['team_id'] / 1000),
                $group['team_id']
            );

            /** @var Avatar $avatar */
            $avatar = XF::service('Truonglv\Groups:Group\Avatar', $groupEntity);
            $tempFile = File::getTempFile();
            if (file_exists($avatarFile) && copy($avatarFile, $tempFile)) {
                $upload = new Upload($tempFile, basename($avatarFile));
                $avatar->setUpload($upload);

                if (!$avatar->validate($errors)) {
                    App::logError('Failed to import group avatar. $errors=' . json_encode($errors) . ' $groupId=' . $newId);
                } else {
                    $avatar->upload();
                }
            }
        }

        if ($group['cover_date'] > 0) {
            $coverPath = sprintf(
                '%s/teams/covers/%d/%d.jpg',
                $this->baseConfig['data_dir'],
                floor($group['team_id'] / 1000),
                $group['team_id']
            );

            /** @var Cover $cover */
            $cover = XF::service('Truonglv\Groups:Group\Cover', $groupEntity);
            $cover->setSkipCheckDimensions(true);
            $tempFile = File::getTempFile();
            if (file_exists($coverPath) && copy($coverPath, $tempFile)) {
                $upload = new Upload($tempFile, basename($coverPath));
                $cover->setUpload($upload);

                if (!$cover->validate($errors)) {
                    App::logError('Failed to import group cover. $errors=' . json_encode($errors) . ' $groupId=' . $newId);
                } else {
                    $cover->upload();
                }
            }
        }

        $feature = $this->sourceDb->fetchRow('
            SELECT *
            FROM xf_team_feature
            WHERE team_id = ?
        ', $group['team_id']);

        if ($feature) {
            $this->app->db()->query('
                INSERT IGNORE INTO xf_tl_group_feature
                    (group_id, feature_date, expire_date)
                VALUES
                    (?, ?, ?)
            ', [
                $newId, (int) $feature['feature_date'], (int) $feature['expire_date']
            ]);
        }

        return (int) $newId;
    }

    /**
     * @param int $oldGroupId
     * @param int $newGroupId
     * @throws Exception
     * @return void
     */
    public function importMembers($oldGroupId, $newGroupId)
    {
        $lastId = 0;
        $stepLimit = 1000;
        $emptyCheckCounts = 0;

        while (true) {
            $members = $this->sourceDb->fetchAll('
                SELECT *
                FROM xf_team_member
                WHERE team_id = ? AND user_id > ?
                ORDER BY user_id
                LIMIT ' . $stepLimit . '
            ', [
                $oldGroupId, $lastId
            ]);

            if (count($members) <= 0) {
                if ($emptyCheckCounts > 3) {
                    break;
                }

                $emptyCheckCounts++;
                $lastId += $stepLimit;

                continue;
            }

            foreach ($members as $member) {
                $lastId = $member['user_id'];

                $data = $this->mapKeys($member, [
                    'user_id',
                    'username',
                    'member_role_id',
                    'join_date' => 'joined_date'
                ]);

                $data['group_id'] = $newGroupId;

                if ($member['member_state'] === 'accept') {
                    $data['member_state'] = App::MEMBER_STATE_VALID;
                } elseif ($member['member_state'] === 'request') {
                    $data['member_state'] = App::MEMBER_STATE_MODERATED;
                    $data['member_role_id'] = '';
                }

                if ($member['action'] === 'invite') {
                    $data['member_state'] = App::MEMBER_STATE_INVITED;
                }

                if ($member['member_role_id'] === 'admin') {
                    $data['member_role_id'] = App::MEMBER_ROLE_ID_ADMIN;
                }

                $banned = $this->sourceDb->fetchRow('
                    SELECT *
                    FROM xf_team_ban
                    WHERE team_id = ? AND user_id = ?
                ', [
                    $member['team_id'], $member['user_id']
                ]);

                if ($banned) {
                    $data['member_state'] = App::MEMBER_STATE_BANNED;
                    $data['ban_end_date'] = $banned['end_date'];
                }

                $db = $this->db();
                $db->beginTransaction();

                try {
                    $this->insertMember($data);
                } catch (Exception $e) {
                    $db->rollback();

                    throw $e;
                }

                $db->commit();
            }
        }
    }

    /**
     * @return int
     */
    public function getStepEndEvents()
    {
        return $this->sourceDb->fetchOne('SELECT MAX(event_id) FROM xf_team_event');
    }

    /**
     * @param StepState $state
     * @param array $stepConfig
     * @param mixed $maxTime
     * @return StepState
     * @throws Exception
     */
    public function stepEvents(StepState $state, array $stepConfig, $maxTime)
    {
        $limit = 500;
        $timer = new Timer($maxTime);

        $events = $this->sourceDb->fetchAll("
            SELECT *
            FROM xf_team_event
            WHERE event_id > ? AND event_id <= ?
            ORDER BY event_id
            LIMIT {$limit}
        ", [$state->startAfter, $state->end]);

        if (!$events) {
            return $state->complete();
        }

        $mapGroupIds = $this->lookup('tl_group', array_column($events, 'team_id'));

        foreach ($events as $event) {
            $oldId = $event['event_id'];
            $state->startAfter = $oldId;

            if (!isset($mapGroupIds[$event['team_id']])) {
                continue;
            }

            $baseConfig = $this->getBaseConfig();
            $eventMapKeys = [
                'event_title' => 'event_name',
                'user_id',
                'username',
                'publish_date' => 'created_date',
                'begin_date',
                'end_date',
                'timezone'
            ];
            if ($baseConfig['old_version_id'] === '2.9.1a') {
                $eventMapKeys += [
                    'address',
                    'latitude',
                    'longitude'
                ];
            }

            $data = $this->mapKeys($event, $eventMapKeys);

            $data['tags'] = @unserialize($event['tags']);
            if (!\is_array($data['tags'])) {
                $data['tags'] = [];
            }

            $data['group_id'] = $mapGroupIds[$event['team_id']];
            /** @var \Truonglv\Groups\Import\Data\Event $eventHandler */
            $eventHandler = $this->newHandler('Truonglv\Groups:Event');
            $eventHandler->bulkSet($data);
            $newId = $eventHandler->save($oldId);

            if (!$newId) {
                continue;
            }

            $commentData = [
                'user_id' => $data['user_id'],
                'username' => $data['username'],
                'message' => $event['event_description'],
                'comment_date' => $data['created_date'],
                'content_type' => 'event',
                'content_id' => $event['event_id']
            ];

            $map = [
                'tl_group_event' => [
                    $event['event_id'] => $newId
                ]
            ];
            $newCommentId = $this->importComment($commentData, $map);

            $comments = $this->sourceDb->fetchAll('
                SELECT *
                FROM xf_team_comment
                WHERE content_type = ? AND content_id = ?
                ORDER BY comment_date
            ', ['event', $oldId]);
            foreach ($comments as $comment) {
                $this->importComment($comment, $map);
            }

            if ($newCommentId > 0) {
                $this->updateAttachments('team_event', $oldId, App::CONTENT_TYPE_COMMENT, $newCommentId);

                $state->imported++;
            }

            if ($timer->limitExceeded()) {
                break;
            }
        }

        return $state->resumeIfNeeded();
    }

    // Comments

    /**
     * @param array $comments
     * @param array $map
     * @return void
     */
    protected function lookupCommentContents(array $comments, array & $map)
    {
    }

    /**
     * @param array $comment
     * @param array $map
     * @return int|null
     */
    protected function getContentIdFromComment(array $comment, array $map)
    {
        return null;
    }

    /**
     * @param array $comment
     * @param array $map
     * @return int
     * @throws Exception
     */
    protected function importComment(array $comment, array $map)
    {
        if ($comment['content_type'] === 'event') {
            $contentId = isset($map['tl_group_event'][$comment['content_id']])
                ? $map['tl_group_event'][$comment['content_id']]
                : 0;
        } elseif ($comment['content_type'] === 'post') {
            $contentId = isset($map['tl_group_post'][$comment['content_id']])
                ? $map['tl_group_post'][$comment['content_id']]
                : 0;
        } else {
            $contentId = $this->getContentIdFromComment($comment, $map);
        }

        if (!$contentId) {
            return 0;
        }

        $keys = [
            'user_id',
            'username',
            'comment_date',
            'message',
            'content_type'
        ];
        if (array_key_exists('likes', $comment)) {
            $keys['likes'] = 'reaction_score';

            $comment['reactions'] = [1 => $comment['likes']];
            $keys[] = 'reactions';
        }
        if (array_key_exists('like_users', $comment)) {
            if (!is_array($comment['like_users'])) {
                $comment['like_users'] = (array) Php::safeUnserialize($comment['like_users']);
            }
            $keys['like_users'] = 'reaction_users';
        }

        $data = $this->mapKeys($comment, $keys);
        $data['content_id'] = $contentId;

        /** @var Comment $handler */
        $handler = $this->newHandler('Truonglv\Groups:Comment');
        $handler->bulkSet($data);

        $oldId = isset($comment['comment_id']) ? $comment['comment_id'] : false;

        return (int) $handler->save($oldId);
    }

    /**
     * @return int
     */
    public function getStepEndForums()
    {
        return $this->sourceDb->fetchOne('SELECT MAX(node_id) FROM xf_forum');
    }

    /**
     * @param StepState $state
     * @param array $stepConfig
     * @param mixed $maxTime
     * @return StepState
     */
    public function stepForums(StepState $state, array $stepConfig, $maxTime)
    {
        $limit = 500;
        $timer = new Timer($maxTime);

        $forums = $this->sourceDb->fetchAll("
            SELECT node_id, team_id
            FROM xf_forum
            WHERE node_id > ?
            ORDER BY node_id
            LIMIT {$limit}
        ", [
            $state->startAfter
        ]);

        if (!$forums) {
            return $state->complete();
        }

        $teamIdsMap = $this->lookup('tl_group', array_column($forums, 'team_id'));

        foreach ($forums as $forum) {
            if ($timer->limitExceeded()) {
                break;
            }

            $nodeId = $forum['node_id'];
            $state->startAfter = $nodeId;

            if ($forum['team_id'] <= 0) {
                continue;
            }

            if (!isset($teamIdsMap[$forum['team_id']])) {
                continue;
            }
            $newGroupId = $teamIdsMap[$forum['team_id']];

            try {
                $this->db()->query('
                    INSERT IGNORE INTO xf_tl_group_forum
                        (group_id, node_id)
                    VALUES
                        (?, ?)
                ', [
                    $newGroupId, $nodeId
                ]);
            } catch (\XF\Db\Exception $e) {
            }

            $this->db()->update(
                'xf_node',
                ['node_type_id' => App::NODE_TYPE_ID],
                'node_type_id = ?',
                ['nobita_Teams_Forum']
            );

            $state->imported++;
        }

        return $state->resumeIfNeeded();
    }

    /**
     * @param StepState $state
     * @return StepState
     * @throws Exception
     */
    public function stepFields(StepState $state)
    {
        $fields = $this->sourceDb->fetchAll("
			SELECT field.*,
				ptitle.phrase_text AS title,
				pdesc.phrase_text AS description
			FROM xf_team_field AS field
			INNER JOIN xf_phrase AS ptitle ON
				(ptitle.language_id = 0 AND ptitle.title = CONCAT('Teams_team_field_', CAST(field.field_id AS CHAR(100))))
			INNER JOIN xf_phrase AS pdesc ON
				(pdesc.language_id = 0 AND pdesc.title = CONCAT('Teams_team_field_', CAST(field.field_id AS CHAR(100)), '_desc'))
		");

        if (!$fields) {
            return $state->complete();
        }

        $existingFields = $this->db()->fetchPairs('SELECT field_id, field_id FROM xf_tl_group_field');

        foreach ($fields as $field) {
            $oldId = $field['field_id'];

            if (isset($existingFields[$oldId])) {
                $this->logHandler('Truonglv\Groups:Field', $oldId, $oldId);
            } else {
                $field['match_params'] = [];

                if ($field['display_group'] === 'parent_tab') {
                    $field['display_group'] = 'new_tab';
                }

                $import = $this->setupCustomFieldImport('Truonglv\Groups:Field', $field);
                $newId = $import->save($oldId);

                if ($newId > 0) {
                    // migrate category
                    $categoryIds = $this->sourceDb->fetchAllColumn('
                        SELECT team_category_id
                        FROM xf_team_field_category
                        WHERE field_id = ?
                    ', $oldId);

                    if ($categoryIds) {
                        $newCategoryIds = $this->lookup('tl_group_category', $categoryIds);
                        foreach ($categoryIds as $categoryId) {
                            if (!isset($newCategoryIds[$categoryId])) {
                                continue;
                            }

                            $this->db()->insert('xf_tl_group_category_field', [
                                'field_id' => $newId,
                                'category_id' => $newCategoryIds[$categoryId]
                            ]);
                        }
                    }

                    // migrate group field value.
                    $fieldValues = $this->sourceDb->fetchAll('
                        SELECT *
                        FROM xf_team_field_value
                        WHERE field_id = ?
                    ', $oldId);

                    if ($fieldValues) {
                        $newGroupIds = $this->lookup('tl_group', array_column($fieldValues, 'team_id'));
                        foreach ($fieldValues as $fieldValue) {
                            if (!isset($newGroupIds[$fieldValue['team_id']])) {
                                continue;
                            }

                            $this->db()->insert('xf_tl_group_field_value', [
                                'group_id' => $newGroupIds[$fieldValue['team_id']],
                                'field_id' => $newId,
                                'field_value' => $fieldValue['field_value']
                            ]);
                        }
                    }
                }
            }

            $state->imported++;
        }

        return $state->complete();
    }

    /**
     * @return int
     */
    public function getStepEndPosts()
    {
        return $this->sourceDb->fetchOne('SELECT MAX(post_id) FROM xf_team_post');
    }

    /**
     * @param StepState $state
     * @param array $stepConfig
     * @param mixed $maxTime
     * @return StepState
     * @throws Exception
     */
    public function stepPosts(StepState $state, array $stepConfig, $maxTime)
    {
        $limit = 500;

        $timer = new Timer($maxTime);
        $posts = $this->sourceDb->fetchAll("
            SELECT *
            FROM xf_team_post
            WHERE post_id > ? AND post_id <= ?
            ORDER BY post_id
            LIMIT {$limit}
        ", [$state->startAfter, $state->end]);

        if (!$posts) {
            return $state->complete();
        }

        $groupIdsMap = $this->lookup('tl_group', array_column($posts, 'team_id'));

        foreach ($posts as $post) {
            $oldPostId = $post['post_id'];
            $state->startAfter = $post['post_id'];

            $oldGroupId = $post['team_id'];
            if (!isset($groupIdsMap[$oldGroupId])) {
                continue;
            }
            $newGroupId = $groupIdsMap[$oldGroupId];

            $data = $this->mapKeys($post, [
                'username',
                'user_id',
                'team_id' => 'group_id',
                'post_date',
                'comment_count'
            ]);

            $data['group_id'] = $newGroupId;

            /** @var \Truonglv\Groups\Import\Data\Post $handler */
            $handler = $this->newHandler('Truonglv\Groups:Post');
            $handler->bulkSet($data);

            $newPostId = $handler->save($post['post_id']);
            if ($newPostId > 0) {
                $firstComment = [
                    'user_id' => $post['user_id'],
                    'username' => $post['username'],
                    'comment_date' => $post['post_date'],
                    'message' => $post['message'],
                    'content_type' => 'post',
                    'content_id' => $oldPostId
                ];

                $map = [
                    'tl_group_post' => [
                        $oldPostId => $newPostId
                    ]
                ];
                $newFirstCommentId = $this->importComment($firstComment, $map);
                if ($newFirstCommentId <= 0) {
                    continue;
                }

                try {
                    $this->db()->update(
                        'xf_reaction_content',
                        [
                            'content_type' => App::CONTENT_TYPE_COMMENT,
                            'content_id' => $newFirstCommentId
                        ],
                        'content_type = ? AND content_id = ?',
                        ['team_post', $oldPostId]
                    );
                } catch (\XF\Db\Exception $e) {
                }

                try {
                    $this->db()->update(
                        'xf_attachment',
                        [
                            'content_type' => App::CONTENT_TYPE_COMMENT,
                            'content_id' => $newFirstCommentId
                        ],
                        'content_type = ? AND content_id = ?',
                        ['team_post', $oldPostId]
                    );
                } catch (\XF\Db\Exception $e) {
                }


                $this->db()->update(
                    'xf_tl_group_post',
                    ['first_comment_id' => $newFirstCommentId],
                    'post_id = ?',
                    [$newPostId]
                );

                $comments = $this->sourceDb->fetchAll('
                    SELECT *
                    FROM xf_team_comment
                    WHERE content_type = ? AND content_id = ?
                    ORDER BY comment_date
                ', ['post', $oldPostId]);
                if (!$comments) {
                    continue;
                }

                $state->imported++;
                foreach ($comments as $comment) {
                    $this->importComment($comment, $map);
                }
            }

            if ($timer->limitExceeded()) {
                break;
            }
        }

        return $state->resumeIfNeeded();
    }
}

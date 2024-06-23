<?php

namespace FS\ForumGroups\XF\Entity;

use XF\Mvc\Entity\Structure;
use FS\ForumGroups\Service\ForumGroups\Cover;
use InvalidArgumentException;

class Node extends XFCP_Node
{
    public static function getStructure(Structure $structure)
    {
        $structure = parent::getStructure($structure);

        $structure->columns['user_id'] =  ['type' => self::UINT, 'default' => 0];
        $structure->columns['room_path'] =  ['type' => self::STR, 'default' => null];
        $structure->columns['avatar_attachment_id'] =  ['type' => self::UINT, 'default' => 0];
        $structure->columns['cover_attachment_id'] =  ['type' => self::UINT, 'default' => 0];
        $structure->columns['cover_crop_data'] =  ['type' => self::JSON_ARRAY, 'default' => []];
        $structure->columns['created_at'] =  ['type' => self::UINT, 'default' => \XF::$time];
        $structure->columns['node_state'] =  ['type' => self::STR, 'default' => 'moderated', 'allowedValues' => ['visible', 'moderated', 'deleted']];

        $structure->relations += [
            'AvatarAttachment' => [
                'type' => self::TO_ONE,
                'entity' => 'XF:Attachment',
                'conditions' => [
                    ['attachment_id', '=', '$avatar_attachment_id']
                ],
                'primary' => true,
                'with' => ['Data']
            ],
            'CoverAttachment' => [
                'type' => self::TO_ONE,
                'entity' => 'XF:Attachment',
                'conditions' => [
                    ['attachment_id', '=', '$cover_attachment_id']
                ],
                'primary' => true,
                'with' => ['Data']
            ],
            'User' => [
                'entity' => 'XF:User',
                'type' => self::TO_ONE,
                'conditions' => 'user_id',
            ],
            'Forum' => [
                'entity' => 'XF:Forum',
                'type' => self::TO_ONE,
                'conditions' => 'node_id',
                'primary' => true
            ],
            'ApprovalQueue' => [
                'entity' => 'XF:ApprovalQueue',
                'type' => self::TO_ONE,
                'conditions' => [
                    ['content_type', '=', 'node'],
                    ['content_id', '=', '$node_id']
                ],
                'primary' => true
            ],
        ];

        return $structure;
    }

    /**
     * @param bool $canonical
     * @return string|null
     */
    public function getAvatarUrl($canonical = false)
    {
        /** @var Attachment|null $attachment */
        $attachment = $this->AvatarAttachment;
        if ($attachment === null) {
            return null;
        }

        return $this
            ->app()
            ->router('public')
            ->buildLink(($canonical ? 'canonical:' : '') . 'attachments', $attachment);
    }

    /**
     * @param null|string $key
     * @param mixed $default
     * @return array|mixed|null
     */
    public function getCoverCropData($key = null, $default = null)
    {
        if ($key !== null && strlen($key) > 0) {
            return \array_key_exists($key, $this->cover_crop_data) ? $this->cover_crop_data[$key] : $default;
        }

        return array_replace(Cover::getDefaultCropData(), $this->cover_crop_data);
    }

    /**
     * @param bool $canonical
     * @return string|null
     */
    public function getCoverUrl($canonical = false)
    {
        /** @var Attachment|null $attachment */
        $attachment = $this->CoverAttachment;
        if ($attachment === null) {
            return null;
        }

        return $this
            ->app()
            ->router('public')
            ->buildLink(($canonical ? 'canonical:' : '') . 'attachments', $attachment);
    }

    public function getImageAttributes()
    {
        $imgAttrs = [
            'data-debug=' . (\XF::$debugMode ? 1 : 0)
        ];

        if ($this->CoverAttachment !== null) {
            $imgAttrs[] = 'data-width=' . $this->CoverAttachment->width;
            $imgAttrs[] = 'data-height=' . $this->CoverAttachment->height;
            $imgAttrs[] = 'alt=' . htmlspecialchars($this->title);
            $top = $this->getCoverCropData('y', 0);

            $imgAttrs[] = 'style="top:' . $top;
            $coverUrl = $this->getCoverUrl(true) !== null
                ? htmlspecialchars($this->getCoverUrl(true))
                : '';
            if ($coverUrl === '') {
                throw new InvalidArgumentException('Group did not have cover!');
            }
            $imgAttrs[] = 'src=' . $coverUrl;
            $imgAttrs[] = 'data-src=' . $coverUrl;
        }


        return implode(' ', $imgAttrs);
    }

    public function getViewCounts()
    {
        $db = \XF::db();

        $res =  $db->fetchAll(
            "SELECT SUM(`view_count`) AS total_sum
            FROM xf_thread
            WHERE node_id  = ?
		",
            [
                $this->node_id,
            ]
        );

        return intval($res['0']['total_sum']);
    }

    public function getRandomColor()
    {
        return sprintf('%06X', mt_rand(0, 0xFFFFFF));
        // mt_srand((float)microtime() * 1000000);
        // $c = '';
        // while (strlen($c) < 6) {
        //     $c .= sprintf("%02X", mt_rand(0, 255));
        // }
        // return $c;
    }

    protected function _postSave()
    {
        $approvalChange = $this->isStateChanged('node_state', 'moderated');
        if ($this->isUpdate()) {
            if ($approvalChange == 'leave' && $this->ApprovalQueue) {
                $this->ApprovalQueue->delete();
            }
        }

        if ($approvalChange == 'enter') {
            if (!$this->app()->options()->fs_forum_groups_approval) {
                /** @var \XF\Entity\ApprovalQueue $approvalQueue */
                $approvalQueue = $this->getRelationOrDefault('ApprovalQueue', false);
                $approvalQueue->content_date = \XF::$time;
                $approvalQueue->save();
            }
        }
    }
    // public function canView( )
    // {
    //     return true;
    // }

    public function canApproveUnapprove(&$error = null): bool
    {
        return true;
    }
}

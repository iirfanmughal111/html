<?php

namespace Truonglv\Groups\Entity;

use XF;
use XF\Util\File;
use function time;
use Truonglv\Groups\App;
use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;
use XF\Repository\Attachment;

/**
 * COLUMNS
 * @property int|null $resource_id
 * @property string $title
 * @property int $attach_count
 * @property int $resource_date
 * @property int $download_count
 * @property int $view_count
 * @property int $icon_date
 * @property string $icon_url
 * @property int $group_id
 * @property int $user_id
 * @property string $username
 * @property int $first_comment_id
 * @property array $latest_comment_ids
 * @property int $last_comment_date
 * @property int $comment_count
 *
 * GETTERS
 * @property ArrayCollection|null $LatestComments
 * @property string $display_icon_url
 *
 * RELATIONS
 * @property \XF\Entity\User $User
 * @property \Truonglv\Groups\Entity\Group $Group
 * @property \Truonglv\Groups\Entity\Comment $FirstComment
 */
class ResourceItem extends Entity
{
    use CommentableTrait;

    /**
     * @param mixed $error
     * @return bool
     */
    public function canView(& $error = null)
    {
        if ($this->Group === null) {
            return false;
        }

        if (!$this->Group->canViewResources($error)) {
            return false;
        }

        return $this->Group->canViewContent($error);
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canEdit(& $error = null)
    {
        $visitor = XF::visitor();
        if ($visitor->user_id <= 0) {
            return false;
        }

        if ($visitor->user_id === $this->user_id) {
            return true;
        }

        $member = $this->getMember();

        return $member !== null && $member->hasRole(App::MEMBER_ROLE_PERM_KEY_RESOURCE, 'editResourceAny');
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canDelete(& $error = null)
    {
        $visitor = XF::visitor();
        if ($visitor->user_id <= 0) {
            return false;
        }

        if ($visitor->user_id === $this->user_id) {
            return true;
        }

        $member = $this->getMember();

        return $member !== null && $member->hasRole(App::MEMBER_ROLE_PERM_KEY_RESOURCE, 'deleteResourceAny');
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canDownload(& $error = null)
    {
        $visitor = XF::visitor();
        if ($visitor->user_id <= 0) {
            return false;
        }

        if (!$this->isDownloadable()) {
            return false;
        }

        $member = $this->getMember();

        return $member !== null && $member->isValidMember();
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canViewDownloadLogs(& $error = null)
    {
        $visitor = XF::visitor();
        if ($visitor->user_id <= 0) {
            return false;
        }

        if ($visitor->user_id === $this->user_id) {
            return true;
        }

        $member = $this->getMember();

        return $member !== null && $member->isValidMember();
    }

    /**
     * @return Member|null
     */
    public function getMember()
    {
        if ($this->Group === null) {
            return null;
        }

        return $this->Group->Member;
    }

    /**
     * @return bool
     */
    public function isDownloadable()
    {
        return $this->attach_count > 0;
    }

    /**
     * @return bool
     */
    public function isIgnored()
    {
        return XF::visitor()->isIgnoring($this->user_id);
    }

    public function getAbstractedIconPath(): string
    {
        return sprintf(
            'data://groups/resource_icons/%d/%d.jpg',
            floor($this->resource_id / 1000),
            $this->resource_id
        );
    }

    /**
     * @param bool $canonical
     * @return string
     */
    public function getDisplayIconUrl($canonical = false): string
    {
        if (strlen($this->icon_url) > 0) {
            return $this->icon_url;
        }

        if ($this->icon_date > 0) {
            $group = floor($this->resource_id / 1000);

            return $this->app()->applyExternalDataUrl(
                "groups/resource_icons/{$group}/{$this->resource_id}.jpg?{$this->icon_date}",
                $canonical
            );
        }

        return '';
    }

    public static function getStructure(Structure $structure)
    {
        $structure->table = 'xf_tl_group_resource';
        $structure->primaryKey = 'resource_id';
        $structure->shortName = 'Truonglv\Groups:ResourceItem';
        $structure->contentType = App::CONTENT_TYPE_RESOURCE;

        $structure->columns = [
            'resource_id' => ['type' => self::UINT, 'nullable' => true, 'autoIncrement' => true],
            'title' => ['type' => self::STR, 'required' => true, 'maxLength' => 100],
            'attach_count' => ['type' => self::UINT, 'forced' => 0, 'default' => 0],
            'resource_date' => ['type' => self::UINT, 'default' => time()],
            'download_count' => ['type' => self::UINT, 'forced' => true, 'default' => 0],
            'view_count' => ['type' => self::UINT, 'forced' => true, 'default' => 0],
            'icon_date' => ['type' => self::UINT, 'default' => 0],
            'icon_url' => ['type' => self::STR, 'maxLength' => 255, 'default' => ''],
        ];

        static::addCommentStructureElements($structure);

        $structure->getters += [
            'display_icon_url' => true,
        ];

        $structure->withAliases = [
            'fullList' => [
                'User',
            ],
            'fullView' => [
                'User',
                'User.Profile',
                'User.Privacy',
                'Group',
                'Group.full',
                'FirstComment',
            ]
        ];

        return $structure;
    }

    protected function _postDelete()
    {
        if ($this->attach_count > 0) {
            /** @var Attachment $attachmentRepo */
            $attachmentRepo = $this->repository('XF:Attachment');
            $attachmentRepo->fastDeleteContentAttachments(
                App::CONTENT_TYPE_RESOURCE,
                $this->resource_id
            );
        }

        if ($this->icon_date > 0) {
            File::deleteFromAbstractedPath($this->getAbstractedIconPath());
        }
    }

    /**
     * @return string
     */
    public function getCommentContentType()
    {
        return 'resource';
    }
}

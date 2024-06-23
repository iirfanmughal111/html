<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Entity;

use XF;
use function sprintf;
use XF\Entity\Phrase;
use function in_array;
use Truonglv\Groups\App;
use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;
use InvalidArgumentException;
use Truonglv\Groups\MemberRole\AbstractMemberRole;

/**
 * COLUMNS
 * @property string|null $member_role_id
 * @property array $role_permissions
 * @property int $display_order
 * @property array $user_group_ids
 *
 * GETTERS
 * @property \XF\Phrase|null $title
 * @property \XF\Phrase|null $description
 * @property bool $is_staff
 *
 * RELATIONS
 * @property \XF\Entity\Phrase $MasterTitle
 * @property \XF\Entity\Phrase $MasterDescription
 */
class MemberRole extends Entity
{
    const PHRASE_PREFIX_ID = 'tlg_member_role_id';

    /**
     * @param bool $title
     * @return string
     */
    public function getPhraseName($title)
    {
        // tl_groups.member_role_id_*
        return sprintf(
            '%s_%s_%s',
            self::PHRASE_PREFIX_ID,
            $title ? 'title' : 'desc',
            $this->member_role_id
        );
    }

    /**
     * @return \XF\Phrase|null
     */
    public function getTitle()
    {
        // XF::phrase('tlg_member_role_id_*')
        /** @var Phrase|null $title */
        $title = $this->MasterTitle;
        // @phpstan-ignore-next-line
        return $title !== null ? XF::phrase($this->getPhraseName(true)) : null;
    }

    /**
     * @param string $group
     * @param string $name
     * @return bool
     * @throws InvalidArgumentException
     */
    public function hasRole($group, $name)
    {
        $handlers = App::memberRoleRepo()->getMemberRoleHandlers();
        if (!isset($handlers[$group])) {
            throw new InvalidArgumentException('Unknown member role handler for (' . $group . ')');
        }

        /** @var AbstractMemberRole $handler */
        $handler = $handlers[$group];
        $handler->setPermissions(isset($this->role_permissions[$group]) ? $this->role_permissions[$group] : []);

        return (bool) $handler->has($name);
    }

    /**
     * @param bool $title
     * @return Phrase|null
     */
    public function getMasterPhrase($title)
    {
        /** @var Phrase|null $phrase */
        $phrase = $title ? $this->MasterTitle : $this->MasterDescription;
        if ($phrase === null) {
            /** @var Phrase $phrase */
            $phrase = $this->_em->create('XF:Phrase');
            $phrase->title = $this->_getDeferredValue(function () use ($title) {
                return $this->getPhraseName($title);
            });
            $phrase->language_id = 0;
            $phrase->addon_id = '';
        }

        return $phrase;
    }

    /**
     * @return \XF\Phrase|null
     */
    public function getDescription()
    {
        /** @var Phrase|null $description */
        $description = $this->MasterDescription;

        // @phpstan-ignore-next-line
        return $description !== null ? XF::phrase($this->getPhraseName(false)) : null;
    }

    /**
     * @return bool
     */
    public function isStaff()
    {
        return in_array($this->member_role_id, [App::MEMBER_ROLE_ID_ADMIN, App::MEMBER_ROLE_ID_MODERATOR], true);
    }

    public static function getStructure(Structure $structure)
    {
        $structure->table = 'xf_tl_group_member_role';
        $structure->primaryKey = 'member_role_id';
        $structure->shortName = 'Truonglv\Groups:MemberRole';
        $structure->columns = [
            'member_role_id' => ['type' => self::STR, 'nullable' => true, 'maxLength' => 50, 'writeOnce' => true],
            'role_permissions' => ['type' => self::JSON_ARRAY, 'default' => []],
            'display_order' => ['type' => self::UINT, 'default' => 0],
            'user_group_ids' => ['type' => self::JSON_ARRAY, 'default' => []]
        ];

        $structure->getters = [
            'title' => true,
            'description' => true,
            'is_staff' => [
                'cache' => true,
                'getter' => 'isStaff'
            ],
        ];

        $structure->relations = [
            'MasterTitle' => [
                'entity' => 'XF:Phrase',
                'type' => self::TO_ONE,
                'conditions' => [
                    ['language_id', '=', 0],
                    ['title', '=', self::PHRASE_PREFIX_ID . '_title_', '$member_role_id']
                ]
            ],
            'MasterDescription' => [
                'entity' => 'XF:Phrase',
                'type' => self::TO_ONE,
                'conditions' => [
                    ['language_id', '=', 0],
                    ['title', '=', self::PHRASE_PREFIX_ID . '_desc_', '$member_role_id']
                ]
            ]
        ];

        return $structure;
    }

    protected function _preSave()
    {
        if ($this->isInsert()) {
            $memberRole = $this->em()->find('Truonglv\Groups:MemberRole', $this->member_role_id);
            if ($memberRole !== null) {
                $this->error(XF::phrase('tlg_member_role_has_been_taken'));
            }
        }
    }

    protected function _preDelete()
    {
        if ($this->member_role_id === App::MEMBER_ROLE_ID_ADMIN
            || $this->member_role_id === App::MEMBER_ROLE_ID_MEMBER
            || $this->member_role_id === App::MEMBER_ROLE_ID_MODERATOR
        ) {
            $this->error(XF::phrase('tlg_you_cannot_delete_reserve_member_roles'));
        }
    }

    protected function _postSave()
    {
        $this->memberRoleRepo()->rebuildCache();
    }

    protected function _postDelete()
    {
        /** @var Phrase|null $title */
        $title = $this->MasterTitle;
        if ($title !== null) {
            $title->delete();
        }

        /** @var Phrase|null $description */
        $description = $this->MasterDescription;
        if ($description !== null) {
            $description->delete();
        }

        $this->app()
            ->jobManager()
            ->enqueueUnique(
                'tlg_MRDel' . $this->member_role_id,
                'Truonglv\Groups:MemberRoleDelete',
                [
                    'memberRoleId' => $this->member_role_id,
                ]
            );

        $this->memberRoleRepo()->rebuildCache();
    }

    /**
     * @return \Truonglv\Groups\Repository\MemberRole
     */
    protected function memberRoleRepo()
    {
        return App::memberRoleRepo();
    }
}

<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\XFMG\Entity;

use Truonglv\Groups\App;
use XF\Mvc\Entity\Structure;
use Truonglv\Groups\Entity\Group;

/**
 * Class Album
 * @package Truonglv\Groups\XFMG\Entity
 * @inheritdoc
 *
 * @property \Truonglv\Groups\Entity\Album|null GroupAlbum
 */
class Album extends XFCP_Album
{
    /**
     * @var Group|null
     */
    protected $tlgGroup;

    /**
     * @param mixed $error
     * @return bool
     */
    public function canView(& $error = null)
    {
        if (App::isEnabledXenMediaAddOn()
            && $this->album_state === 'visible'
        ) {
            $group = App::getGroupEntityFromEntity($this);
            if ($group !== null) {
                // this album was linked to group
                // we will take over permissions and depend on group privacy
                if (!$group->canViewContent($error)) {
                    return false;
                }

                return true;
            }
        }

        return parent::canView($error);
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canEdit(& $error = null)
    {
        if ($this->album_state === 'visible'
            && App::isEnabledXenMediaAddOn()
        ) {
            $group = App::getGroupEntityFromEntity($this);
            $member = $group === null ? null : $group->Member;
            if ($member !== null
                && $member->hasRole(App::MEMBER_ROLE_PERM_KEY_MEDIA, 'editAlbumAny')
            ) {
                return true;
            }
        }

        return parent::canEdit($error);
    }

    /**
     * @param mixed $type
     * @param mixed $error
     * @return bool
     */
    public function canDelete($type = 'soft', & $error = null)
    {
        if ($this->album_state === 'visible'
            && $type === 'soft'
            && App::isEnabledXenMediaAddOn()
        ) {
            $group = App::getGroupEntityFromEntity($this);
            $member = $group === null ? null : $group->Member;
            if ($member !== null
                && $member->hasRole(App::MEMBER_ROLE_PERM_KEY_MEDIA, 'deleteAlbumAny')
            ) {
                return true;
            }
        }

        return parent::canDelete($type, $error);
    }

    public function canChangePrivacy(& $error = null)
    {
        $group = App::getGroupEntityFromEntity($this);
        if ($group !== null) {
            // this album was handled by group privacy
            // then do not allow anyone to change privacy
            return false;
        }

        return parent::canChangePrivacy($error);
    }

    /**
     * @return \Truonglv\Groups\Entity\Album
     */
    public function getNewGroupAlbum()
    {
        /** @var \Truonglv\Groups\Entity\Album $entity */
        $entity = $this->em()->create('Truonglv\Groups:Album');
        $entity->album_id = $this->_getDeferredValue(function () {
            return $this->album_id;
        }, 'save');

        $this->addCascadedSave($entity);

        return $entity;
    }

    /**
     * @return Group|null
     */
    public function getTlgGroup(): ?Group
    {
        return $this->tlgGroup;
    }

    public function setTLGGroup(Group $group): void
    {
        $this->tlgGroup = $group;
    }

    public function canAddMedia(& $error = null)
    {
        if ($this->tlgGroup !== null) {
            // creating album in group
            // we will overwrite permissions by group rules
            $member = $this->tlgGroup->Member;

            if ($member !== null && $member->hasRole(App::MEMBER_ROLE_PERM_KEY_MEDIA, 'createAlbum')) {
                return $this->canEmbedMedia($error) || $this->canUploadMedia($error);
            }

            return false;
        }

        return parent::canAddMedia($error);
    }

    public static function getStructure(Structure $structure)
    {
        $structure = parent::getStructure($structure);

        if (App::isEnabledXenMediaAddOn()) {
            $structure->relations['GroupAlbum'] = [
                'type' => self::TO_ONE,
                'entity' => 'Truonglv\Groups:Album',
                'conditions' => 'album_id',
                'primary' => true
            ];

            $structure->behaviors['Truonglv\Groups:Activity'] = [
                'stateField' => 'album_state',
                'groupIdField' => function (\XFMG\Entity\Album $album) {
                    /** @var \Truonglv\Groups\Entity\Album|null $groupAlbum */
                    $groupAlbum = $album->getRelation('GroupAlbum');

                    return $groupAlbum !== null ? $groupAlbum->group_id : 0;
                }
            ];
        }

        return $structure;
    }
}

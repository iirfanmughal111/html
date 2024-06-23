<?php

namespace Truonglv\Groups\Service\Album;

use XF;
use LogicException;
use XF\PrintableException;
use XF\Service\AbstractService;
use Truonglv\Groups\Entity\Album;
use Truonglv\Groups\Entity\Group;

class Importer extends AbstractService
{
    /**
     * @var Group
     */
    protected $group;

    /**
     * @var array
     */
    protected $albumIds = [];

    public function __construct(\XF\App $app, Group $group)
    {
        parent::__construct($app);

        $this->group = $group;
    }

    public function setAlbumIds(array $albumIds): self
    {
        $this->albumIds = array_unique($albumIds);

        return $this;
    }

    public function save(): void
    {
        if (count($this->albumIds) === 0) {
            throw new LogicException('Must be set album IDs');
        }

        $albums = $this->em()->findByIds('XFMG:Album', $this->albumIds);
        $linked = $this->em()->findByIds('Truonglv\Groups:Album', $this->albumIds, ['Album', 'Group']);

        $db = $this->db();
        $db->beginTransaction();

        foreach ($this->albumIds as $albumId) {
            if (!isset($albums[$albumId])) {
                continue;
            }

            /** @var \Truonglv\Groups\XFMG\Entity\Album $albumRef */
            $albumRef = $albums[$albumId];
            if (!$albumRef->canView($error)) {
                // @phpstan-ignore-next-line
                throw new PrintableException($error ?: XF::phrase('xfmg_requested_album_not_found'));
            }

            if ($albumRef->view_privacy !== 'public') {
                throw new PrintableException(XF::phrase('tlg_group_album_x_must_be_public_view_privacy', [
                    'title' => $albumRef->title,
                ]));
            }

            if (isset($linked[$albumId])) {
                /** @var Album $groupAlbum */
                $groupAlbum = $linked[$albumId];

                if ($this->group->group_id !== $groupAlbum->group_id) {
                    throw new PrintableException(XF::phrase('tlg_album_x_has_been_linked_to_other_group_y', [
                        'title' => $groupAlbum->Album->title ?? '',
                        'name' => $groupAlbum->Group->name ?? '',
                    ]));
                }

                continue;
            }

            try {
                $db->insert('xf_tl_group_mg_album', [
                    'group_id' => $this->group->group_id,
                    'album_id' => $albumId
                ], true, 'group_id = VALUES(group_id)');
            } catch (\XF\Db\DuplicateKeyException $e) {
            }
        }

        $db->commit();
    }
}

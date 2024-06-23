<?php

namespace FS\PGPEncryption\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

class User extends XFCP_User {

    public static function getStructure(Structure $structure) {

        $structure = parent::getStructure($structure);

        $structure->columns['public_key'] = ['type' => self::STR, 'default' => null];

        $structure->columns['encrypt_message'] = ['type' => self::STR, 'default' => null];

        $structure->columns['random_message'] = ['type' => self::STR, 'default' => null];

        $structure->columns['pgp_option'] = ['type' => self::INT, 'default' => 1];

        $structure->columns['passphrase_option'] = ['type' => self::INT, 'default' => 1];

        $structure->columns['passphrase_1'] = ['type' => self::STR, 'default' => null];

        $structure->columns['passphrase_2'] = ['type' => self::STR, 'default' => null];
        $structure->columns['passphrase_3'] = ['type' => self::STR, 'default' => null];

        $structure->columns['verify_pgp'] = ['type' => self::INT, 'default' => null];

        $structure->columns['pgp_admin'] = ['type' => self::INT, 'default' => 0];

        return $structure;
    }

}

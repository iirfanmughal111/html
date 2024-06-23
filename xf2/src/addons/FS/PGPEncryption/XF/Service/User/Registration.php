<?php

namespace FS\PGPEncryption\XF\Service\User;

class Registration extends XFCP_Registration {

    protected $fieldMap = [
        'username' => 'username',
        'email' => 'email',
        'timezone' => 'timezone',
        'location' => 'Profile.location',
        'public_key' => 'public_key',
        'encrypt_message' => 'encrypt_message',
        'random_message' => 'random_message',
        'passphrase_1' => 'passphrase_1',
        'passphrase_2' => 'passphrase_2',
        'passphrase_3' => 'passphrase_3',
    ];

}

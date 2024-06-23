<?php

namespace XFMG\XF\Service\User;

use XF\Entity\User;

class SignatureEdit extends XFCP_SignatureEdit
{
	public function __construct(\XF\App $app, User $user)
	{
		parent::__construct($app, $user);
		$this->permTagMap['media'][] = 'gallery';
	}
}
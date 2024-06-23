<?php

namespace FS\BunnyIntegration\XF\Entity;

use XF\Mvc\Entity\Structure;

class AttachmentData extends XFCP_AttachmentData
{
	public function getPublicUrlBunnyPath(bool $canonical = false)
	{
		$path = $this->file_path;
		if (!strlen($path)) {
			return null;
		}

		$placeholders = [
			'%INTERNAL%' => 'internal-data://', // for legacy
			'%DATA%' => 'data://', // for legacy
		];
		$path = strtr($path, $placeholders);
		if (substr($path, 0, 7) !== 'data://') {
			return null;
		}

		$path = $this->_getAbstractedDataPath(
			$this->data_id,
			substr($path, 7),
			$this->file_hash
		);

		return $path;
	}
}

<?php

namespace XenAddons\Showcase\XF\Service\Message;

class Preparer extends XFCP_Preparer
{
	protected $showcaseEmbeds = [];

	public function prepare($message, $checkValidity = true)
	{
		$message = parent::prepare($message, $checkValidity);

		/** @var \XenAddons\Showcase\XF\BbCode\ProcessorAction\AnalyzeUsage $usage */
		$usage = $this->bbCodeProcessor->getAnalyzer('usage');
		$this->showcaseEmbeds = $usage->getShowcaseEmbeds();

		return $message;
	}

	public function getEmbeddedShowcaseItems()  
	{
		return $this->showcaseEmbeds;
	}

	public function getEmbedMetadata()
	{
		$metadata = parent::getEmbedMetadata();
		if ($this->showcaseEmbeds)
		{
			$metadata['showcaseEmbeds'] = $this->showcaseEmbeds;
		}

		return $metadata;
	}

	public function checkValidity($message)
	{
		$isValid = parent::checkValidity($message);

		/** @var \XF\BbCode\ProcessorAction\AnalyzeUsage $usage */
		$usage = $this->bbCodeProcessor->getAnalyzer('usage');

		if ($this->isValid)
		{
			$maxImages = $this->constraints['maxImages'];
			if ($maxImages && $usage->getTagCount('img') + $usage->getTagCount('showcase') > $maxImages)
			{
				$this->errors[] = \XF::phraseDeferred(
					'please_enter_message_with_no_more_than_x_images',
					['count' => $maxImages]
				);
				$this->isValid = false;
			}
		}

		return $this->isValid;
	}
}
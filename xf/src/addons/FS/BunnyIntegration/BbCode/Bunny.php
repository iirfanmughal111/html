<?php

namespace FS\BunnyIntegration\BbCode;

class Bunny
{

	public static function renderTagBunny($tagChildren, $tagOption, $tag, array $options, \XF\BbCode\Renderer\AbstractRenderer $renderer)
	{
		// $explodeTagOptions = explode(',', $tagOption);

		if (!$tag['option']) {
			return '';
		}

		if (!$tagChildren) {
			return self::renderDefaultImage();
		}

		$viewParams = [

			'libraryId' => $tagOption,
			'videoId' => $tagChildren ? $tagChildren[0] : '',
		];

		return $renderer->getTemplater()->renderTemplate('public:fs_buunyBBcodeRender', $viewParams);
	}

	protected static function renderDefaultImage(): string
	{
		$baseUrl = \XF::options()->boardUrl;

		$imageUrl =  $baseUrl . '/styles/FS/BunnyIntegration/uploading-image.gif';

		$imageHtml =  '<img src="' . $imageUrl . '" height=315 width=560/>';

		// $imageHtml .= '</div>';

		return $imageHtml;
	}
}

<?php

namespace DC\LinkProxy\XF\Template;

class Templater extends XFCP_Templater
{
    public function renderUnfurl(\XF\Entity\UnfurlResult $result, array $options = [])
    {
        $options = array_replace([
			'noFollow' => false,
			'noProxy' => false,
			'simpleUnfurl' => false
		], $options);

		$formatter = $this->app->stringFormatter();

		$linkInfo = $formatter->getLinkClassTarget($result->url);
		$rels = [];

		if (!$linkInfo['trusted'] && $options['noFollow'])
		{
			$rels[] = 'nofollow';
		}

		if ($linkInfo['target'])
		{
			$rels[] = 'noopener';
		}

		$proxyUrl = '';
		$imageUrl = $result->image_url;
		$iconUrl = $result->favicon_url;

		if (!$options['noProxy'])
		{
			$proxyUrl = htmlspecialchars($formatter->getProxiedUrlIfActive('link', $result->url));

			if ($imageUrl)
			{
				$linkInfo = $formatter->getLinkClassTarget($imageUrl);
				if (!$linkInfo['local'])
				{
					$imageUrl = $formatter->getProxiedUrlIfActive('image', $imageUrl);
					if (!$imageUrl)
					{
						$imageUrl = $result->image_url;
					}
				}
			}

			if ($iconUrl)
			{
				$linkInfo = $formatter->getLinkClassTarget($iconUrl);
				if (!$linkInfo['local'])
				{
					$iconUrl = $formatter->getProxiedUrlIfActive('image', $iconUrl);
					if (!$iconUrl)
					{
						$iconUrl = $result->favicon_url;
					}
				}
			}
        }
        
        if ($linkInfo['type'] != 'internal')
        {
			$url = $result->url;
			$domain = parse_url($url, PHP_URL_HOST);

			$options = \XF::options();

			$boardUrl = $options->boardUrl;

			/** Check white listed domains */
			$domainWhiteListed = $options->DC_LinkProxy_DomainWhiteList;
			$domainWhiteListedArray = explode("\n", $domainWhiteListed);

			if (\XF::options()->useFriendlyUrls)
			{
				$urlEncoded = $boardUrl . '/redirect?to=' . base64_encode(htmlspecialchars($result->url));
			}
			else
			{
				$urlEncoded = $boardUrl . '?redirect&to=' . base64_encode(htmlspecialchars($result->url));
			}

			foreach ($domainWhiteListedArray as &$value) {
				if ( $domain == $value ) {
					$urlEncoded = $result->url;
				}
			}
        } else {
            $urlEncoded = $result->url;
        }

		$viewParams = [
			'linkInfo' => $linkInfo,
			'rels' => $rels,
			'proxyUrl' => $proxyUrl,
			'result' => $result,
			'imageUrl' => $imageUrl,
			'faviconUrl' => $iconUrl,
            'urlEncoded' => $urlEncoded
        ];

		return $this->renderTemplate('public:bb_code_tag_url_unfurl', $viewParams);
    }
}
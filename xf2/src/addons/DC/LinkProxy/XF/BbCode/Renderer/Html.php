<?php

namespace DC\LinkProxy\XF\BbCode\Renderer;

class Html extends XFCP_Html
{
    protected function getRenderedLink($text, $url, array $options)
	{
		$linkInfo = $this->formatter->getLinkClassTarget($url);
		$rels = [];

		$classAttr = $linkInfo['class'] ? " class=\"$linkInfo[class]\"" : '';
		$targetAttr = $linkInfo['target'] ? " target=\"$linkInfo[target]\"" : '';

		if (!$linkInfo['trusted'] && !empty($options['noFollowUrl']))
		{
			$rels[] = 'nofollow';
		}

		if ($linkInfo['target'])
		{
			$rels[] = 'noopener';
		}

		$proxyAttr = '';
		if (empty($options['noProxy']))
		{
			$proxyUrl = $this->formatter->getProxiedUrlIfActive('link', $url);
			if ($proxyUrl)
			{
				$proxyAttr = ' data-proxy-href="' . htmlspecialchars($proxyUrl) . '"';
			}
		}

		if ($rels)
		{
			$relAttr = ' rel="' . implode(' ', $rels) . '"';
		}
		else
		{
			$relAttr = '';
        }
        
        $link = $this->formatter->getLinkClassTarget($url);

        if ($link['type'] != 'internal')
        {
			$options = \XF::options();

			$boardUrl = $options->boardUrl;
			
			/** Check white listed domains */
			$domainWhiteListed = $options->DC_LinkProxy_DomainWhiteList;
			$domainWhiteListedArray = explode("\n", $domainWhiteListed);
			$domain = parse_url($url, PHP_URL_HOST);

            if (\XF::options()->useFriendlyUrls)
            {
                $urlEncoded = $boardUrl . '/redirect?to=' . base64_encode(htmlspecialchars($url));
            }
            else
            {
                $urlEncoded = $boardUrl . '?redirect&to=' . base64_encode(htmlspecialchars($url));
            }

			foreach ($domainWhiteListedArray as &$value) {
				if ( $domain == $value )
				{
					$urlEncoded = $url;
				}
			}
        } else {
            $urlEncoded = htmlspecialchars($url);
        }

		return $this->wrapHtml(
			'<a href="' . $urlEncoded . '"' . $targetAttr . $classAttr . $proxyAttr . $relAttr . '>',
			$text,
			'</a>'
		);
	}
}
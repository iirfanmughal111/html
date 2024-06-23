<?php

namespace XenAddons\Showcase\XF\Pub\Controller;

use XF\Mvc\ParameterBag;

class GotoPage extends XFCP_GotoPage
{
	public function actionShowcaseComment(ParameterBag $params)
	{
		$params->offsetSet('comment_id', $this->filter('id', 'uint'));
		return $this->rerouteController('XenAddons\Showcase:Comment', 'index', $params);
	}
}
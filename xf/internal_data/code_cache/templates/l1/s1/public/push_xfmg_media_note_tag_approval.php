<?php
// FROM HASH: 71454008d64f039bf54f46965f9a5c49
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . ($__templater->escape($__vars['user']['username']) ?: $__templater->escape($__vars['alert']['username'])) . ' tagged you in media ' . $__templater->escape($__vars['content']['MediaItem']['title']) . '. Visit your account to approve this.' . '
<push:url>' . $__templater->func('link', array('canonical:account/alerts', ), true) . '</push:url>';
	return $__finalCompiled;
}
);
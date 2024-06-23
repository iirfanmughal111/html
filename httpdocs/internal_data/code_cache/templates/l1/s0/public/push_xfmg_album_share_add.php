<?php
// FROM HASH: ebaea784bdbc8da463a5cf24651d7b3f
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . ($__templater->escape($__vars['user']['username']) ?: $__templater->escape($__vars['alert']['username'])) . ' has shared their album ' . $__templater->escape($__vars['content']['title']) . ' with you and you are now able to add media to it.' . '
<push:url>' . $__templater->func('link', array('canonical:media/albums', $__vars['content'], ), true) . '</push:url>';
	return $__finalCompiled;
}
);
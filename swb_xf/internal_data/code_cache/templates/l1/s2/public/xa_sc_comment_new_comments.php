<?php
// FROM HASH: e4939dc072d024dd231d40038cb1c54f
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__vars['firstUnshownComment']) {
		$__finalCompiled .= '
	<div class="message">
		<div class="message-inner">
			<div class="message-cell message-cell--alert">
				' . 'There are more comments to display.' . ' <a href="' . $__templater->func('link', array('showcase/comments', $__vars['firstUnshownComment'], ), true) . '">' . 'View them?' . '</a>
			</div>
		</div>
	</div>
';
	}
	$__finalCompiled .= '

';
	if ($__templater->isTraversable($__vars['comments'])) {
		foreach ($__vars['comments'] AS $__vars['comment']) {
			$__finalCompiled .= '
	' . $__templater->callMacro('xa_sc_comment_macros', 'comment', array(
				'comment' => $__vars['comment'],
				'content' => $__vars['content'],
				'linkPrefix' => $__vars['linkPrefix'],
			), $__vars) . '
';
		}
	}
	return $__finalCompiled;
}
);
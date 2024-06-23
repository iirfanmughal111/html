<?php
// FROM HASH: 2747ec1d92fa44e73621d1da7b762d0b
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__vars['firstUnshownPost']) {
		$__finalCompiled .= '
    <div class="message">
        <div class="message-inner">
            <div class="message-cell message-cell--alert">
                ' . 'There are no comments to display.' . ' <a href="' . $__templater->func('link', array('group-comments', $__vars['firstUnshownPost'], ), true) . '">' . 'View them?' . '</a>
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
    ' . $__templater->callMacro('tlg_comment_macros', 'comment', array(
				'comment' => $__vars['comment'],
				'event' => $__vars['event'],
			), $__vars) . '
';
		}
	}
	return $__finalCompiled;
}
);
<?php
// FROM HASH: 5812c86c60d3a0cdba0cf2d74f336bfc
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if (!$__templater->test($__vars['comments'], 'empty', array())) {
		$__finalCompiled .= '
	<div class="block"' . $__templater->func('widget_data', array($__vars['widget'], ), true) . '>
		<div class="block-container">
			<h3 class="block-minorHeader">
				<a href="' . $__templater->escape($__vars['link']) . '" rel="nofollow">' . ($__templater->escape($__vars['title']) ?: 'Latest comments') . '</a>
			</h3>
			<ul class="block-body">
				';
		if ($__templater->isTraversable($__vars['comments'])) {
			foreach ($__vars['comments'] AS $__vars['comment']) {
				$__finalCompiled .= '
					<li class="block-row">
						' . $__templater->callMacro('xa_sc_comment_macros', 'comment_simple', array(
					'comment' => $__vars['comment'],
					'content' => $__vars['comment']['Content'],
				), $__vars) . '
					</li>
				';
			}
		}
		$__finalCompiled .= '
			</ul>
		</div>
	</div>
';
	}
	return $__finalCompiled;
}
);
<?php
// FROM HASH: dabef337b0073b41e9635ee651660152
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if (!$__templater->test($__vars['updates'], 'empty', array())) {
		$__finalCompiled .= '
	';
		$__templater->includeCss('xa_sc.less');
		$__finalCompiled .= '
	<div class="block"' . $__templater->func('widget_data', array($__vars['widget'], ), true) . '>

		<div class="block-container">
			';
		if ($__vars['style'] == 'full') {
			$__finalCompiled .= '
				<h3 class="block-header">
					<a href="' . $__templater->escape($__vars['link']) . '" rel="nofollow">' . ($__templater->escape($__vars['title']) ?: 'Latest updates') . '</a>
				</h3>
				<div class="block-body">
					';
			if ($__templater->isTraversable($__vars['updates'])) {
				foreach ($__vars['updates'] AS $__vars['update']) {
					$__finalCompiled .= '
						' . $__templater->callMacro('xa_sc_update_macros', 'update', array(
						'update' => $__vars['update'],
						'item' => $__vars['update']['Item'],
						'showItem' => true,
						'showAttachments' => true,
						'allowInlineModeration' => false,
					), $__vars) . '
					';
				}
			}
			$__finalCompiled .= '
				</div>
			';
		} else {
			$__finalCompiled .= '
				<h3 class="block-minorHeader">
					<a href="' . $__templater->escape($__vars['link']) . '" rel="nofollow">' . ($__templater->escape($__vars['title']) ?: 'Latest updates') . '</a>
				</h3>
				<ul class="block-body">
					';
			if ($__templater->isTraversable($__vars['updates'])) {
				foreach ($__vars['updates'] AS $__vars['update']) {
					$__finalCompiled .= '
						<li class="block-row">
							' . $__templater->callMacro('xa_sc_update_macros', 'update_simple', array(
						'update' => $__vars['update'],
					), $__vars) . '
						</li>
					';
				}
			}
			$__finalCompiled .= '
				</ul>
			';
		}
		$__finalCompiled .= '
		</div>
	</div>
';
	}
	return $__finalCompiled;
}
);
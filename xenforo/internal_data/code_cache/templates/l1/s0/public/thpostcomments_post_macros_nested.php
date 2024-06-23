<?php
// FROM HASH: e57c90a7912d3af7f3499903be397d88
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__vars['nestedPost']) {
		$__finalCompiled .= '			
	';
		if ($__vars['post']['thpostcomments_depth'] == 0) {
			$__finalCompiled .= '
		';
			$__compilerTemp1 = '';
			$__compilerTemp1 .= '
					';
			if (!$__templater->test($__vars['nestedPost']['children'], 'empty', array()) AND $__templater->func('is_array', array($__vars['nestedPost']['children'], ), false)) {
				$__compilerTemp1 .= '
						';
				if ($__templater->isTraversable($__vars['nestedPost']['children'])) {
					foreach ($__vars['nestedPost']['children'] AS $__vars['subPost']) {
						$__compilerTemp1 .= '
							';
						if ($__vars['subPost']['record']['message_state'] == 'deleted') {
							$__compilerTemp1 .= '
								' . $__templater->callMacro('post_macros', 'post_deleted', array(
								'nestedPost' => $__vars['subPost'],
								'post' => $__vars['subPost']['record'],
								'depth' => ($__vars['depth'] + 1),
								'thread' => $__vars['thread'],
							), $__vars) . '
							';
						} else {
							$__compilerTemp1 .= '
								' . $__templater->callMacro('post_macros', 'post', array(
								'nestedPost' => $__vars['subPost'],
								'post' => $__vars['subPost']['record'],
								'depth' => ($__vars['depth'] + 1),
								'thread' => $__vars['thread'],
							), $__vars) . '
							';
						}
						$__compilerTemp1 .= '
						';
					}
				}
				$__compilerTemp1 .= '
					';
			}
			$__compilerTemp1 .= '
				';
			if (strlen(trim($__compilerTemp1)) > 0) {
				$__finalCompiled .= '
			<div class="thpostcomments_commentsContainer">
				' . $__compilerTemp1 . '
			</div>
		';
			}
			$__finalCompiled .= '
	';
		} else {
			$__finalCompiled .= '
		';
			if (!$__templater->test($__vars['nestedPost']['children'], 'empty', array()) AND $__templater->func('is_array', array($__vars['nestedPost']['children'], ), false)) {
				$__finalCompiled .= '
			';
				if ($__templater->isTraversable($__vars['nestedPost']['children'])) {
					foreach ($__vars['nestedPost']['children'] AS $__vars['subPost']) {
						$__finalCompiled .= '
				';
						if ($__vars['subPost']['record']['message_state'] == 'deleted') {
							$__finalCompiled .= '
					' . $__templater->callMacro('post_macros', 'post_deleted', array(
								'nestedPost' => $__vars['subPost'],
								'post' => $__vars['subPost']['record'],
								'depth' => ($__vars['depth'] + 1),
								'thread' => $__vars['thread'],
							), $__vars) . '
				';
						} else {
							$__finalCompiled .= '
					' . $__templater->callMacro('post_macros', 'post', array(
								'nestedPost' => $__vars['subPost'],
								'post' => $__vars['subPost']['record'],
								'depth' => ($__vars['depth'] + 1),
								'thread' => $__vars['thread'],
							), $__vars) . '
				';
						}
						$__finalCompiled .= '
			';
					}
				}
				$__finalCompiled .= '
		';
			}
			$__finalCompiled .= '
	';
		}
		$__finalCompiled .= '
';
	}
	return $__finalCompiled;
}
);
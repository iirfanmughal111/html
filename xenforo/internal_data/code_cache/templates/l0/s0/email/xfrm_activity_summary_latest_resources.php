<?php
// FROM HASH: 7ad90c28365a18c157c6cb568b8eab4d
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if (!$__templater->test($__vars['resources'], 'empty', array())) {
		$__finalCompiled .= '
	' . $__templater->callMacro('activity_summary_macros', 'outer_header', array(
			'title' => $__vars['title'],
		), $__vars) . '

	';
		if ($__templater->isTraversable($__vars['resources'])) {
			foreach ($__vars['resources'] AS $__vars['resource']) {
				$__finalCompiled .= '
		';
				$__vars['header'] = $__templater->preEscaped('
			<a href="' . $__templater->func('link', array('canonical:resources', $__vars['resource'], ), true) . '">' . $__templater->escape($__vars['resource']['title']) . '</a>
		');
				$__finalCompiled .= '
		';
				$__vars['attribution'] = $__templater->preEscaped('
			' . $__templater->escape($__vars['resource']['username']) . ' &middot; ' . $__templater->func('date_time', array($__vars['resource']['resource_date'], ), true) . '
		');
				$__finalCompiled .= '
		';
				$__vars['footer'] = $__templater->preEscaped('
			' . 'Category' . $__vars['xf']['language']['label_separator'] . ' <a href="' . $__templater->func('link', array('canonical:resources/categories', $__vars['resource']['Category'], ), true) . '">' . $__templater->escape($__vars['resource']['Category']['title']) . '</a>
		');
				$__finalCompiled .= '
		';
				$__vars['footerOpposite'] = $__templater->preEscaped('
			<a href="' . $__templater->func('link', array('canonical:resources', $__vars['resource'], ), true) . '" class="button button--link">' . 'View this resource' . '</a>
		');
				$__finalCompiled .= '

		' . $__templater->callMacro('activity_summary_macros', 'block', array(
					'header' => $__vars['header'],
					'attribution' => $__vars['attribution'],
					'content' => $__templater->func('bb_code_type_snippet', array('emailHtml', $__vars['resource']['Description']['message'], 'resource_update', $__vars['resource']['Description'], 300, ), false),
					'footer' => $__vars['footer'],
					'footerOpposite' => $__vars['footerOpposite'],
				), $__vars) . '
	';
			}
		}
		$__finalCompiled .= '
';
	}
	return $__finalCompiled;
}
);
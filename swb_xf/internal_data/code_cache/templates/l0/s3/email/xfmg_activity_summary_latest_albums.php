<?php
// FROM HASH: 91e9c50e3d634fa897ebb52ac67ecf71
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if (!$__templater->test($__vars['albums'], 'empty', array())) {
		$__finalCompiled .= '
	' . $__templater->callMacro('activity_summary_macros', 'outer_header', array(
			'title' => $__vars['title'],
		), $__vars) . '

	';
		if ($__templater->isTraversable($__vars['albums'])) {
			foreach ($__vars['albums'] AS $__vars['album']) {
				$__finalCompiled .= '
		';
				$__vars['header'] = $__templater->preEscaped('
			<a href="' . $__templater->func('link', array('canonical:media/albums', $__vars['album'], ), true) . '">' . $__templater->escape($__vars['album']['title']) . '</a>
		');
				$__finalCompiled .= '
		';
				$__vars['attribution'] = $__templater->preEscaped('
			' . ($__vars['album']['User'] ? $__templater->escape($__vars['album']['User']['username']) : $__templater->escape($__vars['album']['username'])) . ' &middot; ' . $__templater->func('date_time', array($__vars['album']['create_date'], ), true) . ' &middot; ' . 'Media' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->filter($__vars['album']['media_count'], array(array('number_short', array()),), true) . '
		');
				$__finalCompiled .= '
		';
				$__compilerTemp1 = '';
				if ($__templater->method($__vars['album'], 'getThumbnailUrl', array(true, ))) {
					$__compilerTemp1 .= '
				<div style="text-align: center">
					<a href="' . $__templater->func('link', array('canonical:media/albums', $__vars['album'], ), true) . '"><img src="' . $__templater->escape($__templater->method($__vars['album'], 'getThumbnailUrl', array(true, ))) . '" alt="' . $__templater->escape($__vars['album']['title']) . '" /></a>
				</div>
			';
				}
				$__vars['content'] = $__templater->preEscaped('
			' . $__compilerTemp1 . '
			' . $__templater->func('bb_code_type_snippet', array('emailHtml', $__vars['album']['description'], 'xfmg_album', $__vars['album']['description'], 100, ), true) . '
		');
				$__finalCompiled .= '
		';
				$__compilerTemp2 = '';
				if ($__vars['album']['Category']) {
					$__compilerTemp2 .= '
				' . 'Category' . $__vars['xf']['language']['label_separator'] . ' <a href="' . $__templater->func('link', array('canonical:media/categories', $__vars['album']['Category'], ), true) . '">' . $__templater->escape($__vars['album']['Category']['title']) . '</a>
			';
				}
				$__vars['footer'] = $__templater->preEscaped('
			' . $__compilerTemp2 . '
		');
				$__finalCompiled .= '
		';
				$__vars['footerOpposite'] = $__templater->preEscaped('
			<a href="' . $__templater->func('link', array('canonical:media/albums', $__vars['album'], ), true) . '" class="button button--link">' . 'View album' . '</a>
		');
				$__finalCompiled .= '

		' . $__templater->callMacro('activity_summary_macros', 'block', array(
					'header' => $__vars['header'],
					'attribution' => $__vars['attribution'],
					'content' => $__vars['content'],
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
<?php
// FROM HASH: 8017d46cbf6f2fe4365113a20257ec25
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if (!$__templater->test($__vars['mediaItems'], 'empty', array())) {
		$__finalCompiled .= '
	' . $__templater->callMacro('activity_summary_macros', 'outer_header', array(
			'title' => $__vars['title'],
		), $__vars) . '

	';
		if ($__templater->isTraversable($__vars['mediaItems'])) {
			foreach ($__vars['mediaItems'] AS $__vars['mediaItem']) {
				$__finalCompiled .= '
		';
				$__vars['header'] = $__templater->preEscaped('
			<a href="' . $__templater->func('link', array('canonical:media', $__vars['mediaItem'], ), true) . '">' . $__templater->escape($__vars['mediaItem']['title']) . '</a>
		');
				$__finalCompiled .= '
		';
				$__compilerTemp1 = '';
				if ($__vars['mediaItem']['comment_count']) {
					$__compilerTemp1 .= '
				&middot; ' . 'Comments' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->filter($__vars['mediaItem']['comment_count'], array(array('number_short', array()),), true) . '
			';
				}
				$__vars['attribution'] = $__templater->preEscaped('
			' . ($__vars['mediaItem']['User'] ? $__templater->escape($__vars['mediaItem']['User']['username']) : $__templater->escape($__vars['mediaItem']['username'])) . '
			&middot; ' . $__templater->func('date_time', array($__vars['mediaItem']['media_date'], ), true) . '
			' . $__compilerTemp1 . '
		');
				$__finalCompiled .= '
		';
				$__compilerTemp2 = '';
				if ($__templater->method($__vars['mediaItem'], 'getCurrentThumbnailUrl', array(true, ))) {
					$__compilerTemp2 .= '
					<a href="' . $__templater->func('link', array('canonical:media', $__vars['mediaItem'], ), true) . '"><img src="' . $__templater->escape($__templater->method($__vars['mediaItem'], 'getCurrentThumbnailUrl', array(true, ))) . '" alt="' . $__templater->escape($__vars['mediaItem']['title']) . '" /></a>
				';
				} else {
					$__compilerTemp2 .= '
					<span class="mediaPlaceholder" style="display: inline-block; max-width: 400px">
						<a href="' . $__templater->func('link', array('canonical:media', $__vars['mediaItem'], ), true) . '">' . $__templater->escape($__vars['mediaItem']['title']) . '</a>
					</span>
				';
				}
				$__vars['content'] = $__templater->preEscaped('
			<div style="text-align: center">
				' . $__compilerTemp2 . '
			</div>
			' . $__templater->func('bb_code_type_snippet', array('emailHtml', $__vars['mediaItem']['description'], 'xfmg_media', $__vars['mediaItem']['description'], 100, ), true) . '
		');
				$__finalCompiled .= '
		';
				$__compilerTemp3 = '';
				if ($__vars['mediaItem']['Album']) {
					$__compilerTemp3 .= '
				' . 'Album' . $__vars['xf']['language']['label_separator'] . ' <a href="' . $__templater->func('link', array('canonical:media/albums', $__vars['mediaItem']['Album'], ), true) . '">' . $__templater->escape($__vars['mediaItem']['Album']['title']) . '</a>
			';
				} else if ($__vars['mediaItem']['Category']) {
					$__compilerTemp3 .= '
				' . 'Category' . $__vars['xf']['language']['label_separator'] . ' <a href="' . $__templater->func('link', array('canonical:media/categories', $__vars['mediaItem']['Category'], ), true) . '">' . $__templater->escape($__vars['mediaItem']['Category']['title']) . '</a>
			';
				}
				$__vars['footer'] = $__templater->preEscaped('
			' . $__compilerTemp3 . '
		');
				$__finalCompiled .= '
		';
				$__vars['footerOpposite'] = $__templater->preEscaped('
			<a href="' . $__templater->func('link', array('canonical:media', $__vars['mediaItem'], ), true) . '" class="button button--link">' . 'View media' . '</a>
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
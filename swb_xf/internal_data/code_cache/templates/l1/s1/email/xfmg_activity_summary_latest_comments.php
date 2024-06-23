<?php
// FROM HASH: 2170073b867cf7f6d377b70dc6afaf31
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if (!$__templater->test($__vars['comments'], 'empty', array())) {
		$__finalCompiled .= '
	' . $__templater->callMacro('activity_summary_macros', 'outer_header', array(
			'title' => $__vars['title'],
		), $__vars) . '

	';
		if ($__templater->isTraversable($__vars['comments'])) {
			foreach ($__vars['comments'] AS $__vars['comment']) {
				$__finalCompiled .= '
		';
				$__compilerTemp1 = '';
				if ($__vars['comment']['content_type'] == 'xfmg_media') {
					$__compilerTemp1 .= '
				' . 'Media' . $__vars['xf']['language']['label_separator'] . ' <a href="' . $__templater->func('link', array('canonical:media', $__vars['comment']['Content'], ), true) . '">' . $__templater->escape($__vars['comment']['Content']['title']) . '</a>
			';
				} else {
					$__compilerTemp1 .= '
				' . 'Album' . $__vars['xf']['language']['label_separator'] . ' <a href="' . $__templater->func('link', array('canonical:media/albums', $__vars['comment']['Content'], ), true) . '">' . $__templater->escape($__vars['comment']['Content']['title']) . '</a>
			';
				}
				$__vars['attribution'] = $__templater->preEscaped('
			' . ($__vars['comment']['User'] ? $__templater->escape($__vars['comment']['User']['username']) : $__templater->escape($__vars['comment']['username'])) . '
			&middot;
			' . $__compilerTemp1 . '
			&middot; ' . $__templater->func('date_time', array($__vars['comment']['comment_date'], ), true) . '
		');
				$__finalCompiled .= '
		';
				$__compilerTemp2 = '';
				if ($__vars['comment']['content_type'] == 'xfmg_media') {
					$__compilerTemp2 .= '
				';
					if ($__templater->method($__vars['comment']['Content'], 'getCurrentThumbnailUrl', array(true, ))) {
						$__compilerTemp2 .= '
					<div class="quote">
						<img src="' . $__templater->escape($__templater->method($__vars['comment']['Content'], 'getCurrentThumbnailUrl', array(true, ))) . '" width="100" alt="' . $__templater->escape($__vars['comment']['Content']['title']) . '" />
					</div>
				';
					}
					$__compilerTemp2 .= '
			';
				} else {
					$__compilerTemp2 .= '
				';
					if ($__templater->method($__vars['comment']['Content'], 'getThumbnailUrl', array(true, ))) {
						$__compilerTemp2 .= '
					<div class="quote">
						<img src="' . $__templater->escape($__templater->method($__vars['comment']['Content'], 'getThumbnailUrl', array(true, ))) . '" width="100" alt="' . $__templater->escape($__vars['comment']['Content']['title']) . '" />
					</div>
				';
					}
					$__compilerTemp2 .= '
			';
				}
				$__vars['content'] = $__templater->preEscaped('
			' . $__compilerTemp2 . '

			' . $__templater->func('bb_code_type_snippet', array('emailHtml', $__vars['comment']['message'], 'xfmg_comment', $__vars['comment']['message'], 200, ), true) . '
		');
				$__finalCompiled .= '
		';
				$__vars['footerOpposite'] = $__templater->preEscaped('
			<a href="' . $__templater->func('link', array('canonical:media/comments', $__vars['comment'], ), true) . '" class="button button--link">' . 'Read more' . '</a>
		');
				$__finalCompiled .= '

		' . $__templater->callMacro('activity_summary_macros', 'block', array(
					'attribution' => $__vars['attribution'],
					'content' => $__vars['content'],
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
<?php
// FROM HASH: 16f3eeab1d6d9555079a38d23950b987
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if (!$__templater->test($__vars['updates'], 'empty', array())) {
		$__finalCompiled .= '
	' . $__templater->callMacro('activity_summary_macros', 'outer_header', array(
			'title' => $__vars['title'],
		), $__vars) . '

	';
		if ($__templater->isTraversable($__vars['updates'])) {
			foreach ($__vars['updates'] AS $__vars['update']) {
				$__finalCompiled .= '
		';
				$__vars['header'] = $__templater->preEscaped('
			<a href="' . $__templater->func('link', array('canonical:showcase/update', $__vars['update'], ), true) . '">
				' . $__templater->escape($__vars['update']['Item']['title']) . ' - ' . $__templater->escape($__vars['update']['title']) . '
			</a>
		');
				$__finalCompiled .= '

		';
				$__compilerTemp1 = '';
				if ($__vars['update']['reaction_score']) {
					$__compilerTemp1 .= '
				&middot; ' . 'Reaction score' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->filter($__vars['update']['reaction_score'], array(array('number_short', array()),), true) . '
			';
				}
				$__vars['attribution'] = $__templater->preEscaped('
			' . ($__templater->escape($__vars['update']['User']['username']) ?: 'Deleted member') . ' &middot; ' . $__templater->func('date_time', array($__vars['update']['update_date'], ), true) . '

			' . $__compilerTemp1 . '
		');
				$__finalCompiled .= '

		';
				$__compilerTemp2 = '';
				if ($__vars['update']['Item']['cover_image_id']) {
					$__compilerTemp2 .= '
				<div class="quote">
					<span class="itemCoverImage" style="display: inline-block; max-width: 600px; max-height: 400px;">
						<a href="' . $__templater->func('link', array('canonical:showcase', $__vars['update']['Item'], ), true) . '">' . $__templater->func('sc_item_thumbnail', array($__vars['update']['Item'], ), true) . '</a>
					</span>
				</div>
			';
				}
				$__vars['content'] = $__templater->preEscaped('
			' . $__compilerTemp2 . '

			' . $__templater->func('bb_code_type_snippet', array('emailHtml', $__vars['update']['message'], 'sc_update', $__vars['update'], 500, ), true) . '
		');
				$__finalCompiled .= '

		';
				$__vars['footerOpposite'] = $__templater->preEscaped('
			<a href="' . $__templater->func('link', array('canonical:showcase/update', $__vars['update'], ), true) . '" class="button button--link">' . 'Read more' . '</a>
		');
				$__finalCompiled .= '

		' . $__templater->callMacro('activity_summary_macros', 'block', array(
					'header' => $__vars['header'],
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
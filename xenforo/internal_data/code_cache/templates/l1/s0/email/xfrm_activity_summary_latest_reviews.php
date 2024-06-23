<?php
// FROM HASH: 66846b2b8639d81880d4623b909cf137
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if (!$__templater->test($__vars['reviews'], 'empty', array())) {
		$__finalCompiled .= '
	' . $__templater->callMacro('activity_summary_macros', 'outer_header', array(
			'title' => $__vars['title'],
		), $__vars) . '

	';
		if ($__templater->isTraversable($__vars['reviews'])) {
			foreach ($__vars['reviews'] AS $__vars['review']) {
				$__finalCompiled .= '
		';
				$__vars['header'] = $__templater->preEscaped('
			<a href="' . $__templater->func('link', array('canonical:resources/review', $__vars['review'], ), true) . '">
				' . $__templater->escape($__vars['review']['Resource']['title']) . '
				-
				' . (((((($__vars['review']['rating'] >= 1) ? '&#9733;' : '&#9734;') . (($__vars['review']['rating'] >= 2) ? '&#9733;' : '&#9734;')) . (($__vars['review']['rating'] >= 3) ? '&#9733;' : '&#9734;')) . (($__vars['review']['rating'] >= 4) ? '&#9733;' : '&#9734;')) . (($__vars['review']['rating'] >= 5) ? '&#9733;' : '&#9734;')) . '
			</a>
		');
				$__finalCompiled .= '
		';
				$__compilerTemp1 = '';
				if ($__vars['review']['is_anonymous']) {
					$__compilerTemp1 .= '
				' . 'Anonymous' . '
			';
				} else {
					$__compilerTemp1 .= '
				' . ($__templater->escape($__vars['review']['User']['username']) ?: 'Deleted member') . '
			';
				}
				$__vars['reviewAuthor'] = $__templater->preEscaped('
			' . $__compilerTemp1 . '
		');
				$__finalCompiled .= '
		';
				$__vars['attribution'] = $__templater->preEscaped('
			' . $__templater->escape($__vars['reviewAuthor']) . ' &middot; ' . $__templater->func('date_time', array($__vars['review']['rating_date'], ), true) . '
		');
				$__finalCompiled .= '
		';
				$__vars['footerOpposite'] = $__templater->preEscaped('
			<a href="' . $__templater->func('link', array('canonical:resources/review', $__vars['review'], ), true) . '" class="button button--link">' . 'Read more' . '</a>
		');
				$__finalCompiled .= '

		' . $__templater->callMacro('activity_summary_macros', 'block', array(
					'header' => $__vars['header'],
					'attribution' => $__vars['attribution'],
					'content' => $__templater->func('bb_code_type_snippet', array('emailHtml', $__vars['review']['message'], 'resource_rating', $__vars['review']['message'], 100, ), false),
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
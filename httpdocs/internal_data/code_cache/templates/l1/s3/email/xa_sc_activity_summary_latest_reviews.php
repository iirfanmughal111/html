<?php
// FROM HASH: 1bf36ac1b94a8f0dd2f1e0e3ed75b9d8
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
			<a href="' . $__templater->func('link', array('canonical:showcase/review', $__vars['review'], ), true) . '">
				' . $__templater->escape($__vars['review']['Item']['title']) . '
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
				$__compilerTemp2 = '';
				if ($__vars['review']['reaction_score']) {
					$__compilerTemp2 .= '
				&middot; ' . 'Reaction score' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->filter($__vars['review']['reaction_score'], array(array('number_short', array()),), true) . '
			';
				}
				$__vars['attribution'] = $__templater->preEscaped('
			' . $__templater->escape($__vars['reviewAuthor']) . ' &middot; ' . $__templater->func('date_time', array($__vars['review']['rating_date'], ), true) . '

			' . $__compilerTemp2 . '
		');
				$__finalCompiled .= '

		';
				$__compilerTemp3 = '';
				if ($__vars['review']['Item']['cover_image_id']) {
					$__compilerTemp3 .= '
				<div class="quote">
					<span class="itemCoverImage" style="display: inline-block; max-width: 600px; max-height: 400px;">
						<a href="' . $__templater->func('link', array('canonical:showcase', $__vars['review']['Item'], ), true) . '">' . $__templater->func('sc_item_thumbnail', array($__vars['review']['Item'], ), true) . '</a>
					</span>
				</div>
			';
				}
				$__compilerTemp4 = '';
				if ($__vars['review']['title'] != '') {
					$__compilerTemp4 .= '
				<article class="message-body">
					<span class="review-title">' . $__templater->func('snippet', array($__vars['review']['title'], 100, array('stripBbCode' => true, ), ), true) . '</span>
				</article>
			';
				}
				$__compilerTemp5 = '';
				if ($__vars['review']['pros'] != '') {
					$__compilerTemp5 .= '
				<div style="padding: 5px 0; color:green;">
					<span style="font-weight:700;">' . 'Pros' . $__vars['xf']['language']['label_separator'] . '</span>					
					' . $__templater->escape($__vars['review']['pros']) . '
				</div>
			';
				}
				$__compilerTemp6 = '';
				if ($__vars['review']['cons'] != '') {
					$__compilerTemp6 .= '
				<div style="padding: 5px 0; color:#B40000;">
					<span style="font-weight:700;">' . 'Cons' . $__vars['xf']['language']['label_separator'] . '</span>					
					' . $__templater->escape($__vars['review']['cons']) . '
				</div>
			';
				}
				$__vars['content'] = $__templater->preEscaped('
			' . $__compilerTemp3 . '
  
			<div style="padding: 5px 0;">
				<span style="font-weight:700;">' . 'Rating' . $__vars['xf']['language']['label_separator'] . '</span>
				<span style="font-size:18pt; font-weight:700; color:#f9c479;">
					' . (((((($__vars['review']['rating'] >= 1) ? '&#9733;' : '&#9734;') . (($__vars['review']['rating'] >= 2) ? '&#9733;' : '&#9734;')) . (($__vars['review']['rating'] >= 3) ? '&#9733;' : '&#9734;')) . (($__vars['review']['rating'] >= 4) ? '&#9733;' : '&#9734;')) . (($__vars['review']['rating'] >= 5) ? '&#9733;' : '&#9734;')) . '
				</span>
			</div>

			' . $__compilerTemp4 . '

			' . $__compilerTemp5 . '

			' . $__compilerTemp6 . '

			' . $__templater->func('bb_code_type_snippet', array('emailHtml', $__vars['review']['message'], 'sc_rating', $__vars['review'], 500, ), true) . '
		');
				$__finalCompiled .= '

		';
				$__vars['footerOpposite'] = $__templater->preEscaped('
			<a href="' . $__templater->func('link', array('canonical:showcase/review', $__vars['review'], ), true) . '" class="button button--link">' . 'Read more' . '</a>
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
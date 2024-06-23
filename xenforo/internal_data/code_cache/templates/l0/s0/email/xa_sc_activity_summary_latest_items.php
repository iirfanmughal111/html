<?php
// FROM HASH: 6bd6241e3cd4fa2a39841a6065752c3f
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if (!$__templater->test($__vars['items'], 'empty', array())) {
		$__finalCompiled .= '
	' . $__templater->callMacro('activity_summary_macros', 'outer_header', array(
			'title' => $__vars['title'],
		), $__vars) . '

	';
		if ($__templater->isTraversable($__vars['items'])) {
			foreach ($__vars['items'] AS $__vars['item']) {
				$__finalCompiled .= '
		';
				$__vars['header'] = $__templater->preEscaped('
			<a href="' . $__templater->func('link', array('canonical:showcase', $__vars['item'], ), true) . '">' . $__templater->escape($__vars['item']['title']) . '</a>
		');
				$__finalCompiled .= '

		';
				$__compilerTemp1 = '';
				if ($__vars['item']['last_update'] > $__vars['item']['create_date']) {
					$__compilerTemp1 .= '								
				&middot; <span class="u-muted">' . 'Updated' . '</span>
				' . $__templater->func('date_dynamic', array($__vars['item']['last_update'], array(
					))) . '
			';
				}
				$__compilerTemp2 = '';
				if ($__vars['item']['author_rating'] AND $__vars['item']['Category']['allow_author_rating']) {
					$__compilerTemp2 .= '
				&middot;
				<span style="font-size:14pt; font-weight:700; color:#176093;">
					' . (((((($__vars['item']['author_rating'] >= 1) ? '&#9733;' : '&#9734;') . (($__vars['item']['author_rating'] >= 2) ? '&#9733;' : '&#9734;')) . (($__vars['item']['author_rating'] >= 3) ? '&#9733;' : '&#9734;')) . (($__vars['item']['author_rating'] >= 4) ? '&#9733;' : '&#9734;')) . (($__vars['item']['author_rating'] >= 5) ? '&#9733;' : '&#9734;')) . '
				</span>
			';
				}
				$__compilerTemp3 = '';
				if ($__vars['item']['rating_count']) {
					$__compilerTemp3 .= '
				&middot;
				<span style="font-size:14pt; font-weight:700; color:#f9c479;">
					' . (((((($__vars['item']['rating_avg'] >= 1) ? '&#9733;' : '&#9734;') . (($__vars['item']['rating_avg'] >= 2) ? '&#9733;' : '&#9734;')) . (($__vars['item']['rating_avg'] >= 3) ? '&#9733;' : '&#9734;')) . (($__vars['item']['rating_avg'] >= 4) ? '&#9733;' : '&#9734;')) . (($__vars['item']['rating_avg'] >= 5) ? '&#9733;' : '&#9734;')) . '
				</span>
			';
				}
				$__compilerTemp4 = '';
				if ($__vars['item']['view_count']) {
					$__compilerTemp4 .= '
				&middot; ' . 'Views' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->filter($__vars['item']['view_count'], array(array('number_short', array()),), true) . '
			';
				}
				$__compilerTemp5 = '';
				if ($__vars['item']['comment_count']) {
					$__compilerTemp5 .= '
				&middot; ' . 'Comments' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->filter($__vars['item']['comment_count'], array(array('number_short', array()),), true) . '
			';
				}
				$__compilerTemp6 = '';
				if ($__vars['item']['review_count']) {
					$__compilerTemp6 .= '
				&middot; ' . 'Reviews' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->filter($__vars['item']['review_count'], array(array('number_short', array()),), true) . '
			';
				}
				$__compilerTemp7 = '';
				if ($__vars['item']['reaction_score']) {
					$__compilerTemp7 .= '
				&middot; ' . 'Reaction score' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->filter($__vars['item']['reaction_score'], array(array('number_short', array()),), true) . '
			';
				}
				$__vars['attribution'] = $__templater->preEscaped('
			' . ($__vars['item']['User'] ? $__templater->escape($__vars['item']['User']['username']) : $__templater->escape($__vars['item']['username'])) . '
			&middot; ' . $__templater->func('date_time', array($__vars['item']['create_date'], ), true) . '
			' . $__compilerTemp1 . '
			' . $__compilerTemp2 . '
			' . $__compilerTemp3 . '
			' . $__compilerTemp4 . '
			' . $__compilerTemp5 . '
			' . $__compilerTemp6 . '
			' . $__compilerTemp7 . '
		');
				$__finalCompiled .= '

		';
				$__compilerTemp8 = '';
				if ($__vars['item']['cover_image_id'] AND ($__vars['item']['CoverImage'] AND $__vars['item']['CoverImage']['thumbnail_url'])) {
					$__compilerTemp8 .= '
					<div style="float: left; width: 100px; padding-bottom: 10px; margin-right: 20px;">
						<span style="max-width: 100px;">
							<a href="' . $__templater->func('link', array('canonical:showcase', $__vars['item'], ), true) . '"><img src="' . $__templater->escape($__vars['item']['CoverImage']['thumbnail_url']) . '" alt="' . $__templater->escape($__vars['item']['title']) . '" style="display: block; width:100%;" /></a>
						</span>
					</div>
				';
				} else if ($__vars['item']['Category']['content_image_url']) {
					$__compilerTemp8 .= '
					<div style="float: left; width: 100px; padding-bottom: 10px; margin-right: 20px;">
						<span style="max-width: 100px;">
							<a href="' . $__templater->func('link', array('canonical:showcase', $__vars['item'], ), true) . '"><img src="' . $__templater->func('base_url', array($__vars['item']['Category']['content_image_url'], ), true) . '" style="display: block; width:100%;"/></a>
						</span>
					</div>
				';
				}
				$__compilerTemp9 = '';
				if (!$__vars['displayHeader']) {
					$__compilerTemp9 .= '
						<div style="padding-bottom: 10px; font-weight:700; font-size: 14pt;">
							<a href="' . $__templater->func('link', array('canonical:showcase', $__vars['item'], ), true) . '">' . $__templater->escape($__vars['item']['title']) . '</a>
						</div>
					';
				}
				$__compilerTemp10 = '';
				if ($__vars['displayDescription'] AND ($__vars['item']['description'] != '')) {
					$__compilerTemp10 .= '
						<div style="padding-bottom: 20px; font-weight:700;">
							' . $__templater->func('bb_code_type_snippet', array('emailHtml', $__vars['item']['description'], 'sc_item', $__vars['item']['description'], 255, ), true) . '
						</div>
					';
				}
				$__compilerTemp11 = '';
				if ($__vars['snippetType'] == 'rich_text') {
					$__compilerTemp11 .= '
						' . $__templater->func('bb_code_type_snippet', array('emailHtml', $__vars['item']['message'], 'sc_item', $__vars['item'], 300, ), true) . '
					';
				} else {
					$__compilerTemp11 .= '
						' . $__templater->func('snippet', array($__vars['item']['message'], 300, array('stripBbCode' => true, 'stripQuote' => true, ), ), true) . '
					';
				}
				$__vars['content'] = $__templater->preEscaped('
			<div style="width: 100%; margin: 0 auto;">
				' . $__compilerTemp8 . '

				<div>
					' . $__compilerTemp9 . '

					' . $__compilerTemp10 . '

					' . $__compilerTemp11 . '
				</div>
			</div>
		');
				$__finalCompiled .= '

		';
				$__vars['footer'] = $__templater->preEscaped('
			' . 'Category' . $__vars['xf']['language']['label_separator'] . ' <a href="' . $__templater->func('link', array('canonical:showcase/categories', $__vars['item']['Category'], ), true) . '">' . $__templater->escape($__vars['item']['Category']['title']) . '</a>
		');
				$__finalCompiled .= '

		';
				$__vars['footerOpposite'] = $__templater->preEscaped('
			<a href="' . $__templater->func('link', array('canonical:showcase', $__vars['item'], ), true) . '" class="button button--link">' . 'View this item' . '</a>
		');
				$__finalCompiled .= '

		' . $__templater->callMacro('activity_summary_macros', 'block', array(
					'header' => ($__vars['displayHeader'] ? $__vars['header'] : ''),
					'attribution' => ($__vars['displayAttribution'] ? $__vars['attribution'] : ''),
					'content' => $__vars['content'],
					'footer' => ($__vars['displayFooter'] ? $__vars['footer'] : ''),
					'footerOpposite' => ($__vars['displayFooterOpposite'] ? $__vars['footerOpposite'] : ''),
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
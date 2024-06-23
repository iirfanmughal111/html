<?php
// FROM HASH: 1253ee668630323ab40a5f04c9568157
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->includeCss('xa_sc.less');
	$__finalCompiled .= '
';
	$__templater->includeCss('message.less');
	$__finalCompiled .= '
<div class="embeddedScItem block--messages">
	<div class="block-row block-row--separated" data-author="' . ($__templater->escape($__vars['item']['User']['username']) ?: $__templater->escape($__vars['item']['username'])) . '">
		<div class="contentRow scItemSearchResultRow">
			';
	if ($__vars['item']['CoverImage'] OR $__vars['item']['Category']['content_image_url']) {
		$__finalCompiled .= '
				<span class="contentRow-figure">
					';
		if ($__vars['item']['CoverImage']) {
			$__finalCompiled .= '
						<a href="' . $__templater->func('link', array('showcase', $__vars['item'], ), true) . '">
							' . $__templater->func('sc_item_thumbnail', array($__vars['item'], ), true) . '
						</a>				
					';
		} else if ($__vars['item']['Category']['content_image_url']) {
			$__finalCompiled .= '
						<a href="' . $__templater->func('link', array('showcase', $__vars['item'], ), true) . '">
							' . $__templater->func('sc_category_icon', array($__vars['item'], ), true) . '
						</a>				
					';
		}
		$__finalCompiled .= '
				</span>
			';
	}
	$__finalCompiled .= '
			<div class="contentRow-main">
				<h3 class="contentRow-title">
					<a href="' . $__templater->func('link', array('showcase', $__vars['item'], ), true) . '">' . $__templater->func('prefix', array('sc_item', $__vars['item'], ), true) . ' ' . $__templater->escape($__vars['item']['title']) . '</a>
				</h3>

				<div class="contentRow-snippet">
					' . $__templater->func('snippet', array($__vars['item']['message'], 300, array('stripQuote' => true, ), ), true) . '
				</div>

				<div class="contentRow-minor contentRow-minor--hideLinks">
					<ul class="listInline listInline--bullet">
						<li>' . $__templater->func('username_link', array($__vars['item']['User'], false, array(
		'defaultname' => $__vars['item']['User']['username'],
	))) . '</li>
						<li>' . $__templater->func('date_dynamic', array($__vars['item']['create_date'], array(
	))) . '</li>
						';
	if ($__vars['item']['author_rating']) {
		$__finalCompiled .= '
							<li>
								' . $__templater->callMacro('rating_macros', 'stars_text', array(
			'rating' => $__vars['item']['author_rating'],
			'text' => 'Author rating' . $__vars['xf']['language']['label_separator'],
			'rowClass' => 'ratingStarsRow',
			'starsClass' => 'ratingStars--smaller ratingStars--scAuthorRating',
		), $__vars) . '
							</li>
						';
	}
	$__finalCompiled .= '
						';
	if ($__vars['item']['rating_avg'] AND $__vars['item']['rating_count']) {
		$__finalCompiled .= '
							<li>
								' . $__templater->callMacro('rating_macros', 'stars_text', array(
			'rating' => $__vars['item']['rating_avg'],
			'count' => $__vars['item']['rating_count'],
			'rowClass' => 'ratingStarsRow',
			'starsClass' => 'ratingStars--smaller',
		), $__vars) . '
							</li>
						';
	}
	$__finalCompiled .= '
						';
	if ($__vars['item']['comment_count']) {
		$__finalCompiled .= '<li>' . 'Comments' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->filter($__vars['item']['comment_count'], array(array('number_short', array()),), true) . '</li>';
	}
	$__finalCompiled .= '
						';
	if ($__vars['item']['review_count']) {
		$__finalCompiled .= '<li>' . 'Reviews' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->filter($__vars['item']['review_count'], array(array('number_short', array()),), true) . '</li>';
	}
	$__finalCompiled .= '
						<li>' . 'Category' . $__vars['xf']['language']['label_separator'] . ' <a href="' . $__templater->func('link', array('showcase/categories', $__vars['item']['Category'], ), true) . '">' . $__templater->escape($__vars['item']['Category']['title']) . '</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>';
	return $__finalCompiled;
}
);
<?php
// FROM HASH: e07805a9fd8cc79962edcaf7e1b27bec
return array(
'macros' => array('items_grid' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'items' => '!',
		'itemsCount' => '!',
		'viewAllLink' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	';
	if (!$__templater->test($__vars['items'], 'empty', array())) {
		$__finalCompiled .= '
		';
		$__templater->includeCss('carousel.less');
		$__finalCompiled .= '
		';
		$__templater->includeCss('xa_sc.less');
		$__finalCompiled .= '
		';
		$__templater->includeCss('xa_sc_featured_grid.less');
		$__finalCompiled .= '

		';
		$__vars['extras'] = $__templater->func('property', array('xaScItemsGridBlockElements', ), false);
		$__finalCompiled .= '

		<div class="carousel carousel--withFooter carousel--scFeaturedItems">		
			<ul class="sc-featured-grid">
				';
		$__vars['i'] = 0;
		if ($__templater->isTraversable($__vars['items'])) {
			foreach ($__vars['items'] AS $__vars['item']) {
				$__vars['i']++;
				$__finalCompiled .= '
					';
				if ($__vars['i'] == 1) {
					$__finalCompiled .= '
						<li class="first">
							';
					if ($__vars['itemsCount'] < 3) {
						$__finalCompiled .= '
								<div class="item medium-item ' . (($__templater->method($__vars['item'], 'isUnread', array()) AND (!$__vars['forceRead'])) ? ' is-unread' : '') . '">
									' . $__templater->callMacro(null, 'item_cover_image', array(
							'item' => $__vars['item'],
							'extras' => $__vars['extras'],
							'blockType' => 'items_grid',
						), $__vars) . '

									' . $__templater->callMacro(null, 'item_caption', array(
							'item' => $__vars['item'],
							'extras' => $__vars['extras'],
							'extraHeadingClass' => '',
							'blockType' => 'items_grid',
						), $__vars) . '
								</div>
							';
					} else {
						$__finalCompiled .= '
								<div class="item large-item ' . (($__templater->method($__vars['item'], 'isUnread', array()) AND (!$__vars['forceRead'])) ? ' is-unread' : '') . '">
									' . $__templater->callMacro(null, 'item_cover_image', array(
							'item' => $__vars['item'],
							'extras' => $__vars['extras'],
							'blockType' => 'items_grid',
						), $__vars) . '

									' . $__templater->callMacro(null, 'item_caption', array(
							'item' => $__vars['item'],
							'extras' => $__vars['extras'],
							'extraHeadingClass' => '',
							'blockType' => 'items_grid',
						), $__vars) . '
								</div>
							';
					}
					$__finalCompiled .= '
						</li>
					';
				}
				$__finalCompiled .= '

					';
				if ($__vars['i'] == 2) {
					$__finalCompiled .= '
						<li class="second">
							<div class="item medium-item ' . (($__templater->method($__vars['item'], 'isUnread', array()) AND (!$__vars['forceRead'])) ? ' is-unread' : '') . '">
								' . $__templater->callMacro(null, 'item_cover_image', array(
						'item' => $__vars['item'],
						'extras' => $__vars['extras'],
						'blockType' => 'items_grid',
					), $__vars) . '

								' . $__templater->callMacro(null, 'item_caption', array(
						'item' => $__vars['item'],
						'extras' => $__vars['extras'],
						'extraHeadingClass' => 'heading-small',
						'blockType' => 'items_grid',
					), $__vars) . '
							</div>
						</li>
					';
				}
				$__finalCompiled .= '

					';
				if ($__vars['i'] == 3) {
					$__finalCompiled .= '
						<li class="second">
							<div class="item medium-item-2 ' . (($__templater->method($__vars['item'], 'isUnread', array()) AND (!$__vars['forceRead'])) ? ' is-unread' : '') . '">
								' . $__templater->callMacro(null, 'item_cover_image', array(
						'item' => $__vars['item'],
						'extras' => $__vars['extras'],
						'blockType' => 'items_grid',
					), $__vars) . '

								' . $__templater->callMacro(null, 'item_caption', array(
						'item' => $__vars['item'],
						'extras' => $__vars['extras'],
						'extraHeadingClass' => 'heading-small',
						'blockType' => 'items_grid',
					), $__vars) . '
							</div>
						</li>
					';
				}
				$__finalCompiled .= '
				';
			}
		}
		$__finalCompiled .= '
			</ul>

			' . $__templater->callMacro(null, 'items_footer', array(
			'viewAllLink' => $__vars['viewAllLink'],
		), $__vars) . '
		</div>
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'items_carousel' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'items' => '!',
		'viewAllLink' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	';
	if (!$__templater->test($__vars['items'], 'empty', array())) {
		$__finalCompiled .= '
		';
		$__templater->includeCss('carousel.less');
		$__finalCompiled .= '
		';
		$__templater->includeCss('lightslider.less');
		$__finalCompiled .= '
		';
		$__templater->includeCss('xa_sc.less');
		$__finalCompiled .= '
		
		';
		$__templater->includeJs(array(
			'prod' => 'xf/carousel-compiled.js',
			'dev' => 'vendor/lightslider/lightslider.min.js, xf/carousel.js',
		));
		$__finalCompiled .= '

		';
		$__vars['extras'] = $__templater->func('property', array('xaScItemsCarouselElements', ), false);
		$__finalCompiled .= '
		';
		$__vars['showType'] = $__templater->func('property', array('xaScItemsCarouselShowType', ), false);
		$__finalCompiled .= '

		<div class="carousel carousel--withFooter carousel--scFeaturedItems">
			<ul class="carousel-body ' . (($__vars['showType'] == 'show1') ? 'carousel-body--show1' : 'carousel-body--show2') . '" data-xf-init="carousel">
				';
		if ($__templater->isTraversable($__vars['items'])) {
			foreach ($__vars['items'] AS $__vars['item']) {
				$__finalCompiled .= '
					<li>
						<div class="carousel-item ' . (($__templater->method($__vars['item'], 'isUnread', array()) AND (!$__vars['forceRead'])) ? ' is-unread' : '') . '">
							<div class="contentRow">
								<div class="contentRow-main">
									';
				if ($__vars['extras']['category']) {
					$__finalCompiled .= '
										<div class="contentRow-scCategory">
											<a href="' . $__templater->func('link', array('showcase/categories', $__vars['item']['Category'], ), true) . '">' . $__templater->escape($__vars['item']['Category']['title']) . '</a>
										</div>
									';
				}
				$__finalCompiled .= '

									' . $__templater->callMacro(null, 'item_cover_image', array(
					'item' => $__vars['item'],
					'extras' => $__vars['extras'],
					'blockType' => 'items_carousel',
				), $__vars) . '
									
									<h4 class="contentRow-title"><a href="' . $__templater->func('link', array('showcase', $__vars['item'], ), true) . '">' . $__templater->func('prefix', array('sc_item', $__vars['item'], ), true) . $__templater->escape($__vars['item']['title']) . '</a></h4>

									' . $__templater->callMacro(null, 'item_location', array(
					'item' => $__vars['item'],
					'extras' => $__vars['extras'],
					'blockType' => 'items_carousel',
				), $__vars) . '

									';
				if ($__vars['extras']['preview_snippet']) {
					$__finalCompiled .= '
										<div class="contentRow-lesser">
											' . $__templater->func('snippet', array($__vars['item']['message'], 300, array('stripQuote' => true, ), ), true) . '
										</div>
									';
				}
				$__finalCompiled .= '

									';
				if ($__vars['extras']['custom_fields']) {
					$__finalCompiled .= '
										';
					$__vars['scCustomFieldGroupNames'] = array('header', 'section_1_above', 'section_1_below', 'section_2_above', 'section_2_below', 'section_3_above', 'section_3_below', 'section_4_above', 'section_4_below', 'section_5_above', 'section_5_below', 'section_6_above', 'section_6_below', 'new_tab', 'sidebar', 'new_sidebar_block', 'self_place', );
					$__finalCompiled .= '
										';
					$__compilerTemp1 = '';
					$__compilerTemp1 .= '
													';
					if ($__templater->isTraversable($__vars['scCustomFieldGroupNames'])) {
						foreach ($__vars['scCustomFieldGroupNames'] AS $__vars['scCustomFieldGroupName']) {
							$__compilerTemp1 .= '
														' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
								'type' => 'sc_items',
								'group' => $__vars['scCustomFieldGroupName'],
								'set' => $__vars['item']['custom_fields'],
								'onlyInclude' => $__vars['item']['Category']['field_cache'],
								'additionalFilters' => array('display_on_list', ),
								'wrapperClass' => 'carouselLayout-fields carouselLayout-fields--before',
								'valueClass' => 'pairs pairs--columns pairs--fixedSmall',
							), $__vars) . '
													';
						}
					}
					$__compilerTemp1 .= '
												';
					if (strlen(trim($__compilerTemp1)) > 0) {
						$__finalCompiled .= '
											<div class="contentRow-lesser contentRow-itemCustomFields">
												' . $__compilerTemp1 . '
											</div>
										';
					}
					$__finalCompiled .= '
									';
				}
				$__finalCompiled .= '

									<div class="contentRow-minor contentRow-minor--smaller">
										<ul class="listInline listInline--bullet">
											<li>' . $__templater->func('username_link', array($__vars['item']['User'], false, array(
					'defaultname' => ($__vars['item']['User']['username'] ?: 'Deleted member'),
					'class' => 'u-concealed',
				))) . '</li>
											<li><a href="' . $__templater->func('link', array('showcase', $__vars['item'], ), true) . '" rel="nofollow" class="u-concealed">' . $__templater->func('date_dynamic', array($__vars['item']['create_date'], array(
				))) . '</a></li>
											';
				if ($__vars['item']['last_update'] AND ($__vars['item']['last_update'] > $__vars['item']['create_date'])) {
					$__finalCompiled .= '
												<li>' . 'Updated' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->func('date_dynamic', array($__vars['item']['last_update'], array(
					))) . '</li>
											';
				}
				$__finalCompiled .= '
											';
				if ($__vars['item']['author_rating'] AND ($__vars['item']['Category']['allow_author_rating'] AND $__vars['extras']['author_rating'])) {
					$__finalCompiled .= '
												<li>
													' . $__templater->callMacro('rating_macros', 'stars', array(
						'rating' => $__vars['item']['author_rating'],
						'class' => 'ratingStars--scAuthorRating',
					), $__vars) . '
												</li>
											';
				}
				$__finalCompiled .= '
											';
				if ($__vars['item']['rating_avg'] AND ($__vars['item']['rating_count'] AND ($__vars['item']['Category']['allow_ratings'] AND $__vars['extras']['rating_avg']))) {
					$__finalCompiled .= '
												<li>
													' . $__templater->callMacro('rating_macros', 'stars', array(
						'rating' => $__vars['item']['rating_avg'],
					), $__vars) . '
												</li>
											';
				}
				$__finalCompiled .= '
											';
				if ($__vars['item']['view_count'] AND $__vars['extras']['view_count']) {
					$__finalCompiled .= '
												<li>' . 'Views' . ': ' . $__templater->filter($__vars['item']['view_count'], array(array('number_short', array()),), true) . '</li>
											';
				}
				$__finalCompiled .= '
											';
				if ($__vars['item']['reaction_score'] AND $__vars['extras']['reaction_score']) {
					$__finalCompiled .= '
												<li>' . 'Reaction score' . ': ' . $__templater->filter($__vars['item']['reaction_score'], array(array('number_short', array()),), true) . '</li>
											';
				}
				$__finalCompiled .= '
											';
				if ($__vars['item']['update_count'] AND $__vars['extras']['update_count']) {
					$__finalCompiled .= '
												<li><a href="' . $__templater->func('link', array('showcase/updates', $__vars['item'], ), true) . '" class="u-concealed">' . 'Updates' . ': ' . $__templater->filter($__vars['item']['update_count'], array(array('number_short', array()),), true) . '</a></li>
											';
				}
				$__finalCompiled .= '
											';
				if ($__vars['item']['review_count'] AND $__vars['extras']['review_count']) {
					$__finalCompiled .= '
												<li><a href="' . $__templater->func('link', array('showcase/reviews', $__vars['item'], ), true) . '" class="u-concealed">' . 'Reviews' . ': ' . $__templater->filter($__vars['item']['review_count'], array(array('number_short', array()),), true) . '</a></li>
											';
				}
				$__finalCompiled .= '
											';
				if ($__vars['item']['comment_count'] AND $__vars['extras']['comment_count']) {
					$__finalCompiled .= '
												<li><a href="' . $__templater->func('link', array('showcase', $__vars['item'], ), true) . '#comments" class="u-concealed">' . 'Comments' . ': ' . $__templater->filter($__vars['item']['comment_count'], array(array('number_short', array()),), true) . '</a></li>
											';
				}
				$__finalCompiled .= '
											';
				if ($__vars['extras']['share_this_item']) {
					$__finalCompiled .= '
												<li class="u-flexStretch"><a href="' . $__templater->func('link', array('showcase', $__vars['item'], ), true) . '"
													class="u-muted"
													data-xf-init="share-tooltip"
													data-href="' . $__templater->func('link', array('showcase/share', $__vars['item'], ), true) . '"
													aria-label="' . $__templater->filter('Share', array(array('for_attr', array()),), true) . '"
													rel="nofollow">
													' . $__templater->fontAwesome('fa-share-alt', array(
					)) . '
												</a></li>
											';
				}
				$__finalCompiled .= '
										</ul>
									</div>
								</div>
							</div>
						</div>
					</li>
				';
			}
		}
		$__finalCompiled .= '
			</ul>

			' . $__templater->callMacro(null, 'items_footer', array(
			'viewAllLink' => $__vars['viewAllLink'],
		), $__vars) . '
		</div>
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'items_carousel_simple' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'items' => '!',
		'viewAllLink' => '!',
		'isFeaturedItems' => false,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	';
	if (!$__templater->test($__vars['items'], 'empty', array())) {
		$__finalCompiled .= '
		';
		$__templater->includeCss('carousel.less');
		$__finalCompiled .= '
		';
		$__templater->includeCss('lightslider.less');
		$__finalCompiled .= '
		';
		$__templater->includeCss('xa_sc.less');
		$__finalCompiled .= '

		';
		$__templater->includeJs(array(
			'prod' => 'xf/carousel-compiled.js',
			'dev' => 'vendor/lightslider/lightslider.min.js, xf/carousel.js',
		));
		$__finalCompiled .= '

		';
		$__vars['extras'] = $__templater->func('property', array('xaScItemsCarouselSimpleElements', ), false);
		$__finalCompiled .= '

		<div class="carousel carousel--withFooter carousel--scFeaturedItemsSimple">
			<ul class="carousel-body carousel-body--show1" data-xf-init="carousel">
				';
		if ($__templater->isTraversable($__vars['items'])) {
			foreach ($__vars['items'] AS $__vars['item']) {
				$__finalCompiled .= '
					<li>
						<div class="carousel-item ' . (($__templater->method($__vars['item'], 'isUnread', array()) AND (!$__vars['forceRead'])) ? ' is-unread' : '') . '">
							<div class="contentRow">
								<div class="contentRow-main">
									';
				if ($__vars['extras']['cover_image']) {
					$__finalCompiled .= '
										';
					if ($__vars['item']['CoverImage'] AND $__templater->method($__vars['xf']['visitor'], 'hasShowcaseItemPermission', array('viewItemAttach', ))) {
						$__finalCompiled .= '
											<div class="contentRow-figure">
												<a href="' . $__templater->func('link', array('showcase', $__vars['item'], ), true) . '">
													<img src="' . $__templater->func('link', array('canonical:showcase/cover-image', $__vars['item'], ), true) . '" />
												</a>                                            
											</div>
										';
					} else if ($__vars['item']['Category']['content_image_url']) {
						$__finalCompiled .= '
											<div class="contentRow-figure">
												<a href="' . $__templater->func('link', array('showcase', $__vars['item'], ), true) . '">
													' . $__templater->func('sc_category_icon', array($__vars['item'], ), true) . '
												</a>                                            
											</div>
										';
					}
					$__finalCompiled .= '
									';
				}
				$__finalCompiled .= '

									';
				if ($__vars['extras']['category']) {
					$__finalCompiled .= '
										<div class="contentRow-scCategory">
											<a href="' . $__templater->func('link', array('showcase/categories', $__vars['item']['Category'], ), true) . '">' . $__templater->escape($__vars['item']['Category']['title']) . '</a>
										</div>
									';
				}
				$__finalCompiled .= '

									';
				if ($__vars['extras']['title']) {
					$__finalCompiled .= '
										<h4 class="contentRow-title">
											';
					if ($__vars['item']['prefix_id']) {
						$__finalCompiled .= '
												' . $__templater->func('prefix', array('sc_item', $__vars['item'], 'html', '', ), true) . '
											';
					}
					$__finalCompiled .= '
											<a href="' . $__templater->func('link', array('showcase', $__vars['item'], ), true) . '">' . $__templater->escape($__vars['item']['title']) . '</a>
										</h4>
									';
				}
				$__finalCompiled .= '

									' . $__templater->callMacro(null, 'item_location', array(
					'item' => $__vars['item'],
					'extras' => $__vars['extras'],
					'blockType' => 'items_carousel',
				), $__vars) . '

									';
				if ($__vars['item']['author_rating'] AND ($__vars['item']['Category']['allow_author_rating'] AND $__vars['extras']['author_rating'])) {
					$__finalCompiled .= '
										<div class="contentRow-lesser contentRow-minor contentRow-minor--smaller">
											' . $__templater->callMacro('rating_macros', 'stars', array(
						'rating' => $__vars['item']['author_rating'],
						'class' => 'ratingStars--scAuthorRating',
					), $__vars) . '
										</div>
									';
				}
				$__finalCompiled .= '

									';
				if ($__vars['item']['rating_avg'] AND ($__vars['item']['rating_count'] AND ($__vars['item']['Category']['allow_ratings'] AND $__vars['extras']['rating_avg']))) {
					$__finalCompiled .= '
										<div class="contentRow-lesser contentRow-minor contentRow-minor--smaller">
											' . $__templater->callMacro('rating_macros', 'stars', array(
						'rating' => $__vars['item']['rating_avg'],
					), $__vars) . '
										</div>
									';
				}
				$__finalCompiled .= '

									';
				if ($__vars['extras']['preview_snippet']) {
					$__finalCompiled .= '
										<div class="contentRow-lesser">
											';
					if ($__vars['item']['description']) {
						$__finalCompiled .= '
												' . $__templater->func('snippet', array($__vars['item']['description'], 150, array('stripQuote' => true, ), ), true) . '
											';
					} else {
						$__finalCompiled .= '
												' . $__templater->func('snippet', array($__vars['item']['message'], 159, array('stripQuote' => true, ), ), true) . '
											';
					}
					$__finalCompiled .= '
										</div>
									';
				}
				$__finalCompiled .= '

									';
				$__compilerTemp1 = '';
				$__compilerTemp1 .= '
													';
				if ($__vars['extras']['author']) {
					$__compilerTemp1 .= '
														<li>' . $__templater->func('username_link', array($__vars['item']['User'], false, array(
						'defaultname' => ($__vars['item']['User']['username'] ?: 'Deleted member'),
						'class' => 'u-concealed',
					))) . '</li>
													';
				}
				$__compilerTemp1 .= '
													';
				if ($__vars['extras']['create_date']) {
					$__compilerTemp1 .= '
														<li><a href="' . $__templater->func('link', array('showcase', $__vars['item'], ), true) . '" rel="nofollow" class="u-concealed">' . $__templater->func('date_dynamic', array($__vars['item']['create_date'], array(
					))) . '</a></li>
														';
					if ($__vars['item']['last_update'] AND ($__vars['item']['last_update'] > $__vars['item']['create_date'])) {
						$__compilerTemp1 .= '
															<li>' . 'Updated' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->func('date_dynamic', array($__vars['item']['last_update'], array(
						))) . '</li>
														';
					}
					$__compilerTemp1 .= '
													';
				}
				$__compilerTemp1 .= '
													';
				if ($__vars['item']['view_count'] AND $__vars['extras']['view_count']) {
					$__compilerTemp1 .= '
														<li>' . 'Views' . ': ' . $__templater->filter($__vars['item']['view_count'], array(array('number_short', array()),), true) . '</li>
													';
				}
				$__compilerTemp1 .= '
													';
				if ($__vars['item']['reaction_score'] AND $__vars['extras']['reaction_score']) {
					$__compilerTemp1 .= '
														<li>' . 'Reaction score' . ': ' . $__templater->filter($__vars['item']['reaction_score'], array(array('number_short', array()),), true) . '</li>
													';
				}
				$__compilerTemp1 .= '
													';
				if ($__vars['item']['update_count'] AND $__vars['extras']['update_count']) {
					$__compilerTemp1 .= '
														<li><a href="' . $__templater->func('link', array('showcase/updates', $__vars['item'], ), true) . '" class="u-concealed">' . 'Updates' . ': ' . $__templater->filter($__vars['item']['update_count'], array(array('number_short', array()),), true) . '</a></li>
													';
				}
				$__compilerTemp1 .= '
													';
				if ($__vars['item']['review_count'] AND $__vars['extras']['review_count']) {
					$__compilerTemp1 .= '
														<li><a href="' . $__templater->func('link', array('showcase/reviews', $__vars['item'], ), true) . '" class="u-concealed">' . 'Reviews' . ': ' . $__templater->filter($__vars['item']['review_count'], array(array('number_short', array()),), true) . '</a></li>
													';
				}
				$__compilerTemp1 .= '
													';
				if ($__vars['item']['comment_count'] AND $__vars['extras']['comment_count']) {
					$__compilerTemp1 .= '
														<li><a href="' . $__templater->func('link', array('showcase', $__vars['item'], ), true) . '#comments" class="u-concealed">' . 'Comments' . ': ' . $__templater->filter($__vars['item']['comment_count'], array(array('number_short', array()),), true) . '</a></li>
													';
				}
				$__compilerTemp1 .= '
													';
				if ($__vars['extras']['share_this_item']) {
					$__compilerTemp1 .= '
														<li class="u-flexStretch"><a href="' . $__templater->func('link', array('showcase', $__vars['item'], ), true) . '"
															class="u-concealed"
															data-xf-init="share-tooltip"
															data-href="' . $__templater->func('link', array('showcase/share', $__vars['item'], ), true) . '"
															aria-label="' . $__templater->filter('Share', array(array('for_attr', array()),), true) . '"
															rel="nofollow">
															' . $__templater->fontAwesome('fa-share-alt', array(
					)) . '
														</a></li>
													';
				}
				$__compilerTemp1 .= '
												';
				if (strlen(trim($__compilerTemp1)) > 0) {
					$__finalCompiled .= '
										<div class="contentRow-minor contentRow-minor--smaller">
											<ul class="listInline listInline--bullet">
												' . $__compilerTemp1 . '
											</ul>
										</div>
									';
				}
				$__finalCompiled .= '
								</div>
							</div>
						</div>
					</li>
				';
			}
		}
		$__finalCompiled .= '
			</ul>
			<div class="carousel-footer">
				';
		if ($__vars['isFeaturedItems']) {
			$__finalCompiled .= '
					<a href="' . $__templater->escape($__vars['viewAllLink']) . '">' . 'View all featured items' . '</a>
				';
		} else {
			$__finalCompiled .= '
					<a href="' . $__templater->escape($__vars['viewAllLink']) . '">' . 'View more items' . '</a>
				';
		}
		$__finalCompiled .= '
			</div>
		</div>
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'item_cover_image' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'item' => '!',
		'extras' => '',
		'blockType' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	';
	if ($__vars['blockType'] == 'items_grid') {
		$__finalCompiled .= '
		';
		if ($__vars['item']['CoverImage']) {
			$__finalCompiled .= '
			<a class="image-link" style="background: url(' . $__templater->func('link', array('showcase/cover-image', $__vars['item'], ), true) . ') no-repeat center center transparent; background-size: cover;" href="' . $__templater->func('link', array('showcase', $__vars['item'], ), true) . '"></a>
		';
		} else if ($__vars['item']['Category']['content_image_url']) {
			$__finalCompiled .= '
			<a class="image-link" style="background: url(' . $__templater->func('base_url', array($__vars['item']['Category']['content_image_url'], ), true) . ') no-repeat center center transparent; background-size: cover;" href="' . $__templater->func('link', array('showcase', $__vars['item'], ), true) . '"></a>
		';
		} else {
			$__finalCompiled .= '
			<a class="image-link" style="background: #185886; background-size: cover;" href="' . $__templater->func('link', array('showcase', $__vars['item'], ), true) . '"></a>
		';
		}
		$__finalCompiled .= '
	';
	} else if ($__vars['blockType'] == 'items_carousel') {
		$__finalCompiled .= '
		';
		if ($__vars['item']['CoverImage']) {
			$__finalCompiled .= '
			<div class="contentRow-figure">
				<a href="' . $__templater->func('link', array('showcase', $__vars['item'], ), true) . '">' . $__templater->func('sc_item_thumbnail', array($__vars['item'], ), true) . '</a>											
			</div>
		';
		} else if ($__vars['item']['Category']['content_image_url']) {
			$__finalCompiled .= '
			<div class="contentRow-figure">
				<a href="' . $__templater->func('link', array('showcase', $__vars['item'], ), true) . '">' . $__templater->func('sc_category_icon', array($__vars['item'], ), true) . '</a>											
			</div>
		';
		}
		$__finalCompiled .= '
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'item_caption' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'item' => '!',
		'extras' => '!',
		'extraHeadingClass' => '',
		'blockType' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	<div class="caption">
		' . $__templater->callMacro(null, 'item_heading', array(
		'item' => $__vars['item'],
		'extras' => $__vars['extras'],
		'extraHeadingClass' => $__vars['extraHeadingClass'],
	), $__vars) . '

		' . $__templater->callMacro(null, 'item_ratings', array(
		'item' => $__vars['item'],
		'extras' => $__vars['extras'],
	), $__vars) . '

		' . $__templater->callMacro(null, 'item_location', array(
		'item' => $__vars['item'],
		'extras' => $__vars['extras'],
		'blockType' => $__vars['blockType'],
	), $__vars) . '

		' . $__templater->callMacro(null, 'item_meta', array(
		'item' => $__vars['item'],
		'extras' => $__vars['extras'],
	), $__vars) . '
	</div>
';
	return $__finalCompiled;
}
),
'item_heading' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'item' => '!',
		'extras' => '',
		'extraHeadingClass' => '',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	<h3>
		<span class="item-heading ' . ($__templater->escape($__vars['extraHeadingClass']) ?: '') . '">
			<a href="' . $__templater->func('link', array('showcase', $__vars['item'], ), true) . '">' . $__templater->func('prefix', array('sc_item', $__vars['item'], ), true) . $__templater->escape($__vars['item']['title']) . '</a>
		</span>		
	</h3>
';
	return $__finalCompiled;
}
),
'item_ratings' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'item' => '!',
		'extras' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	';
	if ($__vars['extras']['author_rating'] OR $__vars['extras']['rating_avg']) {
		$__finalCompiled .= ' 
		<ul class="listInline listInline--bullet item-rating">
			';
		if ($__vars['extras']['author_rating'] AND ($__vars['item']['author_rating'] AND $__vars['item']['Category']['allow_author_rating'])) {
			$__finalCompiled .= '
				<li>
					' . $__templater->callMacro('rating_macros', 'stars', array(
				'rating' => $__vars['item']['author_rating'],
				'class' => 'ratingStars--smaller ratingStars--scAuthorRating',
			), $__vars) . '
				</li>
			';
		}
		$__finalCompiled .= '
			';
		if ($__vars['extras']['rating_avg'] AND ($__vars['item']['rating_avg'] AND ($__vars['item']['rating_count'] AND $__vars['item']['Category']['allow_ratings']))) {
			$__finalCompiled .= '
				<li>
					' . $__templater->callMacro('rating_macros', 'stars', array(
				'rating' => $__vars['item']['rating_avg'],
				'class' => 'ratingStars--smaller',
			), $__vars) . '
				</li>
			';
		}
		$__finalCompiled .= '
		</ul>
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'item_location' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'item' => '!',
		'extras' => '',
		'blockType' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= $__templater->escape($__templater->method($__vars['item'], 'getItemLocationForList', array()));
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
		<div class="' . (($__vars['blockType'] == 'items_grid') ? 'item-location' : 'contentRow-minor contentRow-minor--smaller contentRow-itemLocation') . '">
			' . $__compilerTemp1 . '

			';
		if ($__templater->method($__vars['item'], 'canViewItemMap', array())) {
			$__finalCompiled .= '
				<a href="' . $__templater->func('link', array('showcase/map-overlay', $__vars['item'], ), true) . '" rel="nofollow" class="' . (($__vars['blockType'] == 'items_grid') ? 'item-location-icon' : 'contentRow-itemLocationIcon') . '" data-xf-click="overlay">' . $__templater->fontAwesome('fa-map-marker-alt', array(
			)) . '</a>				
			';
		}
		$__finalCompiled .= '
		</div>	
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'item_meta' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'item' => '!',
		'extras' => '',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
			';
	if ($__vars['extras']['username']) {
		$__compilerTemp1 .= '
				<li>
					' . $__templater->func('username_link', array($__vars['item']['User'], false, array(
			'defaultname' => ($__vars['item']['username'] ?: 'Deleted member'),
			'class' => 'category-title',
		))) . '
				</li> 
			';
	}
	$__compilerTemp1 .= '
			';
	if ($__vars['extras']['create_date']) {
		$__compilerTemp1 .= '
				<li><time class="create-date">' . $__templater->func('date_dynamic', array($__vars['item']['create_date'], array(
		))) . '</time></li> 
				';
		if ($__vars['item']['last_update'] AND ($__vars['item']['last_update'] > $__vars['item']['create_date'])) {
			$__compilerTemp1 .= '
					<li><time class="create-date">' . 'Updated' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->func('date_dynamic', array($__vars['item']['last_update'], array(
			))) . '</time></li>
				';
		}
		$__compilerTemp1 .= '				
			';
	}
	$__compilerTemp1 .= '
			';
	if ($__vars['extras']['category']) {
		$__compilerTemp1 .= '
				<li><a class="category-title category-' . $__templater->escape($__vars['item']['category_id']) . '" href="' . $__templater->func('link', array('showcase/categories', $__vars['item'], ), true) . '">' . $__templater->escape($__vars['item']['Category']['title']) . '</a></li>
			';
	}
	$__compilerTemp1 .= '
			';
	if ($__vars['extras']['share_this_item']) {
		$__compilerTemp1 .= '
				<li class="u-flexStretch"><a href="' . $__templater->func('link', array('showcase', $__vars['item'], ), true) . '"
					class="item-share"
					data-xf-init="share-tooltip"
					data-href="' . $__templater->func('link', array('showcase/share', $__vars['item'], ), true) . '"
					aria-label="' . $__templater->filter('Share', array(array('for_attr', array()),), true) . '"
					rel="nofollow">
					' . $__templater->fontAwesome('fa-share-alt', array(
		)) . '
				</a></li>
			';
	}
	$__compilerTemp1 .= '
		';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '										
		<ul class="listInline listInline--bullet">
		' . $__compilerTemp1 . '
		</ul>
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'items_footer' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'viewAllLink' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	<div class="carousel-footer">
		<a href="' . $__templater->escape($__vars['viewAllLink']) . '">' . 'View more items' . '</a>
	</div>
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

' . '

' . '


' . '

' . '

' . '

' . '

' . '

' . '

';
	return $__finalCompiled;
}
);
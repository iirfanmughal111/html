<?php
// FROM HASH: 40ce70defd2317e0b2b0ef6df307cb28
return array(
'macros' => array('buttons' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'findNew' => '!',
		'canInlineMod' => false,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	';
	if ($__vars['canInlineMod']) {
		$__finalCompiled .= '
		' . $__templater->callMacro('inline_mod_macros', 'button', array(), $__vars) . '
	';
	}
	$__finalCompiled .= '
	';
	if ($__vars['xf']['visitor']['user_id']) {
		$__finalCompiled .= '
		' . $__templater->button('
			' . 'Mark media viewed' . '
		', array(
			'href' => $__templater->func('link', array('media/mark-viewed', null, array('date' => $__vars['findNew']['cache_date'], ), ), false),
			'class' => 'button--link',
			'overlay' => 'true',
		), '', array(
		)) . '
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'results' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'findNew' => '!',
		'mediaItems' => '!',
		'prevPage' => '',
		'nextPage' => '',
		'latestRoute' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if ($__vars['findNew']['result_count']) {
		$__finalCompiled .= '
		<div class="block-body">
			' . $__templater->callMacro('xfmg_media_list_macros', 'media_list', array(
			'mediaItems' => $__vars['mediaItems'],
			'prevPage' => $__vars['prevPage'],
			'nextPage' => $__vars['nextPage'],
			'setupLightbox' => $__vars['xf']['options']['xfmgLightboxNavigation'],
		), $__vars) . '
		</div>
	';
	} else {
		$__finalCompiled .= '
		<div class="block-row">
			';
		if ($__vars['xf']['visitor']['user_id'] AND (($__vars['findNew']['filters']['unviewed'] AND ($__templater->func('count', array($__vars['findNew']['filters'], ), false) == 1)))) {
			$__finalCompiled .= '
				' . 'You have no unviewed media. You may <a href="' . $__templater->func('link', array($__vars['latestRoute'], null, array('skip' => 1, ), ), true) . '" rel="nofollow">view all latest media</a> instead.' . '
			';
		} else {
			$__finalCompiled .= '
				' . 'No results found.' . '
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
'filter_bar' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'findNew' => '!',
		'rerunRoute' => '!',
		'rerunData' => null,
		'rerunQuery' => array(),
		'filterRoute' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	';
	if ($__vars['xf']['visitor']['user_id']) {
		$__finalCompiled .= '
		<div class="block-filterBar">
			<div class="filterBar">
				';
		$__compilerTemp1 = '';
		$__compilerTemp1 .= '
							' . '
							';
		if ($__vars['findNew']['filters']['unviewed']) {
			$__compilerTemp1 .= '
								<li><a href="' . $__templater->func('link', array($__vars['rerunRoute'], $__vars['rerunData'], $__vars['rerunQuery'] + array('remove' => 'unviewed', ), ), true) . '"
									class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Remove this filter', array(array('for_attr', array()),), true) . '">
									<span class="filterBar-filterToggle-label">' . 'Show only' . $__vars['xf']['language']['label_separator'] . '</span>
									' . 'Unviewed' . '</a></li>
							';
		}
		$__compilerTemp1 .= '
							';
		if ($__vars['findNew']['filters']['watched']) {
			$__compilerTemp1 .= '
								<li><a href="' . $__templater->func('link', array($__vars['rerunRoute'], $__vars['rerunData'], $__vars['rerunQuery'] + array('remove' => 'watched', ), ), true) . '"
									class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Remove this filter', array(array('for_attr', array()),), true) . '">
									<span class="filterBar-filterToggle-label">' . 'Show only' . $__vars['xf']['language']['label_separator'] . '</span>
									' . 'Watched' . '</a></li>
							';
		}
		$__compilerTemp1 .= '
							';
		if ($__vars['findNew']['filters']['own']) {
			$__compilerTemp1 .= '
								<li><a href="' . $__templater->func('link', array($__vars['rerunRoute'], $__vars['rerunData'], $__vars['rerunQuery'] + array('remove' => 'own', ), ), true) . '"
									class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Remove this filter', array(array('for_attr', array()),), true) . '">
									<span class="filterBar-filterToggle-label">' . 'Show only' . $__vars['xf']['language']['label_separator'] . '</span>
									' . 'Own' . '</a></li>
							';
		}
		$__compilerTemp1 .= '
							' . '
						';
		if (strlen(trim($__compilerTemp1)) > 0) {
			$__finalCompiled .= '
					<ul class="filterBar-filters">
						' . $__compilerTemp1 . '
					</ul>
				';
		}
		$__finalCompiled .= '

				<a class="filterBar-menuTrigger" data-xf-click="menu" role="button" tabindex="0" aria-expanded="false" aria-haspopup="true">' . 'Filters' . '</a>
				' . $__templater->callMacro(null, 'filter_menu', array(
			'findNew' => $__vars['findNew'],
			'submitRoute' => $__vars['filterRoute'],
		), $__vars) . '
			</div>
		</div>
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'filter_menu' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'findNew' => '!',
		'submitRoute' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<div class="menu" data-menu="menu" aria-hidden="true">
		<div class="menu-content">
			<h4 class="menu-header">' . 'Show only' . $__vars['xf']['language']['label_separator'] . '</h4>
			' . $__templater->form('
				<div class="menu-row">
					' . $__templater->formCheckBox(array(
	), array(array(
		'name' => 'unviewed',
		'selected' => $__vars['findNew']['filters']['unviewed'],
		'label' => 'Unviewed media',
		'_type' => 'option',
	),
	array(
		'name' => 'watched',
		'selected' => $__vars['findNew']['filters']['watched'],
		'label' => 'Watched media',
		'_type' => 'option',
	),
	array(
		'name' => 'own',
		'selected' => $__vars['findNew']['filters']['own'],
		'label' => 'Your media',
		'_type' => 'option',
	))) . '
				</div>
				' . '

				' . $__templater->callMacro('filter_macros', 'find_new_filter_footer', array(), $__vars) . '
			', array(
		'action' => $__templater->func('link', array($__vars['submitRoute'], ), false),
	)) . '
		</div>
	</div>
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('New media');
	$__templater->pageParams['pageNumber'] = $__vars['page'];
	$__finalCompiled .= '

';
	$__compilerTemp1 = $__vars;
	$__compilerTemp1['pageSelected'] = $__templater->preEscaped('xfmg_new_media');
	$__templater->wrapTemplate('whats_new_wrapper', $__compilerTemp1);
	$__finalCompiled .= '

';
	$__compilerTemp2 = '';
	if ($__templater->method($__vars['xf']['visitor'], 'canCreateThread', array())) {
		$__compilerTemp2 .= '
		' . $__templater->button('
			' . 'Add media' . $__vars['xf']['language']['ellipsis'] . '
		', array(
			'href' => $__templater->func('link', array('media/add', ), false),
			'class' => 'button--cta',
			'icon' => 'add',
			'data-xf-click' => 'overlay',
		), '', array(
		)) . '
	';
	}
	$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__compilerTemp2 . '
');
	$__finalCompiled .= '

';
	if ($__vars['canInlineMod']) {
		$__finalCompiled .= '
	';
		$__templater->includeJs(array(
			'src' => 'xf/inline_mod.js',
			'min' => '1',
		));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

<div class="block" data-xf-init="' . ($__vars['canInlineMod'] ? 'inline-mod' : '') . '" data-type="xfmg_media" data-href="' . $__templater->func('link', array('inline-mod', ), true) . '">
	';
	if ($__vars['findNew']['result_count']) {
		$__finalCompiled .= '
		<div class="block-outer">';
		$__compilerTemp3 = '';
		$__compilerTemp4 = '';
		$__compilerTemp4 .= '
							' . $__templater->callMacro(null, 'buttons', array(
			'findNew' => $__vars['findNew'],
			'canInlineMod' => $__vars['canInlineMod'],
		), $__vars) . '
						';
		if (strlen(trim($__compilerTemp4)) > 0) {
			$__compilerTemp3 .= '
				<div class="block-outer-opposite">
					<div class="buttonGroup">
						' . $__compilerTemp4 . '
					</div>
				</div>
			';
		}
		$__finalCompiled .= $__templater->func('trim', array('

			' . $__templater->func('page_nav', array(array(
			'page' => $__vars['page'],
			'total' => $__vars['findNew']['result_count'],
			'link' => 'whats-new/media',
			'data' => $__vars['findNew'],
			'wrapperclass' => 'block-outer-main',
			'perPage' => $__vars['perPage'],
		))) . '

			' . $__compilerTemp3 . '

		'), false) . '</div>
	';
	}
	$__finalCompiled .= '

	<div class="block-container">
		' . $__templater->callMacro(null, 'filter_bar', array(
		'findNew' => $__vars['findNew'],
		'rerunRoute' => 'whats-new/media',
		'rerunData' => $__vars['findNew'],
		'filterRoute' => 'whats-new/media',
	), $__vars) . '

		' . $__templater->callMacro(null, 'results', array(
		'findNew' => $__vars['findNew'],
		'mediaItems' => $__vars['media'],
		'prevPage' => $__vars['prevPage'],
		'nextPage' => $__vars['nextPage'],
		'latestRoute' => 'whats-new/media',
	), $__vars) . '
	</div>

	';
	if ($__vars['findNew']['result_count']) {
		$__finalCompiled .= '
		<div class="block-outer block-outer--after">
			' . $__templater->func('page_nav', array(array(
			'page' => $__vars['page'],
			'total' => $__vars['findNew']['result_count'],
			'link' => 'whats-new/media',
			'data' => $__vars['findNew'],
			'wrapperclass' => 'block-outer-main',
			'perPage' => $__vars['perPage'],
		))) . '
			' . $__templater->func('show_ignored', array(array(
			'wrapperclass' => 'block-outer-opposite',
		))) . '
		</div>
	';
	}
	$__finalCompiled .= '
</div>

' . '

' . '

' . '

';
	return $__finalCompiled;
}
);
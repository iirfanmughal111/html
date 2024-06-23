<?php
// FROM HASH: f0a470733d3157f97ae48e70a188fca5
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
			' . 'Mark items read' . '
		', array(
			'href' => $__templater->func('link', array('showcase/mark-read', null, array('date' => $__vars['findNew']['cache_date'], ), ), false),
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
		'items' => '!',
		'latestRoute' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if ($__vars['findNew']['result_count']) {
		$__finalCompiled .= '
		<div class="structItemContainer">
			';
		if ($__templater->isTraversable($__vars['items'])) {
			foreach ($__vars['items'] AS $__vars['item']) {
				$__finalCompiled .= '
				' . $__templater->callMacro('xa_sc_item_list_macros', 'list_view_layout', array(
					'item' => $__vars['item'],
				), $__vars) . '
			';
			}
		}
		$__finalCompiled .= '
		</div>
	';
	} else {
		$__finalCompiled .= '
		<div class="block-row">
			';
		if ($__vars['xf']['visitor']['user_id'] AND ($__vars['findNew']['filters']['unread'] AND ($__templater->func('count', array($__vars['findNew']['filters'], ), false) == 1))) {
			$__finalCompiled .= '
				' . 'You have no unread items. You may <a href="' . $__templater->func('link', array($__vars['latestRoute'], null, array('skip' => 1, ), ), true) . '" rel="nofollow">view all latest items</a> instead.' . '
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
		if ($__vars['findNew']['filters']['unread']) {
			$__compilerTemp1 .= '
								<li><a href="' . $__templater->func('link', array($__vars['rerunRoute'], $__vars['rerunData'], $__vars['rerunQuery'] + array('remove' => 'unread', ), ), true) . '"
									class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Remove this filter', array(array('for_attr', array()),), true) . '">
									<span class="filterBar-filterToggle-label">' . 'Show only' . $__vars['xf']['language']['label_separator'] . '</span>
									' . 'Unread' . '</a></li>
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
		'name' => 'unread',
		'selected' => $__vars['findNew']['filters']['unread'],
		'label' => 'Unread items',
		'_type' => 'option',
	),
	array(
		'name' => 'watched',
		'selected' => $__vars['findNew']['filters']['watched'],
		'label' => 'Watched content',
		'_type' => 'option',
	),
	array(
		'name' => 'own',
		'selected' => $__vars['findNew']['filters']['own'],
		'label' => 'Your items',
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
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('New showcase items');
	$__templater->pageParams['pageNumber'] = $__vars['page'];
	$__finalCompiled .= '

';
	$__compilerTemp1 = $__vars;
	$__compilerTemp1['pageSelected'] = $__templater->preEscaped('sc_new_item');
	$__templater->wrapTemplate('whats_new_wrapper', $__compilerTemp1);
	$__finalCompiled .= '

';
	if ($__templater->method($__vars['xf']['visitor'], 'canAddShowcaseItem', array())) {
		$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('Add item' . $__vars['xf']['language']['ellipsis'], array(
			'href' => $__templater->func('link', array('showcase/add', ), false),
			'class' => 'button--cta',
			'icon' => 'write',
			'overlay' => 'true',
		), '', array(
		)) . '
');
	}
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

<div class="block" data-xf-init="' . ($__vars['canInlineMod'] ? 'inline-mod' : '') . '" data-type="sc_item" data-href="' . $__templater->func('link', array('inline-mod', ), true) . '">
	';
	if ($__vars['findNew']['result_count']) {
		$__finalCompiled .= '
		<div class="block-outer">
			' . $__templater->func('page_nav', array(array(
			'page' => $__vars['page'],
			'total' => $__vars['findNew']['result_count'],
			'link' => 'whats-new/showcase-items',
			'data' => $__vars['findNew'],
			'wrapperclass' => 'block-outer-main',
			'perPage' => $__vars['perPage'],
		))) . '

			';
		$__compilerTemp2 = '';
		$__compilerTemp2 .= '
							' . $__templater->callMacro(null, 'buttons', array(
			'findNew' => $__vars['findNew'],
			'canInlineMod' => $__vars['canInlineMod'],
		), $__vars) . '
						';
		if (strlen(trim($__compilerTemp2)) > 0) {
			$__finalCompiled .= '
				<div class="block-outer-opposite">
					<div class="buttonGroup">
						' . $__compilerTemp2 . '
					</div>
				</div>
			';
		}
		$__finalCompiled .= '
		</div>
	';
	}
	$__finalCompiled .= '

	<div class="block-container">
		' . $__templater->callMacro(null, 'filter_bar', array(
		'findNew' => $__vars['findNew'],
		'rerunRoute' => 'whats-new/showcase-items',
		'rerunData' => $__vars['findNew'],
		'filterRoute' => 'whats-new/showcase-items',
	), $__vars) . '

		' . $__templater->callMacro(null, 'results', array(
		'findNew' => $__vars['findNew'],
		'items' => $__vars['items'],
		'latestRoute' => 'whats-new/showcase-items',
	), $__vars) . '
	</div>

	';
	if ($__vars['findNew']['result_count']) {
		$__finalCompiled .= '
		<div class="block-outer block-outer--after">
			' . $__templater->func('page_nav', array(array(
			'page' => $__vars['page'],
			'total' => $__vars['findNew']['result_count'],
			'link' => 'whats-new/showcase-items',
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
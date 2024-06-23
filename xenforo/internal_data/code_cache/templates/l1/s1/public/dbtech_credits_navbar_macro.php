<?php
// FROM HASH: 830e926646efee72a7c738b9cc516e37
return array(
'macros' => array('navbar' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'section' => '!',
		'nav' => '',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if (($__vars['nav'] AND ($__templater->method($__vars['xf']['visitor'], 'canViewDbtechCredits', array()) AND (($__vars['xf']['options']['dbtech_credits_navbar']['enabled'] == 2) AND ($__vars['xf']['options']['dbtech_credits_navbar']['right_position'] == $__vars['section']))))) {
		$__finalCompiled .= '

		' . $__templater->callMacro(null, 'nav_link', array(
			'navId' => 'dbtech-credits',
			'nav' => $__vars['nav'],
			'titleHtml' => ((!$__vars['xf']['options']['dbtech_credits_navbar']['right_text']) ? '' : null),
			'class' => 'p-navgroup-link--dbtech-credits',
		), $__vars) . '

		' . '
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'nav_link' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'navId' => '!',
		'nav' => '!',
		'titleHtml' => null,
		'menuTitle' => true,
		'shortcut' => false,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__vars['tag'] = ($__vars['nav']['href'] ? 'a' : 'span');
	$__finalCompiled .= '

	<' . $__templater->escape($__vars['tag']) . ' ' . ($__vars['nav']['href'] ? (('href="' . $__templater->escape($__vars['nav']['href'])) . '"') : '') . '
		class="p-navgroup-link p-navgroup-link--iconic p-navgroup-link--' . $__templater->escape($__vars['navId']) . ' js-badge--' . $__templater->escape($__vars['navId']) . ' badgeContainer' . ($__vars['nav']['counter'] ? ' badgeContainer--highlighted' : '') . ' ' . $__templater->escape($__vars['nav']['attributes']['class']) . '"
		data-badge="' . $__templater->filter($__vars['nav']['counter'], array(array('number', array()),), true) . '"
		' . ($__vars['nav']['children'] ? 'data-xf-click="menu"' : '') . '
		' . (($__vars['shortcut'] !== false) ? (('data-xf-key="' . $__templater->escape($__vars['shortcut'])) . '"') : '') . '
		data-menu-pos-ref="< .p-navgroup"
		aria-expanded="false"
		aria-haspopup="true">
			<i aria-hidden="true"></i>
			<span class="p-navgroup-linkText">' . (($__vars['titleHtml'] !== null) ? $__templater->filter($__vars['titleHtml'], array(array('raw', array()),), true) : $__templater->escape($__vars['nav']['title'])) . '</span>
	</' . $__templater->escape($__vars['tag']) . '>
	';
	if ($__vars['nav']['children']) {
		$__finalCompiled .= '
		<div class="menu menu--structural menu--medium" data-menu="menu" aria-hidden="true">
			<div class="menu-content">
				';
		if ($__vars['menuTitle']) {
			$__finalCompiled .= '
					<h3 class="menu-header">
						';
			if ($__vars['nav']['href']) {
				$__finalCompiled .= '
							<a href="' . $__templater->escape($__vars['nav']['href']) . '">' . $__templater->escape($__vars['nav']['title']) . '</a>
						';
			} else {
				$__finalCompiled .= '
							' . $__templater->escape($__vars['nav']['title']) . '
						';
			}
			$__finalCompiled .= '
					</h3>
				';
		}
		$__finalCompiled .= '
				';
		if ($__templater->isTraversable($__vars['nav']['children'])) {
			foreach ($__vars['nav']['children'] AS $__vars['childNavId'] => $__vars['child']) {
				$__finalCompiled .= '
					' . $__templater->callMacro('PAGE_CONTAINER', 'nav_menu_entry', array(
					'navId' => $__vars['childNavId'],
					'nav' => $__vars['child'],
				), $__vars) . '
				';
			}
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
'nav_link_old' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'navId' => '!',
		'nav' => '!',
		'titleHtml' => null,
		'class' => '',
		'icon' => '',
		'menuTitle' => true,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if ($__vars['icon']) {
		$__finalCompiled .= '
		';
		$__vars['nav']['icon'] = $__vars['icon'];
		$__finalCompiled .= '
	';
	}
	$__finalCompiled .= '

	';
	$__vars['navTitle'] = $__vars['nav']['title'];
	$__finalCompiled .= '
	';
	if ($__vars['titleHtml'] !== null) {
		$__finalCompiled .= '
		';
		$__vars['nav']['title'] = '';
		$__finalCompiled .= '
	';
	}
	$__finalCompiled .= '

	';
	if ($__vars['nav']['href']) {
		$__finalCompiled .= '
		';
		if ($__vars['nav']['children']) {
			$__finalCompiled .= '
			';
			$__vars['nav']['attributes']['data-xf-click'] = 'menu';
			$__finalCompiled .= '
		';
		}
		$__finalCompiled .= '
		' . $__templater->callMacro('PAGE_CONTAINER', 'nav_link', array(
			'navId' => $__vars['navId'],
			'nav' => $__vars['nav'],
			'class' => 'p-navgroup-link p-navgroup-link--iconic ' . $__vars['class'],
		), $__vars) . '
	';
	} else if ($__vars['nav']['children']) {
		$__finalCompiled .= '
		<a data-xf-click="menu"
		   class="p-navgroup-link p-navgroup-link--iconic ' . $__templater->escape($__vars['class']) . '"
		   role="button"
		   tabindex="0"
		   aria-expanded="false"
		   aria-haspopup="true">
			' . $__templater->callMacro('PAGE_CONTAINER', 'nav_link', array(
			'navId' => $__vars['navId'],
			'nav' => $__vars['nav'],
		), $__vars) . '
		</a>
	';
	} else {
		$__finalCompiled .= '
		' . $__templater->callMacro('PAGE_CONTAINER', 'nav_link', array(
			'navId' => $__vars['navId'],
			'nav' => $__vars['nav'],
			'class' => 'p-navgroup-link p-navgroup-link--iconic ' . $__vars['class'],
		), $__vars) . '
	';
	}
	$__finalCompiled .= '

	';
	if ($__vars['nav']['children']) {
		$__finalCompiled .= '
		<div class="menu menu--structural" data-menu="menu" aria-hidden="true">
			<div class="menu-content">
				';
		if ($__vars['menuTitle']) {
			$__finalCompiled .= '
					<h3 class="menu-header">
						';
			if ($__vars['nav']['href']) {
				$__finalCompiled .= '
							<a href="' . $__templater->escape($__vars['nav']['href']) . '">' . $__templater->escape($__vars['navTitle']) . '</a>
						';
			} else {
				$__finalCompiled .= '
							' . $__templater->escape($__vars['navTitle']) . '
						';
			}
			$__finalCompiled .= '
					</h3>
				';
		}
		$__finalCompiled .= '
				';
		if ($__templater->isTraversable($__vars['nav']['children'])) {
			foreach ($__vars['nav']['children'] AS $__vars['childNavId'] => $__vars['child']) {
				$__finalCompiled .= '
					' . $__templater->callMacro('PAGE_CONTAINER', 'nav_menu_entry', array(
					'navId' => $__vars['childNavId'],
					'nav' => $__vars['child'],
				), $__vars) . '
				';
			}
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
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

' . '

';
	return $__finalCompiled;
}
);
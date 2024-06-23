<?php
// FROM HASH: acdb9c56c2d4fc198e7f6932f10deed1
return array(
'macros' => array('header' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'series' => '!',
		'titleHtml' => null,
		'showMeta' => true,
		'metaHtml' => null,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	';
	$__compilerTemp1 = '';
	if ((!$__vars['series']['community_series']) OR (($__vars['series']['community_series'] AND $__vars['series']['icon_date']))) {
		$__compilerTemp1 .= '
				<span class="contentRow-figure">
					';
		if ($__vars['series']['icon_date']) {
			$__compilerTemp1 .= '
						<span class="contentRow-figureIcon">' . $__templater->func('sc_series_icon', array($__vars['series'], 'm', ), true) . '</span>
					';
		} else {
			$__compilerTemp1 .= '
						' . $__templater->func('avatar', array($__vars['series']['User'], 's', false, array(
			))) . '
					';
		}
		$__compilerTemp1 .= '
				</span>
			';
	}
	$__compilerTemp2 = '';
	if ($__vars['series']['community_series']) {
		$__compilerTemp2 .= '
								' . $__templater->fontAwesome('fa-users', array(
			'title' => $__templater->filter('Community series', array(array('for_attr', array()),), false),
		)) . '
								<span class="u-srOnly">' . 'Community series' . '</span>

								' . 'Community series' . '
							';
	} else {
		$__compilerTemp2 .= '
								' . $__templater->fontAwesome('fa-user', array(
			'title' => $__templater->filter('Author', array(array('for_attr', array()),), false),
		)) . '
								<span class="u-srOnly">' . 'Author' . '</span>
								' . $__templater->func('username_link', array($__vars['series']['User'], false, array(
			'defaultname' => $__vars['series']['User']['username'],
			'class' => 'u-concealed',
		))) . '
							';
	}
	$__compilerTemp3 = '';
	if ($__vars['series']['last_part_date'] AND ($__vars['series']['last_part_date'] > $__vars['series']['create_date'])) {
		$__compilerTemp3 .= '								
							<li>
								' . $__templater->fontAwesome('fa-clock', array(
			'title' => $__templater->filter('Last update', array(array('for_attr', array()),), false),
		)) . '
								<span class="u-concealed">' . 'Updated' . '</span>

								<a href="' . $__templater->func('link', array('showcase/series', $__vars['series'], ), true) . '" class="u-concealed">' . $__templater->func('date_dynamic', array($__vars['series']['last_part_date'], array(
		))) . '</a>
							</li>
						';
	}
	$__compilerTemp4 = '';
	if ($__vars['series']['item_count']) {
		$__compilerTemp4 .= '
							<li>' . 'Items' . ': ' . $__templater->filter($__vars['series']['item_count'], array(array('number', array()),), true) . '</li>
						';
	}
	$__compilerTemp5 = '';
	if ($__vars['xf']['options']['enableTagging'] AND (($__templater->method($__vars['series'], 'canEditTags', array()) OR $__vars['series']['tags']))) {
		$__compilerTemp5 .= '
							<li>

								' . $__templater->callMacro('tag_macros', 'list', array(
			'tags' => $__vars['series']['tags'],
			'tagList' => 'tagList--series-' . $__vars['series']['series_id'],
			'editLink' => ($__templater->method($__vars['series'], 'canEditTags', array()) ? $__templater->func('link', array('showcase/series/tags', $__vars['series'], ), false) : ''),
		), $__vars) . '
							</li>
						';
	}
	$__compilerTemp6 = '';
	if ($__vars['series']['Featured']) {
		$__compilerTemp6 .= '
							<li><span class="label label--accent">' . 'Featured' . '</span></li>
						';
	}
	$__templater->setPageParam('headerHtml', '
		<div class="contentRow contentRow--hideFigureNarrow">
			' . $__compilerTemp1 . '
			<div class="contentRow-main">
				<div class="p-title">
					<h1 class="p-title-value">
						' . $__templater->escape($__vars['series']['title']) . '
					</h1>
				</div>

				<div class="p-description">
					<ul class="listInline listInline--bullet">
						<li>
							' . $__compilerTemp2 . '
						</li>
						<li>
							' . $__templater->fontAwesome('fa-clock', array(
		'title' => $__templater->filter('Create date', array(array('for_attr', array()),), false),
	)) . '
							<span class="u-srOnly">' . 'Create date' . '</span>

							<a href="' . $__templater->func('link', array('showcase/series', $__vars['series'], ), true) . '" class="u-concealed">' . $__templater->func('date_dynamic', array($__vars['series']['create_date'], array(
	))) . '</a>
						</li>
						' . $__compilerTemp3 . '
						' . $__compilerTemp4 . '
						' . $__compilerTemp5 . '					
						' . $__compilerTemp6 . '
					</ul>
				</div>
			</div>
		</div>
	');
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'status' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'series' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
				';
	if ($__vars['series']['series_state'] == 'deleted') {
		$__compilerTemp1 .= '
					<dd class="blockStatus-message blockStatus-message--deleted">
						' . $__templater->callMacro('deletion_macros', 'notice', array(
			'log' => $__vars['series']['DeletionLog'],
		), $__vars) . '
					</dd>
				';
	} else if ($__vars['series']['series_state'] == 'moderated') {
		$__compilerTemp1 .= '
					<dd class="blockStatus-message blockStatus-message--moderated">
						' . 'Awaiting approval before being displayed publicly.' . '
					</dd>
				';
	}
	$__compilerTemp1 .= '
				';
	if ($__vars['series']['warning_message']) {
		$__compilerTemp1 .= '
					<dd class="blockStatus-message blockStatus-message--warning">
						' . $__templater->escape($__vars['series']['warning_message']) . '
					</dd>
				';
	}
	$__compilerTemp1 .= '
				';
	if ($__templater->method($__vars['series'], 'isIgnored', array())) {
		$__compilerTemp1 .= '
					<dd class="blockStatus-message blockStatus-message--ignored">
						' . 'You are ignoring content by this member.' . '
					</dd>
				';
	}
	$__compilerTemp1 .= '
			';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
		<dl class="blockStatus blockStatus--standalone">
			<dt>' . 'Status' . '</dt>
			' . $__compilerTemp1 . '
		</dl>
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'tabs' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'series' => '!',
		'selected' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<div class="tabs tabs--standalone">
		<div class="hScroller" data-xf-init="h-scroller">
			<span class="hScroller-scroll">
				<a class="tabs-tab ' . (($__vars['selected'] == 'overview') ? 'is-active' : '') . '" href="' . $__templater->func('link', array('showcase/series', $__vars['series'], ), true) . '">' . 'Items' . '</a>
				<a class="tabs-tab ' . (($__vars['selected'] == 'details') ? 'is-active' : '') . '" href="' . $__templater->func('link', array('showcase/series/details', $__vars['series'], ), true) . '">' . 'Series details' . '</a>
			</span>
		</div>
	</div>
';
	return $__finalCompiled;
}
),
'action_buttons' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'series' => '!',
		'showAddItemButton' => false,
		'canInlineMod' => false,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	';
	if ($__templater->method($__vars['series'], 'canAddItemToSeries', array()) AND $__vars['showAddItemButton']) {
		$__finalCompiled .= '
		' . $__templater->button('
			' . 'Add item to series' . '
		', array(
			'href' => $__templater->func('link', array('showcase/series/add-item', $__vars['series'], ), false),
			'overlay' => 'true',
		), '', array(
		)) . '
	';
	}
	$__finalCompiled .= '

	';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
			';
	if ($__vars['canInlineMod']) {
		$__compilerTemp1 .= '
				' . $__templater->callMacro('inline_mod_macros', 'button', array(), $__vars) . '
			';
	}
	$__compilerTemp1 .= '

			';
	if ($__templater->method($__vars['series'], 'canUndelete', array()) AND ($__vars['series']['series_state'] == 'deleted')) {
		$__compilerTemp1 .= '
				' . $__templater->button('
					' . 'Undelete' . '
				', array(
			'href' => $__templater->func('link', array('showcase/series/undelete', $__vars['series'], ), false),
			'class' => 'button--link',
			'overlay' => 'true',
		), '', array(
		)) . '
			';
	}
	$__compilerTemp1 .= '
			';
	if ($__templater->method($__vars['series'], 'canApproveUnapprove', array()) AND ($__vars['series']['series_state'] == 'moderated')) {
		$__compilerTemp1 .= '
				' . $__templater->button('
					' . 'Approve' . '
				', array(
			'href' => $__templater->func('link', array('showcase/series/approve', $__vars['series'], array('t' => $__templater->func('csrf_token', array(), false), ), ), false),
			'class' => 'button--link',
		), '', array(
		)) . '
			';
	}
	$__compilerTemp1 .= '

			';
	if ($__templater->method($__vars['series'], 'canWatch', array())) {
		$__compilerTemp1 .= '
				';
		$__compilerTemp2 = '';
		if ($__vars['series']['Watch'][$__vars['xf']['visitor']['user_id']]) {
			$__compilerTemp2 .= 'Unwatch';
		} else {
			$__compilerTemp2 .= 'Watch';
		}
		$__compilerTemp1 .= $__templater->button('
					' . $__compilerTemp2 . '
				', array(
			'href' => $__templater->func('link', array('showcase/series/watch', $__vars['series'], ), false),
			'class' => 'button--link',
			'data-xf-click' => 'switch-overlay',
			'data-sk-watch' => 'Watch',
			'data-sk-unwatch' => 'Unwatch',
		), '', array(
		)) . '
			';
	}
	$__compilerTemp1 .= '
			' . $__templater->callMacro('bookmark_macros', 'button', array(
		'content' => $__vars['series'],
		'confirmUrl' => $__templater->func('link', array('showcase/series/bookmark', $__vars['series'], ), false),
	), $__vars) . '
			
			';
	$__compilerTemp3 = '';
	$__compilerTemp3 .= '
								' . '
								';
	if ($__templater->method($__vars['series'], 'canFeatureUnfeature', array())) {
		$__compilerTemp3 .= '
									<a href="' . $__templater->func('link', array('showcase/series/quick-feature', $__vars['series'], ), true) . '"
										class="menu-linkRow"
										data-xf-click="switch"
										data-menu-closer="true">

										';
		if ($__vars['series']['Featured']) {
			$__compilerTemp3 .= '
											' . 'Unfeature series' . '
										';
		} else {
			$__compilerTemp3 .= '
											' . 'Feature series' . '
										';
		}
		$__compilerTemp3 .= '
									</a>
								';
	}
	$__compilerTemp3 .= '
								';
	if ($__templater->method($__vars['series'], 'canEdit', array())) {
		$__compilerTemp3 .= '
									<a href="' . $__templater->func('link', array('showcase/series/edit', $__vars['series'], ), true) . '" class="menu-linkRow">' . 'Edit series' . '</a>
								';
	}
	$__compilerTemp3 .= '
								';
	if ($__templater->method($__vars['series'], 'canEditIcon', array())) {
		$__compilerTemp3 .= '
									<a href="' . $__templater->func('link', array('showcase/series/editIcon', $__vars['series'], ), true) . '" data-xf-click="overlay" class="menu-linkRow">' . 'Edit series icon' . '</a>
								';
	}
	$__compilerTemp3 .= '
								';
	if ($__templater->method($__vars['series'], 'canDelete', array('soft', ))) {
		$__compilerTemp3 .= '
									<a href="' . $__templater->func('link', array('showcase/series/delete', $__vars['series'], ), true) . '" data-xf-click="overlay" class="menu-linkRow">' . 'Delete series' . '</a>
								';
	}
	$__compilerTemp3 .= '
								';
	if ($__templater->method($__vars['series'], 'canReassign', array())) {
		$__compilerTemp3 .= '
									<a href="' . $__templater->func('link', array('showcase/series/reassign', $__vars['series'], ), true) . '" data-xf-click="overlay" class="menu-linkRow">' . 'Reassign series' . '</a>
								';
	}
	$__compilerTemp3 .= '
								';
	if ($__templater->method($__vars['series'], 'canCreatePoll', array())) {
		$__compilerTemp3 .= '
									<a href="' . $__templater->func('link', array('showcase/series/poll/create', $__vars['series'], ), true) . '" data-xf-click="overlay" class="menu-linkRow">' . 'Create poll' . '</a>
								';
	}
	$__compilerTemp3 .= '
								';
	if ($__templater->method($__vars['series'], 'canViewModeratorLogs', array())) {
		$__compilerTemp3 .= '
									<a href="' . $__templater->func('link', array('showcase/series/moderator-actions', $__vars['series'], ), true) . '" data-xf-click="overlay" class="menu-linkRow">' . 'Moderator actions' . '</a>
								';
	}
	$__compilerTemp3 .= '											
								' . '
								';
	if ($__templater->method($__vars['series'], 'canUseInlineModeration', array())) {
		$__compilerTemp3 .= '
									<div class="menu-footer"
										data-xf-init="inline-mod"
										data-type="sc_series"
										data-href="' . $__templater->func('link', array('inline-mod', ), true) . '"
										data-toggle=".js-seriesInlineModToggle">
										' . $__templater->formCheckBox(array(
		), array(array(
			'class' => 'js-seriesInlineModToggle',
			'value' => $__vars['series']['series_id'],
			'label' => 'Select for moderation',
			'_type' => 'option',
		))) . '
									</div>
									';
		$__templater->includeJs(array(
			'src' => 'xf/inline_mod.js',
			'min' => '1',
		));
		$__compilerTemp3 .= '
								';
	}
	$__compilerTemp3 .= '
								' . '
							';
	if (strlen(trim($__compilerTemp3)) > 0) {
		$__compilerTemp1 .= '
				<div class="buttonGroup-buttonWrapper">
					' . $__templater->button('&#8226;&#8226;&#8226;', array(
			'class' => 'button--link menuTrigger',
			'data-xf-click' => 'menu',
			'aria-expanded' => 'false',
			'aria-haspopup' => 'true',
			'title' => $__templater->filter('More options', array(array('for_attr', array()),), false),
		), '', array(
		)) . '
					<div class="menu" data-menu="menu" aria-hidden="true">
						<div class="menu-content">
							<h4 class="menu-header">' . 'More options' . '</h4>
							' . $__compilerTemp3 . '
						</div>
					</div>
				</div>
			';
	}
	$__compilerTemp1 .= '
		';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
		<div class="buttonGroup">
		' . $__compilerTemp1 . '
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

' . '

';
	return $__finalCompiled;
}
);
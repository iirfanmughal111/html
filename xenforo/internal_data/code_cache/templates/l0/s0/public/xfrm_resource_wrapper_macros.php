<?php
// FROM HASH: 916432e14f505e9089a9dc9ddd7dace6
return array(
'macros' => array('header' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'resource' => '!',
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
	if ($__vars['xf']['options']['xfrmAllowIcons']) {
		$__compilerTemp1 .= '
					' . $__templater->func('resource_icon', array($__vars['resource'], 's', ), true) . '
				';
	} else {
		$__compilerTemp1 .= '
					' . $__templater->func('avatar', array($__vars['resource']['User'], 's', false, array(
		))) . '
				';
	}
	$__compilerTemp2 = '';
	if ($__vars['titleHtml'] !== null) {
		$__compilerTemp2 .= '
							' . $__templater->filter($__vars['titleHtml'], array(array('raw', array()),), true) . '
						';
	} else {
		$__compilerTemp2 .= '
							' . $__templater->func('prefix', array('resource', $__vars['resource'], ), true) . $__templater->escape($__vars['resource']['title']) . '
						';
	}
	$__compilerTemp3 = '';
	if ($__templater->method($__vars['resource'], 'isVersioned', array())) {
		$__compilerTemp3 .= '
							<span class="u-muted">' . $__templater->escape($__vars['resource']['CurrentVersion']['version_string']) . '</span>
						';
	}
	$__compilerTemp4 = '';
	if ($__templater->method($__vars['resource'], 'isDownloadable', array())) {
		$__compilerTemp4 .= '
						<div class="p-title-pageAction">
							';
		if ($__templater->method($__vars['resource'], 'canDownload', array())) {
			$__compilerTemp4 .= '
								';
			if ($__templater->method($__vars['resource'], 'isExternalDownload', array())) {
				$__compilerTemp4 .= '
									' . $__templater->button('Go to download', array(
					'href' => $__templater->func('link', array('resources/download', $__vars['resource'], ), false),
					'target' => '_blank',
					'rel' => 'noopener',
					'data-xf-init' => 'tooltip',
					'class' => 'button--cta',
					'title' => $__templater->method($__vars['resource']['CurrentVersion'], 'getExternalDownloadDomain', array()),
					'icon' => 'redirect',
				), '', array(
				)) . '
								';
			} else {
				$__compilerTemp4 .= '
									' . $__templater->button('Download', array(
					'href' => $__templater->func('link', array('resources/download', $__vars['resource'], ), false),
					'target' => '_blank',
					'class' => 'button--cta',
					'data-xf-click' => (($__vars['resource']['CurrentVersion']['file_count'] > 1) ? 'overlay' : ''),
					'icon' => 'download',
				), '', array(
				)) . '
								';
			}
			$__compilerTemp4 .= '
							';
		} else {
			$__compilerTemp4 .= '
								<span class="button button--wrap is-disabled">' . 'No permission to download' . '</span>
							';
		}
		$__compilerTemp4 .= '
						</div>
					';
	} else if ($__templater->method($__vars['resource'], 'isExternalPurchasable', array())) {
		$__compilerTemp4 .= '
						<div class="p-title-pageAction">
							';
		if ($__templater->method($__vars['resource'], 'canDownload', array())) {
			$__compilerTemp4 .= '
								' . $__templater->button('
									' . 'Buy for ' . $__templater->filter($__vars['resource']['price'], array(array('currency', array($__vars['resource']['currency'], )),), true) . '' . '
								', array(
				'href' => $__vars['resource']['external_purchase_url'],
				'class' => 'button--cta',
				'icon' => 'purchase',
				'target' => '_blank',
				'rel' => 'noopener',
			), '', array(
			)) . '
							';
		} else {
			$__compilerTemp4 .= '
								<span class="button button--wrap is-disabled">' . 'No permission to buy (' . $__templater->filter($__vars['resource']['price'], array(array('currency', array($__vars['resource']['currency'], )),), true) . ')' . '</span>
							';
		}
		$__compilerTemp4 .= '
						</div>
					';
	}
	$__compilerTemp5 = '';
	if ($__vars['showMeta']) {
		$__compilerTemp5 .= '
					<div class="p-description">
						';
		if ($__vars['metaHtml'] !== null) {
			$__compilerTemp5 .= '
							' . $__templater->filter($__vars['metaHtml'], array(array('raw', array()),), true) . '
						';
		} else {
			$__compilerTemp5 .= '
							<ul class="listInline listInline--bullet">
								<li>
									' . $__templater->fontAwesome('fa-user', array(
				'title' => $__templater->filter('Author', array(array('for_attr', array()),), false),
			)) . '
									<span class="u-srOnly">' . 'Author' . '</span>

									' . $__templater->func('username_link', array($__vars['resource']['User'], false, array(
				'defaultname' => $__vars['resource']['username'],
				'class' => 'u-concealed',
			))) . '
								</li>
								<li>
									' . $__templater->fontAwesome('fa-clock', array(
				'title' => $__templater->filter('Creation date', array(array('for_attr', array()),), false),
			)) . '
									<span class="u-srOnly">' . 'Creation date' . '</span>

									<a href="' . $__templater->func('link', array('resources', $__vars['resource'], ), true) . '" class="u-concealed">' . $__templater->func('date_dynamic', array($__vars['resource']['resource_date'], array(
			))) . '</a>
								</li>
								';
			if ($__vars['xf']['options']['enableTagging'] AND ($__templater->method($__vars['resource'], 'canEditTags', array()) OR $__vars['resource']['tags'])) {
				$__compilerTemp5 .= '
									<li>

										' . $__templater->callMacro('tag_macros', 'list', array(
					'tags' => $__vars['resource']['tags'],
					'tagList' => 'tagList--resource-' . $__vars['resource']['resource_id'],
					'editLink' => ($__templater->method($__vars['resource'], 'canEditTags', array()) ? $__templater->func('link', array('resources/tags', $__vars['resource'], ), false) : ''),
				), $__vars) . '
									</li>
								';
			}
			$__compilerTemp5 .= '
								';
			if ($__vars['resource']['Featured']) {
				$__compilerTemp5 .= '
									<li><span class="label label--accent">' . 'Featured' . '</span></li>
								';
			}
			$__compilerTemp5 .= '
								';
			if ($__templater->method($__vars['resource'], 'isNonOwnerTeamMember', array())) {
				$__compilerTemp5 .= '
									<li><span class="label label--accent">' . 'Team member' . '</span></li>
								';
			}
			$__compilerTemp5 .= '
							</ul>
						';
		}
		$__compilerTemp5 .= '
					</div>
				';
	}
	$__templater->setPageParam('headerHtml', '
		<div class="contentRow contentRow--hideFigureNarrow">
			<span class="contentRow-figure">
				' . $__compilerTemp1 . '
			</span>
			<div class="contentRow-main">
				<div class="p-title">
					<h1 class="p-title-value">
						' . $__compilerTemp2 . '
						' . $__compilerTemp3 . '
					</h1>
					' . $__compilerTemp4 . '
				</div>
				' . $__compilerTemp5 . '
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
		'resource' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
				';
	if ($__vars['resource']['resource_state'] == 'deleted') {
		$__compilerTemp1 .= '
					<dd class="blockStatus-message blockStatus-message--deleted">
						' . $__templater->callMacro('deletion_macros', 'notice', array(
			'log' => $__vars['resource']['DeletionLog'],
		), $__vars) . '
					</dd>
				';
	} else if ($__vars['resource']['resource_state'] == 'moderated') {
		$__compilerTemp1 .= '
					<dd class="blockStatus-message blockStatus-message--moderated">
						' . 'Awaiting approval before being displayed publicly.' . '
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
		'resource' => '!',
		'selected' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
						';
	if ($__templater->method($__vars['resource'], 'hasExtraInfoTab', array())) {
		$__compilerTemp1 .= '
							<a class="tabs-tab ' . (($__vars['selected'] == 'extra') ? 'is-active' : '') . '" href="' . $__templater->func('link', array('resources/extra', $__vars['resource'], ), true) . '">' . 'Extra info' . '</a>
						';
	}
	$__compilerTemp1 .= '
						';
	$__compilerTemp2 = $__templater->method($__vars['resource'], 'getExtraFieldTabs', array());
	if ($__templater->isTraversable($__compilerTemp2)) {
		foreach ($__compilerTemp2 AS $__vars['fieldId'] => $__vars['fieldValue']) {
			$__compilerTemp1 .= '
							<a class="tabs-tab ' . (($__vars['selected'] == ('field_' . $__vars['fieldId'])) ? 'is-active' : '') . '" href="' . $__templater->func('link', array('resources/field', $__vars['resource'], array('field' => $__vars['fieldId'], ), ), true) . '">' . $__templater->escape($__vars['fieldValue']) . '</a>
						';
		}
	}
	$__compilerTemp1 .= '
						';
	if ($__vars['resource']['real_update_count']) {
		$__compilerTemp1 .= '
							<a class="tabs-tab ' . (($__vars['selected'] == 'updates') ? 'is-active' : '') . '" href="' . $__templater->func('link', array('resources/updates', $__vars['resource'], ), true) . '">' . 'Updates' . ' ' . $__templater->filter($__vars['resource']['real_update_count'], array(array('parens', array()),), true) . '</a>
						';
	}
	$__compilerTemp1 .= '
						';
	if ($__vars['resource']['real_review_count']) {
		$__compilerTemp1 .= '
							<a class="tabs-tab ' . (($__vars['selected'] == 'reviews') ? 'is-active' : '') . '" href="' . $__templater->func('link', array('resources/reviews', $__vars['resource'], ), true) . '">' . 'Reviews' . ' ' . $__templater->filter($__vars['resource']['real_review_count'], array(array('parens', array()),), true) . '</a>
						';
	}
	$__compilerTemp1 .= '
						';
	if ($__templater->method($__vars['resource'], 'isVersioned', array())) {
		$__compilerTemp1 .= '
							<a class="tabs-tab ' . (($__vars['selected'] == 'history') ? 'is-active' : '') . '" href="' . $__templater->func('link', array('resources/history', $__vars['resource'], ), true) . '">' . 'History' . '</a>
						';
	}
	$__compilerTemp1 .= '
						';
	if ($__templater->method($__vars['resource'], 'hasViewableDiscussion', array())) {
		$__compilerTemp1 .= '
							<a class="tabs-tab ' . (($__vars['selected'] == 'discussion') ? 'is-active' : '') . '" href="' . $__templater->func('link', array('threads', $__vars['resource']['Discussion'], ), true) . '">' . 'Discussion' . '</a>
						';
	}
	$__compilerTemp1 .= '
					';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
		<div class="tabs tabs--standalone">
			<div class="hScroller" data-xf-init="h-scroller">
				<span class="hScroller-scroll">
					<a class="tabs-tab ' . (($__vars['selected'] == 'overview') ? 'is-active' : '') . '" href="' . $__templater->func('link', array('resources', $__vars['resource'], ), true) . '">' . 'Overview' . '</a>
					' . $__compilerTemp1 . '
				</span>
			</div>
		</div>
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'action_buttons' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'resource' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	' . '
	';
	if ($__templater->method($__vars['resource'], 'canRate', array(false, )) OR $__templater->method($__vars['resource'], 'canRatePreReg', array())) {
		$__finalCompiled .= '
		' . $__templater->button('
			' . 'Leave a rating' . '
		', array(
			'href' => $__templater->func('link', array('resources/rate', $__vars['resource'], ), false),
			'overlay' => 'true',
		), '', array(
		)) . '
	';
	}
	$__finalCompiled .= '
	';
	if ($__templater->method($__vars['resource'], 'canReleaseUpdate', array())) {
		$__finalCompiled .= '
		' . $__templater->button('Post an update', array(
			'href' => $__templater->func('link', array('resources/post-update', $__vars['resource'], ), false),
		), '', array(
		)) . '
	';
	}
	$__finalCompiled .= '

	';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
			';
	if ($__templater->method($__vars['resource'], 'canUndelete', array()) AND ($__vars['resource']['resource_state'] == 'deleted')) {
		$__compilerTemp1 .= '
				' . $__templater->button('
					' . 'Undelete' . '
				', array(
			'href' => $__templater->func('link', array('resources/undelete', $__vars['resource'], ), false),
			'class' => 'button--link',
			'overlay' => 'true',
		), '', array(
		)) . '
			';
	}
	$__compilerTemp1 .= '
			';
	if ($__templater->method($__vars['resource'], 'canApproveUnapprove', array()) AND ($__vars['resource']['resource_state'] == 'moderated')) {
		$__compilerTemp1 .= '
				' . $__templater->button('
					' . 'Approve' . '
				', array(
			'href' => $__templater->func('link', array('resources/approve', $__vars['resource'], ), false),
			'class' => 'button--link',
			'overlay' => 'true',
		), '', array(
		)) . '
			';
	}
	$__compilerTemp1 .= '
			';
	if ($__templater->method($__vars['resource'], 'canWatch', array())) {
		$__compilerTemp1 .= '
				';
		$__compilerTemp2 = '';
		if ($__vars['resource']['Watch'][$__vars['xf']['visitor']['user_id']]) {
			$__compilerTemp2 .= '
						' . 'Unwatch' . '
					';
		} else {
			$__compilerTemp2 .= '
						' . 'Watch' . '
					';
		}
		$__compilerTemp1 .= $__templater->button('

					' . $__compilerTemp2 . '
				', array(
			'href' => $__templater->func('link', array('resources/watch', $__vars['resource'], ), false),
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
		'content' => $__vars['resource'],
		'confirmUrl' => $__templater->func('link', array('resources/bookmark', $__vars['resource'], ), false),
	), $__vars) . '

			';
	$__compilerTemp3 = '';
	$__compilerTemp3 .= '
								' . '
								';
	if ($__templater->method($__vars['resource'], 'canEdit', array())) {
		$__compilerTemp3 .= '
									<a href="' . $__templater->func('link', array('resources/edit', $__vars['resource'], ), true) . '" class="menu-linkRow">' . 'Edit resource' . '</a>
								';
	}
	$__compilerTemp3 .= '
								';
	if ($__templater->method($__vars['resource'], 'canEditIcon', array())) {
		$__compilerTemp3 .= '
									<a href="' . $__templater->func('link', array('resources/edit-icon', $__vars['resource'], ), true) . '" class="menu-linkRow" data-xf-click="overlay">' . 'Edit resource icon' . '</a>
								';
	}
	$__compilerTemp3 .= '
								';
	if ($__templater->method($__vars['resource'], 'canChangeResourceType', array())) {
		$__compilerTemp3 .= '
									<a href="' . $__templater->func('link', array('resources/change-type', $__vars['resource'], ), true) . '" class="menu-linkRow">' . 'Change resource type' . '</a>
								';
	}
	$__compilerTemp3 .= '
								';
	if ($__templater->method($__vars['resource'], 'canLeaveTeam', array())) {
		$__compilerTemp3 .= '
									<a href="' . $__templater->func('link', array('resources/leave-team', $__vars['resource'], ), true) . '" class="menu-linkRow" data-xf-click="overlay">
										' . 'Leave resource team' . '
									</a>
								';
	}
	$__compilerTemp3 .= '
								';
	if ($__templater->method($__vars['resource'], 'canManageTeamMembers', array())) {
		$__compilerTemp3 .= '
									<a href="' . $__templater->func('link', array('resources/manage-team', $__vars['resource'], ), true) . '" class="menu-linkRow" data-xf-click="overlay">
										' . 'Manage resource team' . '
									</a>
								';
	}
	$__compilerTemp3 .= '
								';
	if ($__templater->method($__vars['resource'], 'canFeatureUnfeature', array())) {
		$__compilerTemp3 .= '
									<a href="' . $__templater->func('link', array('resources/quick-feature', $__vars['resource'], ), true) . '"
										class="menu-linkRow"
										data-xf-click="switch"
										data-menu-closer="true">

										';
		if ($__vars['resource']['Featured']) {
			$__compilerTemp3 .= '
											' . 'Unfeature resource' . '
										';
		} else {
			$__compilerTemp3 .= '
											' . 'Feature resource' . '
										';
		}
		$__compilerTemp3 .= '
									</a>
								';
	}
	$__compilerTemp3 .= '
								';
	if ($__templater->method($__vars['resource'], 'canMove', array())) {
		$__compilerTemp3 .= '
									<a href="' . $__templater->func('link', array('resources/move', $__vars['resource'], ), true) . '" data-xf-click="overlay" class="menu-linkRow">' . 'Move resource' . '</a>
								';
	}
	$__compilerTemp3 .= '
								';
	if ($__templater->method($__vars['resource'], 'canReassign', array())) {
		$__compilerTemp3 .= '
									<a href="' . $__templater->func('link', array('resources/reassign', $__vars['resource'], ), true) . '" data-xf-click="overlay" class="menu-linkRow">' . 'Reassign resource' . '</a>
								';
	}
	$__compilerTemp3 .= '
								';
	if ($__templater->method($__vars['resource'], 'canChangeDiscussionThread', array())) {
		$__compilerTemp3 .= '
									<a href="' . $__templater->func('link', array('resources/change-thread', $__vars['resource'], ), true) . '" data-xf-click="overlay" class="menu-linkRow">' . 'Change discussion thread' . '</a>
								';
	}
	$__compilerTemp3 .= '
								';
	if ($__templater->method($__vars['resource'], 'canDelete', array('soft', ))) {
		$__compilerTemp3 .= '
									<a href="' . $__templater->func('link', array('resources/delete', $__vars['resource'], ), true) . '" data-xf-click="overlay" class="menu-linkRow">' . 'Delete resource' . '</a>
								';
	}
	$__compilerTemp3 .= '
									' . '
								';
	if ($__templater->method($__vars['resource'], 'canUseInlineModeration', array())) {
		$__compilerTemp3 .= '
									<div class="menu-footer"
										data-xf-init="inline-mod"
										data-type="resource"
										data-href="' . $__templater->func('link', array('inline-mod', ), true) . '"
										data-toggle=".js-resourceInlineModToggle">
										' . $__templater->formCheckBox(array(
		), array(array(
			'class' => 'js-resourceInlineModToggle',
			'value' => $__vars['resource']['resource_id'],
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
			'title' => 'More options',
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
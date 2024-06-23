<?php
// FROM HASH: dc39a1b0504dcdd843fc54a6a01b918c
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Moderators');
	$__finalCompiled .= '

';
	$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('Add moderator', array(
		'href' => $__templater->func('link', array('forumGroups/add-moderator', $__vars['forumGroup'], ), false),
		'icon' => 'add',
	), '', array(
	)) . '
');
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '

	' . '

	';
	if (!$__templater->test($__vars['contentModerators'], 'empty', array())) {
		$__compilerTemp1 .= '
		<div class="block">
			<div class="block-outer">
				<div class="block-outer-main">
					
					' . '
					
					<div class="menu" data-menu="menu" aria-hidden="true">
						<div class="menu-content">
							<h3 class="menu-header">' . 'Content moderators' . '</h3>
							<a href="' . $__templater->func('link', array('forumGroups', ), true) . '" class="menu-linkRow ' . ((!$__vars['userIdFilter']) ? 'is-selected' : '') . '">' . $__vars['xf']['language']['parenthesis_open'] . 'All' . $__vars['xf']['language']['parenthesis_close'] . '</a>
							';
		if ($__templater->isTraversable($__vars['users'])) {
			foreach ($__vars['users'] AS $__vars['user']) {
				$__compilerTemp1 .= '
								<a href="' . $__templater->func('link', array('forumGroups', null, array('user_id' => $__vars['user']['user_id'], ), ), true) . '"
									class="menu-linkRow ' . (($__vars['userIdFilter'] AND ($__vars['userIdFilter'] == $__vars['user']['user_id'])) ? 'is-selected' : '') . '">
									<span>' . $__templater->escape($__vars['user']['username']) . '</span>
								</a>
							';
			}
		}
		$__compilerTemp1 .= '
						</div>
					</div>
				</div>
			</div>
			<div class="block-container">
				<h2 class="block-header">' . 'Content moderators' . '</h2>
				<div class="block-body">
					';
		$__compilerTemp2 = '';
		if ($__templater->isTraversable($__vars['users'])) {
			foreach ($__vars['users'] AS $__vars['userId'] => $__vars['user']) {
				$__compilerTemp2 .= '
							';
				if ($__vars['contentModerators'][$__vars['userId']]) {
					$__compilerTemp2 .= '
								' . $__templater->dataRow(array(
						'rowtype' => 'subsection',
						'rowclass' => 'dataList-row--noHover',
					), array(array(
						'colspan' => '2',
						'_type' => 'cell',
						'html' => $__templater->escape($__vars['user']['username']),
					))) . '
								<tbody class="js-moderatorInsert-' . $__templater->escape($__vars['userId']) . '">
									';
					if ($__templater->isTraversable($__vars['contentModerators'][$__vars['userId']])) {
						foreach ($__vars['contentModerators'][$__vars['userId']] AS $__vars['contentMod']) {
							$__compilerTemp2 .= '
										' . $__templater->dataRow(array(
							), array(array(
								'href' => $__templater->func('link', array('forumGroups/content/edit', $__vars['contentMod'], ), false),
								'label' => $__templater->escape($__templater->method($__vars['contentMod'], 'getContentTitle', array())),
								'_type' => 'main',
								'html' => '',
							),
							array(
								'href' => $__templater->func('link', array('forumGroups/content/delete', $__vars['contentMod'], ), false),
								'overlay' => 'true',
								'_type' => 'delete',
								'html' => '',
							))) . '
									';
						}
					}
					$__compilerTemp2 .= '
									';
					if ($__vars['displayLimit'] AND ($__vars['contentModeratorTotals'][$__vars['userId']] > $__vars['displayLimit'])) {
						$__compilerTemp2 .= '
										' . $__templater->dataRow(array(
						), array(array(
							'colspan' => '2',
							'class' => 'dataList-cell--link',
							'_type' => 'cell',
							'html' => '
												<a href="' . $__templater->func('link', array('forumGroups', null, array('user_id' => $__vars['userId'], ), ), true) . '" data-xf-click="inserter" data-replace=".js-moderatorInsert-' . $__templater->escape($__vars['userId']) . '" data-animate-replace="false">' . '... and ' . $__templater->filter($__vars['contentModeratorTotals'][$__vars['userId']] - $__vars['displayLimit'], array(array('number', array()),), true) . ' more.' . '</a>
											',
						))) . '
									';
					}
					$__compilerTemp2 .= '
								</tbody>
							';
				}
				$__compilerTemp2 .= '
						';
			}
		}
		$__compilerTemp1 .= $__templater->dataList('
						' . $__compilerTemp2 . '
					', array(
		)) . '
				</div>
			</div>
		</div>
	';
	}
	$__compilerTemp1 .= '

	';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
	' . $__compilerTemp1 . '
';
	} else {
		$__finalCompiled .= '
	<div class="blockMessage">' . 'There are currently no moderators. Use the link above to create one.' . '</div>
';
	}
	return $__finalCompiled;
}
);
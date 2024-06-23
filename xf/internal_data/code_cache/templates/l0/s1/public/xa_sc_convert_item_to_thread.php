<?php
// FROM HASH: 547f1889122d66324e21b1fb38b64183
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Convert item to thread');
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['item'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

';
	$__templater->includeJs(array(
		'src' => 'xf/prefix_menu.js',
		'min' => '1',
	));
	$__finalCompiled .= '

';
	$__compilerTemp1 = array();
	$__compilerTemp2 = $__templater->method($__vars['nodeTree'], 'getFlattened', array(0, ));
	if ($__templater->isTraversable($__compilerTemp2)) {
		foreach ($__compilerTemp2 AS $__vars['treeEntry']) {
			$__compilerTemp1[] = array(
				'value' => $__vars['treeEntry']['record']['node_id'],
				'disabled' => (($__vars['treeEntry']['record']['node_type_id'] != 'Forum') ? 'disabled' : ''),
				'label' => $__templater->func('repeat_raw', array('&nbsp; ', $__vars['treeEntry']['depth'], ), true) . ' ' . $__templater->escape($__vars['treeEntry']['record']['title']),
				'_type' => 'option',
			);
		}
	}
	$__templater->includeJs(array(
		'src' => 'xf/prefix_menu.js',
		'min' => '1',
	));
	$__compilerTemp3 = '';
	if (!$__vars['item']['Discussion']) {
		$__compilerTemp3 .= '
				' . $__templater->callMacro('tag_macros', 'edit_rows', array(
			'uneditableTags' => $__vars['uneditableTags'],
			'editableTags' => $__vars['editableTags'],
			'minTags' => $__vars['category']['min_tags'],
			'tagList' => 'tagList--item-' . $__vars['item']['item_id'],
		), $__vars) . '				
			';
	}
	$__compilerTemp4 = '';
	if ($__templater->method($__vars['item'], 'canSendModeratorActionAlert', array())) {
		$__compilerTemp4 .= '
				' . $__templater->callMacro('helper_action', 'author_alert', array(
			'selected' => true,
		), $__vars) . '
			';
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body js-prefixListenContainer">
			' . $__templater->formInfoRow('
				' . '<span style="color:red"> <b>Warning!</b> Performing this action will permanently and irreversibly delete the item and all of its contents (sections, custom fields, comments, ratings, reviews etc) upon successful conversion to a discussion thread!</span>' . '
			', array(
		'rowtype' => 'confirm',
	)) . '
			
			' . $__templater->formSelectRow(array(
		'name' => 'target_node_id',
		'value' => ($__vars['item']['Discussion'] ? $__vars['item']['Discussion']['node_id'] : 0),
		'class' => 'js-nodeList',
	), $__compilerTemp1, array(
		'label' => 'Destination forum',
	)) . '
			
			' . $__templater->formRow('
				' . '' . '
				' . $__templater->callMacro('prefix_macros', 'select', array(
		'type' => 'thread',
		'prefixes' => $__vars['threadPrefixes'],
		'href' => $__templater->func('link', array('forums/prefixes', ), false),
		'listenTo' => '< .js-prefixListenContainer | .js-nodeList',
	), $__vars) . '
			', array(
		'label' => 'Prefix',
		'rowtype' => 'input',
	)) . '			
			
			' . $__compilerTemp3 . '

			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'notify_watchers',
		'value' => '1',
		'selected' => true,
		'label' => 'Notify members watching the destination forum',
		'_type' => 'option',
	)), array(
	)) . '
			
			' . $__compilerTemp4 . '
			
			' . $__templater->formInfoRow('
				' . 'Are you sure you want to convert this item to a thread?' . '
			', array(
		'rowtype' => 'confirm',
	)) . '			
		</div>
		
		' . $__templater->formSubmitRow(array(
		'icon' => 'confirm',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('showcase/convert-to-thread', $__vars['item'], ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);
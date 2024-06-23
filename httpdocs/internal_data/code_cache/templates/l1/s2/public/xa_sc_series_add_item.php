<?php
// FROM HASH: 48fe1f40f0ae11c6e5251a46a5d22e79
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Add item to series');
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['series'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if (!$__templater->test($__vars['items'], 'empty', array())) {
		$__compilerTemp1 .= '
							';
		$__compilerTemp2 = array(array(
			'value' => '0',
			'_type' => 'option',
		));
		if ($__templater->isTraversable($__vars['items'])) {
			foreach ($__vars['items'] AS $__vars['item']) {
				$__compilerTemp2[] = array(
					'value' => $__vars['item']['item_id'],
					'label' => $__templater->escape($__vars['item']['title']),
					'_type' => 'option',
				);
			}
		}
		$__compilerTemp1 .= $__templater->formSelect(array(
			'name' => 'item_id',
			'value' => '0',
			'class' => 'input--inline',
			'style' => 'max-width:99%;',
		), $__compilerTemp2) . '
							<p class="formRow-explain">' . 'Select the item that you want to add to this this series.    
<br><br>
<b>Note</b>: If the item that you are wanting to associate with this series is not listed above, you can attempt to associate a specific item by using the "Set item by URL"  option below.' . '</p>
						';
	} else {
		$__compilerTemp1 .= '
							<p class="formRow-explain">' . 'You do not have any recent existing items that are able to be associated with this series.
<br><br>
Use the "Set item url" option below to attempt to associate an item manually. ' . '</p>
						';
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formRadioRow(array(
		'name' => 'item_input_type',
		'value' => 'item_list',
		'listclass' => '_listColumns',
	), array(array(
		'value' => 'item_list',
		'data-hide' => 'true',
		'label' => 'Select recent item',
		'_dependent' => array('
						' . $__compilerTemp1 . '	
					'),
		'_type' => 'option',
	),
	array(
		'value' => 'input_item_url',
		'data-hide' => 'true',
		'label' => 'Set item by URL',
		'_dependent' => array('
						' . $__templater->formTextBox(array(
		'name' => 'item_url',
		'type' => 'url',
	)) . '
						<p class="formRow-explain">' . 'Enter the URL of the item that you want to add to this series.  
<br><br>
<b>Important Note</b>:  Only visible state items can be added to a series. 
<br><br>
<b>Important Note</b>: Only staff members can add another member\'s item to a series.' . '</p>
					'),
		'_type' => 'option',
	)), array(
		'label' => 'Select or set an item',
	)) . '

			' . $__templater->formNumberBoxRow(array(
		'name' => 'display_order',
		'value' => '1',
		'min' => '1',
		'pattern' => '\\d*',
	), array(
		'label' => 'Display order',
		'explain' => 'The display order determines the order in which the items will appear on the series page as well as the order in which the item titles will appear within the series table of contents on item pages. 
<br><br>
<b>Tip:</b> Using increments of 10 or 100 when setting the display order allow you add a new item between existing items without having to adjust display orders of multiple existing items.',
	)) . '
		</div>

		' . $__templater->formSubmitRow(array(
		'icon' => 'save',
		'sticky' => 'true',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('showcase/series/add-item', $__vars['series'], ), false),
		'ajax' => 'true',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);
<?php
// FROM HASH: 882caa4f8d8c529262cb9653068f4198
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->form('
	
	<div class="menu-row">
    	' . 'Mentioned By' . $__vars['xf']['language']['label_separator'] . '
		<div class="u-inputSpacer">
			' . $__templater->formTextBox(array(
		'name' => 'fs_escrow_mentioned_username',
		'value' => $__vars['conditions']['fs_escrow_mentioned_username'],
		'ac' => 'single',
	)) . '
		</div>
  	</div>
	
	<div class="menu-row menu-row--separated">
			' . 'Status' . $__vars['xf']['language']['label_separator'] . '
			<div class="u-inputSpacer">
				' . $__templater->formSelect(array(
		'name' => 'fs_escrow_status',
		'value' => $__vars['conditions']['fs_escrow_status'],
	), array(array(
		'value' => 'all',
		'selected' => true,
		'label' => 'All',
		'_type' => 'option',
	),
	array(
		'value' => '1',
		'label' => 'Waiting for approvel',
		'_type' => 'option',
	),
	array(
		'value' => '2',
		'label' => 'Aproved. Processing',
		'_type' => 'option',
	),
	array(
		'value' => '3',
		'label' => 'Cancelled by mentioned User',
		'_type' => 'option',
	),
	array(
		'value' => '4',
		'label' => 'Cancelled by Creator',
		'_type' => 'option',
	),
	array(
		'value' => '5',
		'label' => 'Completed',
		'_type' => 'option',
	))) . '
			</div>
		</div>
	
	
	  <div class="menu-footer">
    <span class="menu-footer-controls">
      ' . $__templater->button('Filter', array(
		'type' => 'submit',
		'class' => 'button--primary',
	), '', array(
	)) . '
    </span>
  </div>

  ' . $__templater->formHiddenVal('search', '1', array(
	)) . '
', array(
		'action' => $__templater->func('link', array('escrow', ), false),
	));
	return $__finalCompiled;
}
);
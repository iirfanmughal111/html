<?php
// FROM HASH: 9ce3779fb7b120e43866a3ff95287b01
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->form('
			' . $__templater->formTextBoxRow(array(
		'name' => 'tx_id',
		'value' => $__vars['conditions']['tx_id'],
	), array(
	)) . '
	
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
		'action' => $__templater->func('link', array('escrow/live', ), false),
	));
	return $__finalCompiled;
}
);
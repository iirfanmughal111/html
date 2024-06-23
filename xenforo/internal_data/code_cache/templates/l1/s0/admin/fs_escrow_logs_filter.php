<?php
// FROM HASH: e126929a50ae6946891e3465c1c1c6a6
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->form('
	<div class="menu-row">
    	' . 'username:' . '
		<div class="u-inputSpacer">
			' . $__templater->formTextBox(array(
		'name' => 'fs_transaction_user',
		'value' => $__vars['conditions']['fs_transaction_users'],
		'ac' => 'single',
	)) . '
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
		'action' => $__templater->func('link', array('escrow/log', ), false),
	));
	return $__finalCompiled;
}
);
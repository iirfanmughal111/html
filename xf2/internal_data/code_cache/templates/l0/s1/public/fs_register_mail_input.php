<?php
// FROM HASH: 2cc205284aea03d5f0e6d86892f02721
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped(' ' . 'fs_register_male_email' . ' ');
	$__finalCompiled .= '
' . $__templater->form('
  <div class="block-container">
	  ' . $__templater->formRow('
	  <div class="inputChoices"  >
	  ' . $__templater->formRadio(array(
		'name' => 'contact',
		'required' => 'true',
	), array(array(
		'label' => 'fs_register_contact_email',
		'_dependent' => array('
	 		' . $__templater->formTextBox(array(
		'name' => 'contact_email',
	)) . '
			  '),
		'_type' => 'option',
	),
	array(
		'label' => 'contact_mobile',
		'name' => 'contact_mobile',
		'_dependent' => array('
					 ' . $__templater->formTextBox(array(
		'name' => 'contact_mobile',
	)) . '
			  '),
		'_type' => 'option',
	))) . '
	  </div>
	  ', array(
		'label' => 'fs_register_contact_method',
	)) . '
	<input type="hidden" name="user_id" value="' . $__templater->escape($__vars['user_id']) . '">
	  ' . $__templater->formDateInputRow(array(
		'name' => 'date',
	), array(
		'label' => 'fs_register_date',
	)) . '
	  ' . $__templater->formTextBoxRow(array(
		'name' => 'time',
		'class' => 'input--date time start',
		'required' => 'true',
		'type' => 'time',
	), array(
		'label' => 'fs_register_time',
	)) . '	  
	    ' . $__templater->formTextBoxRow(array(
		'name' => 'duration',
		'required' => 'true',
	), array(
		'label' => 'fs_register_duration',
	)) . '
	    ' . $__templater->formTextBoxRow(array(
		'name' => 'type',
		'required' => 'true',
	), array(
		'label' => 'fs_register_type',
	)) . '
	    ' . $__templater->formTextBoxRow(array(
		'name' => 'city',
		'required' => 'true',
	), array(
		'label' => 'fs_register_city',
	)) . '
	    ' . $__templater->formTextBoxRow(array(
		'name' => 'rates',
		'required' => 'true',
	), array(
		'label' => 'fs_register_rates',
	)) . '
	    ' . $__templater->formTextBoxRow(array(
		'name' => 'promotion',
		'required' => 'true',
	), array(
		'label' => 'fs_register_promotion',
	)) . '
	  
	  
	  

    ' . $__templater->formSubmitRow(array(
		'submit' => '',
		'icon' => 'save',
	), array(
	)) . '
  </div>
', array(
		'action' => $__templater->func('link', array('members/sendmail', $__vars['xf']['visitor'], ), false),
		'ajax' => 'true',
		'class' => 'block',
		'data-force-flash-message' => 'true',
	));
	return $__finalCompiled;
}
);
<?php
// FROM HASH: 800e0c46b7b55138b81a0b5dc59b3aa6
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped(' ' . 'Appointment Request ' . ' ');
	$__finalCompiled .= '
' . $__templater->form('
  <div class="block-container">
	  ' . $__templater->formRow('
	  <div class="inputChoices"  >
	  ' . $__templater->formRadio(array(
		'name' => 'contact',
		'required' => 'true',
		'checked' => 'true',
	), array(array(
		'label' => 'Email',
		'name' => 'contact_detail',
		'_dependent' => array('
	 		' . $__templater->formTextBox(array(
		'name' => 'contact_email',
		'required' => 'true',
	)) . '
			  '),
		'_type' => 'option',
	),
	array(
		'label' => 'Mobile',
		'name' => 'contact_detail',
		'_dependent' => array('
					 ' . $__templater->formTextBox(array(
		'name' => 'contact_mobile',
		'required' => 'true',
	)) . '
			  '),
		'_type' => 'option',
	))) . '
	  </div>
	  ', array(
		'label' => 'Contact Method',
		'hint' => 'Required',
	)) . '
	<input type="hidden" name="user_id" value="' . $__templater->escape($__vars['user_id']) . '">
	  ' . $__templater->formDateInputRow(array(
		'name' => 'date',
		'required' => 'true',
	), array(
		'hint' => 'Required',
		'label' => 'Date',
	)) . '
	  ' . $__templater->formTextBoxRow(array(
		'name' => 'time',
		'class' => 'input--date time start',
		'required' => 'true',
		'type' => 'time',
	), array(
		'label' => 'Time',
		'hint' => 'Required',
	)) . '	  
	    ' . $__templater->formTextBoxRow(array(
		'name' => 'duration',
		'required' => 'true',
	), array(
		'hint' => 'Required',
		'label' => 'Duration',
	)) . '
	    ' . $__templater->formTextBoxRow(array(
		'name' => 'type',
		'required' => 'true',
	), array(
		'hint' => 'Required',
		'label' => 'Type',
	)) . '
	    ' . $__templater->formTextBoxRow(array(
		'name' => 'city',
		'required' => 'true',
	), array(
		'hint' => 'Required',
		'label' => 'Desired City',
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
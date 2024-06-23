<?php
// FROM HASH: a25cb8ba96d2262a4c5890b6a1a790a5
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__vars['attendance']) {
		$__finalCompiled .= '
  ';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('View ');
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

<div class="block">
  ';
	if ($__vars['attendance']) {
		$__finalCompiled .= '
  
      <div class="block-body">
        ' . $__templater->formTextBoxRow(array(
			'name' => 'username',
			'value' => $__vars['xf']['visitor']['username'],
			'readonly' => 'true',
		), array(
			'label' => 'Usernmae',
		)) . '

        ' . $__templater->formRow('
          <div class="inputGroup">
            ' . $__templater->formDateInput(array(
			'name' => 'date',
			'class' => 'date end',
			'readonly' => 'true',
			'value' => $__templater->func('date', array($__vars['attendance']['date'], 'Y-m-d', ), false),
			'required' => 'true',
		)) . '
            <span class="inputGroup-splitter"></span>
            ' . $__templater->formTextBox(array(
			'type' => 'time',
			'name' => 'in-time',
			'class' => 'input--date time end',
			'readonly' => 'true',
			'value' => $__templater->func('time', array($__vars['attendance']['in_time'], 'h:i', ), false),
			'required' => 'true',
		)) . '
            ' . $__templater->formTextBox(array(
			'type' => 'time',
			'name' => 'out-time',
			'readonly' => 'true',
			'class' => 'input--date time end',
			'value' => $__templater->func('time', array($__vars['attendance']['out_time'], 'h:i', ), false),
			'required' => 'true',
		)) . '
          </div>
        ', array(
			'label' => 'Office Time',
			'rowtype' => 'input',
		)) . '

        ' . $__templater->formTextAreaRow(array(
			'name' => 'comment',
			'readonly' => 'true',
			'value' => (($__vars['attend']['comment'] != '') ? $__vars['attend']['comment'] : 'No special Comment'),
		), array(
			'label' => 'Any comments',
		)) . '
        
      </div>
 
  
  ';
	}
	$__finalCompiled .= '
</div>
';
	return $__finalCompiled;
}
);
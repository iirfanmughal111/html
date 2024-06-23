<?php
// FROM HASH: 88d53471430b8c4921cf86588c24d9ea
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__vars['attendance']) {
		$__finalCompiled .= '
  ';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit');
		$__finalCompiled .= '
  ';
	} else {
		$__finalCompiled .= '
  ';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('New Attendance');
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

<div class="block">

  <!-- ' . $__templater->func('date_time', array($__vars['xf']['time'], ), true) . ' -->
  <!-- ' . $__templater->func('dump', array($__vars['xf']['time'], ), true) . ' -->
  <!-- ' . $__templater->func('dump', array($__templater->func('date', array($__vars['attendance']['date'], 'Y-m-d', ), false), ), true) . ' -->

  ';
	if ($__vars['attendance']) {
		$__finalCompiled .= '
    <!-- ' . $__templater->func('dump', array($__vars['attendance']['user_id'], ), true) . '
    ' . $__templater->func('dump', array($__vars['attendance'], ), true) . ' -->

    ' . $__templater->form('
      <div class="block-body">
        ' . $__templater->formTextBoxRow(array(
			'name' => 'username',
			'value' => $__vars['attendance']['User']['username'],
			'readonly' => 'true',
		), array(
			'label' => 'Current User',
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
			'value' => $__templater->func('time', array($__vars['attendance']['in_time'], 'h:i', ), false),
			'required' => 'true',
		)) . '
            ' . $__templater->formTextBox(array(
			'type' => 'time',
			'name' => 'out-time',
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
			'value' => $__vars['attendance']['comment'],
		), array(
			'label' => 'Any comments',
		)) . '
        ' . $__templater->formTextBoxRow(array(
			'type' => 'hidden',
			'name' => 'attendance_id',
			'value' => $__vars['attendance']['attendance_id'],
		), array(
		)) . '
      </div>

      ' . $__templater->formSubmitRow(array(
			'icon' => 'save',
			'sticky' => 'true',
		), array(
		)) . '
    ', array(
			'action' => $__templater->func('link', array('attendance/update', ), false),
			'ajax' => 'true',
			'class' => 'block-container',
			'novalidate' => 'novalidate',
		)) . '
    ';
	} else {
		$__finalCompiled .= '

    ' . $__templater->form('
      <div class="block-body">
        ' . $__templater->formTextBoxRow(array(
			'name' => 'username',
			'value' => $__vars['xf']['visitor']['username'],
			'readonly' => 'true',
		), array(
			'label' => 'Current User',
		)) . '

        ' . $__templater->formRow('
          <div class="inputGroup">
            ' . $__templater->formDateInput(array(
			'name' => 'date',
			'class' => 'date end',
			'readonly' => 'true',
			'value' => $__templater->func('date', array($__vars['xf']['time'], 'Y-m-d', ), false),
			'required' => 'true',
		)) . '
            <span class="inputGroup-splitter"></span>
            ' . $__templater->formTextBox(array(
			'type' => 'time',
			'name' => 'in-time',
			'class' => 'input--date time end',
			'value' => $__templater->func('date', array($__vars['xf']['time'], 'h:i', ), false),
			'required' => 'true',
		)) . '
            ' . $__templater->formTextBox(array(
			'type' => 'time',
			'name' => 'out-time',
			'class' => 'input--date time end',
			'value' => $__templater->func('date', array($__vars['xf']['time'], 'h:i', ), false),
			'required' => 'true',
		)) . '
          </div>
        ', array(
			'label' => 'Office Time',
			'rowtype' => 'input',
		)) . '

        ' . $__templater->formTextAreaRow(array(
			'name' => 'comment',
			'autocomplete' => 'off',
		), array(
			'label' => 'Any comments',
		)) . '
      </div>

      ' . $__templater->formSubmitRow(array(
			'icon' => 'save',
			'sticky' => 'true',
		), array(
		)) . '
    ', array(
			'action' => $__templater->func('link', array('attendance/save', ), false),
			'ajax' => 'true',
			'class' => 'block-container',
			'novalidate' => 'novalidate',
		)) . '
  ';
	}
	$__finalCompiled .= '
</div>';
	return $__finalCompiled;
}
);
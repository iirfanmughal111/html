<?php
// FROM HASH: 45283694e9183fecf6d9b46fb72c605d
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit:');
	$__finalCompiled .= '

<div class="block">
  ' . $__templater->form('
    <div class="block-body">
      <input type="hidden" name="thread_id" value="' . $__templater->escape($__vars['thread_id']) . '" />
      <input type="hidden" name="arrayIndex" value="' . $__templater->escape($__vars['array_index']) . '" />

      ' . $__templater->formRow('
        ' . $__templater->formTextBoxRow(array(
		'name' => 'option',
		'placeholder' => 'Enter dropdown Reply',
		'data-i' => '0',
		'dir' => 'ltr',
		'value' => $__vars['array_value'],
	), array(
		'rowtype' => 'fullWidth',
	)) . '
      ', array(
		'label' => 'Enter Dropdown Options',
		'rowtype' => 'input',
	)) . '
    </div>

    ' . $__templater->formSubmitRow(array(
		'icon' => 'save',
		'sticky' => 'true',
	), array(
	)) . '
  ', array(
		'action' => $__templater->func('link', array(('opt-reply/' . $__vars['thread_id']) . '/update-single', array($__vars['thread_id'], ), array('id' => $__vars['index'], ), ), false),
		'ajax' => 'true',
		'class' => 'block-container',
		'novalidate' => 'novalidate',
	)) . '
</div>
';
	return $__finalCompiled;
}
);
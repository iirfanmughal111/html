<?php
// FROM HASH: 319300fbf8f898cba3ead4c542e3eca3
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__vars['thread']) {
		$__finalCompiled .= '
  ';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit:');
		$__finalCompiled .= '
  ';
	} else {
		$__finalCompiled .= '
  ';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Add Dropdown Reply' . $__vars['xf']['language']['label_separator']);
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

<div class="block">


  ';
	if ($__vars['thread']) {
		$__finalCompiled .= '
    ';
		$__templater->breadcrumbs($__templater->method($__vars['thread'], 'getBreadcrumbs', array(false, )));
		$__finalCompiled .= '
    ';
		$__compilerTemp1 = '';
		if ($__vars['thread']['is_dropdown_active']) {
			$__compilerTemp1 .= '
          ' . $__templater->formCheckBoxRow(array(
			), array(array(
				'name' => 'status',
				'checked' => 'true',
				'label' => 'Active',
				'_type' => 'option',
			)), array(
			)) . '
          ';
		} else {
			$__compilerTemp1 .= '
          ' . $__templater->formCheckBoxRow(array(
			), array(array(
				'name' => 'status',
				'label' => 'Active',
				'_type' => 'option',
			)), array(
			)) . '
        ';
		}
		$__compilerTemp2 = '';
		if ($__templater->isTraversable($__vars['thread']['dropdwon_options'])) {
			foreach ($__vars['thread']['dropdwon_options'] AS $__vars['thread_val']) {
				$__compilerTemp2 .= '
            ';
				if ($__vars['thread_val']) {
					$__compilerTemp2 .= '
              ' . $__templater->formTextBoxRow(array(
						'name' => 'options[]',
						'placeholder' => 'Enter dropdown Reply',
						'data-i' => '0',
						'dir' => 'ltr',
						'value' => $__vars['thread_val'],
					), array(
						'rowtype' => 'fullWidth',
					)) . '
            ';
				}
				$__compilerTemp2 .= '
          ';
			}
		}
		$__finalCompiled .= $__templater->form('
      <div class="block-body">
        ' . $__compilerTemp1 . '
        <input type="hidden" name="thread_id" value="' . $__templater->escape($__vars['thread']['thread_id']) . '" />

        ' . $__templater->formRow('
          ' . $__compilerTemp2 . '
          <div
            class="inputGroup is-undraggable js-blockDragafter"
            data-xf-init="field-adder"
            data-remove-class="is-undraggable js-blockDragafter"
          >
            ' . $__templater->formTextBoxRow(array(
			'name' => 'options[]',
			'placeholder' => 'Enter dropdown Reply',
			'data-i' => '0',
			'dir' => 'ltr',
		), array(
			'rowtype' => 'fullWidth',
		)) . '
          </div>
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
			'action' => $__templater->func('link', array('opt-reply/save', ), false),
			'ajax' => 'true',
			'class' => 'block-container',
			'novalidate' => 'novalidate',
		)) . '
    ';
	} else {
		$__finalCompiled .= '

    ' . $__templater->form('
      <div class="block-body">
        ' . $__templater->formCheckBoxRow(array(
		), array(array(
			'name' => 'status',
			'label' => 'Active',
			'_type' => 'option',
		)), array(
		)) . '
        <input type="hidden" name="thread_id" value="' . $__templater->escape($__vars['thread_id']) . '" />
        ' . $__templater->formRow('
          <div
            class="inputGroup is-undraggable js-blockDragafter"
            data-xf-init="field-adder"
            data-remove-class="is-undraggable js-blockDragafter"
          >
            ' . $__templater->formTextBoxRow(array(
			'name' => 'options[]',
			'placeholder' => 'Enter dropdown Reply',
			'data-i' => '0',
			'dir' => 'ltr',
		), array(
			'rowtype' => 'fullWidth',
		)) . '
          </div>
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
			'action' => $__templater->func('link', array('opt-reply/save', $__vars['thread'], ), false),
			'ajax' => 'true',
			'class' => 'block-container',
			'novalidate' => 'novalidate',
		)) . '

  ';
	}
	$__finalCompiled .= '

</div>
';
	return $__finalCompiled;
}
);
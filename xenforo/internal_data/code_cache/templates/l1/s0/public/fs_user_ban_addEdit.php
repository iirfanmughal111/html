<?php
// FROM HASH: 3da9b5637926ef23e8c2756ec77b4b14
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__vars['userBanned']['user_id']) {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit ' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['userBanned']['User']['username']));
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Ban Member');
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '
';
	$__compilerTemp1 = '';
	if ($__vars['userBanned']['user_id']) {
		$__compilerTemp1 .= '
					' . $__templater->button('
						' . 'Cancel Ban' . '
					', array(
			'href' => $__templater->func('link', array('scheduleBanUser/delete', $__vars['userBanned'], ), false),
			'overlay' => 'true',
		), '', array(
		)) . '
				';
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
		
					<input type="hidden" name="user_id" value="' . ($__vars['userBanned']['user_id'] ? $__templater->escape($__vars['userBanned']['user_id']) : $__templater->escape($__vars['user_id'])) . '">
					<input type="hidden" name="ban_id" value="' . ($__vars['userBanned']['ban_id'] ? $__templater->escape($__vars['userBanned']['ban_id']) : null) . '">
					
			 ' . $__templater->formRow('
          <div class="inputGroup">
            	' . $__templater->formDateInput(array(
		'name' => 'ban_date',
		'value' => ($__vars['userBanned']['ban_date'] ? $__templater->func('date', array($__vars['userBanned']['ban_date'], 'Y-m-d', ), false) : $__templater->func('date', array($__vars['xf']['time'], 'Y-m-d', ), false)),
	)) . '
			  
            <span class="inputGroup-splitter"></span>
          
			 <span class="inputGroup" dir="ltr">
			  ' . $__templater->formTextBox(array(
		'name' => 'ban_time',
		'class' => 'input--date time start',
		'required' => 'true',
		'type' => 'time',
		'value' => ($__templater->method($__vars['userBanned'], 'getbanDate', array()) ?: ''),
		'data-xf-init' => 'time-picker',
		'data-moment' => $__vars['timeFormat'],
		'data-format' => $__vars['xf']['language']['time_format'],
	)) . '
</span>
          </div>
        ', array(
		'label' => 'Ban start',
		'rowtype' => 'input',
	)) . '
			

			' . $__templater->formTextBoxRow(array(
		'name' => 'ban_reason',
		'value' => ($__vars['userBanned']['ban_reason'] ? $__vars['userBanned']['ban_reason'] : ''),
	), array(
		'label' => 'Reason for banning',
		'explain' => 'This will be shown to the user if provided.',
	)) . '
		</div>
		' . $__templater->formSubmitRow(array(
		'icon' => 'save',
	), array(
		'html' => '
				' . $__compilerTemp1 . '
			',
	)) . '
	</div>
	' . $__templater->func('redirect_input', array(null, null, true)) . '
', array(
		'action' => $__templater->func('link', array('scheduleBanUser/save', ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);
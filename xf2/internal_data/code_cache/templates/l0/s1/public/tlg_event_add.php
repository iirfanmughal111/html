<?php
// FROM HASH: 453b8c014a858ba4c2da77ca3c486940
return array(
'macros' => array('date_input' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'label' => '!',
		'name' => '!',
		'date' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    ';
	$__templater->includeCss('tlg_event_style.less');
	$__finalCompiled .= '

    ' . $__templater->formRow('
        <div class="event-input-group">
            ' . $__templater->formDateInput(array(
		'value' => $__vars['date']['date'],
		'name' => $__vars['name'] . '[date]',
		'class' => 'flex--grow date-input--field',
	)) . '
            ' . $__templater->callback('Truonglv\\Groups\\Callback', 'renderHourSelect', $__templater->escape($__vars['name']) . '[hour]', array('date' => $__vars['date'], )) . '
        </div>
    ', array(
		'label' => $__templater->escape($__vars['label']),
		'rowtype' => 'input',
	)) . '
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped(($__templater->method($__vars['event'], 'exists', array()) ? 'Edit event' : 'Add new event'));
	$__finalCompiled .= '

';
	if ($__vars['showWrapper']) {
		$__finalCompiled .= '
    ';
		$__compilerTemp1 = $__vars;
		$__compilerTemp1['pageSelected'] = $__templater->preEscaped('events');
		$__templater->wrapTemplate('tlg_group_wrapper', $__compilerTemp1);
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	$__templater->includeJs(array(
		'src' => 'Truonglv/Groups/event.js',
		'addon' => 'Truonglv/Groups',
		'min' => '1',
	));
	$__finalCompiled .= '

';
	$__vars['googleMapKey'] = $__templater->preEscaped($__templater->callback('Truonglv\\Groups\\App', 'getOption', 'googleMapKey', array()));
	$__finalCompiled .= '

';
	if (!$__templater->test($__vars['googleMapKey'], 'empty', array())) {
		$__finalCompiled .= '
    ';
		$__templater->inlineJs('
        <script async defer
                src="https://maps.googleapis.com/maps/api/js?key=' . $__vars['googleMapKey'] . '&libraries=places">
        </script>
    ');
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	$__compilerTemp2 = '';
	if ($__vars['attachmentData']) {
		$__compilerTemp2 .= '
                ' . $__templater->formRow('
                    ' . $__templater->callMacro('helper_attach_upload', 'upload_block', array(
			'attachmentData' => $__vars['attachmentData'],
		), $__vars) . '
                ', array(
		)) . '
            ';
	}
	$__compilerTemp3 = '';
	if ($__vars['canEditTags']) {
		$__compilerTemp3 .= '
                ' . $__templater->callMacro('tag_macros', 'edit_rows', array(
			'uneditableTags' => ($__vars['uneditableTags'] ?: null),
			'editableTags' => ($__vars['editableTags'] ?: null),
		), $__vars) . '
            ';
	}
	$__compilerTemp4 = '';
	if ($__vars['canEditTimeZone']) {
		$__compilerTemp4 .= '
                ';
		$__compilerTemp5 = $__templater->mergeChoiceOptions(array(), $__templater->method($__templater->method($__vars['xf']['app'], 'data', array('XF:TimeZone', )), 'getTimeZoneOptions', array()));
		$__compilerTemp4 .= $__templater->formSelectRow(array(
			'name' => 'timezone',
			'value' => ($__vars['event']['timezone'] ?: $__vars['xf']['visitor']['timezone']),
		), $__compilerTemp5, array(
			'label' => 'Time zone',
		)) . '
            ';
	}
	$__compilerTemp6 = '';
	if (!$__templater->test($__vars['googleMapKey'], 'empty', array())) {
		$__compilerTemp6 .= '
                            <div data-error=""
                                 data-xf-init="tlg-event-map"
                                 data-zoom="17" data-place="' . $__templater->escape($__vars['event']['address']) . '"
                                 data-acfield="#tl_groups_ac_field">

                                <div class="map" style="width: 100%;height:350px"></div>
                            </div>
                        ';
	}
	$__finalCompiled .= $__templater->form('
    <div class="block-container">
        <div class="block-body">
            ' . $__templater->formTextBoxRow(array(
		'name' => 'event_name',
		'value' => $__vars['event']['event_name'],
		'placeholder' => 'Event name',
		'class' => 'input--title',
		'autofocus' => 'autofocus',
		'maxlength' => $__templater->func('max_length', array($__vars['event'], 'event_name', ), false),
	), array(
		'rowtype' => 'fullWidth noLabel',
	)) . '

            ' . $__templater->formEditorRow(array(
		'name' => 'description',
		'attachments' => $__vars['attachmentData']['attachments'],
		'value' => ($__templater->method($__vars['event'], 'exists', array()) ? $__vars['event']['FirstComment']['message'] : null),
	), array(
		'label' => 'Description',
		'rowtype' => 'fullWidth noLabel mergePrev',
	)) . '

            ' . $__compilerTemp2 . '

            ' . $__compilerTemp3 . '

            <hr class="formRowSep" />

            ' . $__templater->callMacro(null, 'date_input', array(
		'label' => 'Begin date',
		'date' => $__templater->method($__vars['event'], 'getBeginDateOutput', array('edit', )),
		'name' => 'begin_date',
	), $__vars) . '

            ' . $__templater->callMacro(null, 'date_input', array(
		'label' => 'End date',
		'date' => $__templater->method($__vars['event'], 'getEndDateOutput', array('edit', )),
		'name' => 'end_date',
	), $__vars) . '

            ' . $__compilerTemp4 . '

            ' . $__templater->formRadioRow(array(
		'name' => 'location_type',
		'value' => $__vars['event']['location_type'],
	), array(array(
		'value' => '',
		'label' => 'None',
		'_type' => 'option',
	),
	array(
		'value' => 'virtual',
		'label' => 'Virtual location',
		'_dependent' => array('
                        ' . $__templater->formTextBox(array(
		'name' => 'virtual_address',
		'value' => $__vars['event']['virtual_address'],
		'maxlength' => $__templater->func('max_length', array($__vars['event'], 'virtual_address', ), false),
	)) . '
                    '),
		'_type' => 'option',
	),
	array(
		'value' => 'real',
		'label' => 'Real location',
		'_dependent' => array('
                        ' . $__templater->formTextBox(array(
		'name' => 'address',
		'value' => $__vars['event']['address'],
		'id' => 'tl_groups_ac_field',
		'maxlength' => $__templater->func('max_length', array($__vars['event'], 'address', ), false),
	)) . '
                        ' . $__compilerTemp6 . '
                    '),
		'_type' => 'option',
	)), array(
		'label' => 'Location type',
	)) . '

            ' . $__templater->formNumberBoxRow(array(
		'name' => 'max_attendees',
		'value' => $__vars['event']['max_attendees'],
		'min' => '0',
	), array(
		'label' => 'Maximum attendees',
		'explain' => 'Set to 0 to unlimitted.',
	)) . '
        </div>

        ' . $__templater->formHiddenVal('latitude', $__vars['event']['latitude'], array(
	)) . '
        ' . $__templater->formHiddenVal('longitude', $__vars['event']['longitude'], array(
	)) . '

        ' . $__templater->formSubmitRow(array(
		'icon' => 'save',
		'sticky' => 'true',
	), array(
	)) . '
    </div>
', array(
		'action' => (($__vars['event']['event_id'] > 0) ? $__templater->func('link', array('group-events/edit', $__vars['event'], ), false) : $__templater->func('link', array('group-events/add', null, array('group_id' => $__vars['group']['group_id'], ), ), false)),
		'class' => 'block',
		'ajax' => 'true',
		'data-xf-init' => 'attachment-manager',
	)) . '

';
	return $__finalCompiled;
}
);
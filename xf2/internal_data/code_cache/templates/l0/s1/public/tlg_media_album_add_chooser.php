<?php
// FROM HASH: 92a709f543997865bb75e9b453761a72
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Add album');
	$__finalCompiled .= '

';
	if (!$__vars['isInline']) {
		$__finalCompiled .= '
    ';
		$__compilerTemp1 = $__vars;
		$__compilerTemp1['pageSelected'] = $__templater->preEscaped('media');
		$__templater->wrapTemplate('tlg_group_wrapper', $__compilerTemp1);
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	$__templater->includeJs(array(
		'src' => 'Truonglv/Groups/group.js',
		'min' => '1',
		'addon' => 'Truonglv/Groups',
	));
	$__finalCompiled .= '
';
	$__templater->includeCss('tlg_style.less');
	$__finalCompiled .= '

';
	$__compilerTemp2 = array();
	if ($__vars['canAddAlbums']) {
		$__compilerTemp2[] = array(
			'value' => 'new',
			'label' => 'Add new albums',
			'_type' => 'option',
		);
	}
	if ($__vars['canLinkAlbums']) {
		$__compilerTemp2[] = array(
			'value' => 'existing',
			'label' => 'Link existing albums',
			'_dependent' => array('
                            <div class="linkAlbums-fields">
                                ' . $__templater->formTextBox(array(
			'name' => 'album_ids[]',
			'ac' => 'single',
			'data-xf-init' => 'field-adder',
			'placeholder' => 'Enter album title to find' . $__vars['xf']['language']['ellipsis'],
			'data-acurl' => $__templater->func('link', array('media/albums/find', ), false),
		)) . '
                            </div>
                        '),
			'_type' => 'option',
		);
	}
	$__finalCompiled .= $__templater->form('
    <div class="block-container">
        <div class="block-body">
            ' . $__templater->formRadioRow(array(
		'name' => 'action',
		'value' => ($__vars['canAddAlbums'] ? 'new' : 'existing'),
	), $__compilerTemp2, array(
		'label' => 'Add album type',
	)) . '
        </div>

        ' . $__templater->formSubmitRow(array(
		'submit' => 'Continue' . $__vars['xf']['language']['ellipsis'],
	), array(
	)) . '
    </div>
', array(
		'action' => $__templater->func('link', array('groups/media-add', $__vars['group'], ), false),
		'ajax' => 'true',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);
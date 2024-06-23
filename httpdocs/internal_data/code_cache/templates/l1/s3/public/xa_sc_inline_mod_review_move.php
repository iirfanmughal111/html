<?php
// FROM HASH: 5b714f44d3b2f6c406eed8ce6107ba64
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Inline moderation - Move reviews');
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__templater->isTraversable($__vars['reviews'])) {
		foreach ($__vars['reviews'] AS $__vars['review']) {
			$__compilerTemp1 .= '
		' . $__templater->formHiddenVal('ids[]', $__vars['review']['rating_id'], array(
			)) . '
	';
		}
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body js-prefixListenContainer">
			' . $__templater->formInfoRow('Are you sure you want to move ' . $__templater->escape($__vars['total']) . ' reviews(s) to a new item?', array(
		'rowtype' => 'confirm',
	)) . '

			' . $__templater->formRadioRow(array(
		'name' => 'item_type',
		'value' => $__vars['type'],
	), array(array(
		'value' => 'existing',
		'checked' => 'checked',
		'labelclass' => 'u-featuredText',
		'label' => 'Existing item',
		'_dependent' => array('
						<label>' . 'Item URL' . $__vars['xf']['language']['label_separator'] . '</label>
						' . $__templater->formTextBox(array(
		'name' => 'existing_url',
		'type' => 'url',
	)) . '
					'),
		'_type' => 'option',
	)), array(
	)) . '

			' . $__templater->callMacro('helper_action', 'author_alert', array(
		'selected' => ($__vars['total'] == 1),
	), $__vars) . '
		</div>
		' . $__templater->formSubmitRow(array(
		'icon' => 'move',
	), array(
	)) . '
	</div>

	' . $__compilerTemp1 . '

	' . $__templater->formHiddenVal('type', 'sc_rating', array(
	)) . '
	' . $__templater->formHiddenVal('action', 'move', array(
	)) . '
	' . $__templater->formHiddenVal('confirmed', '1', array(
	)) . '

	' . $__templater->func('redirect_input', array($__vars['redirect'], null, true)) . '
', array(
		'action' => $__templater->func('link', array('inline-mod', ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);
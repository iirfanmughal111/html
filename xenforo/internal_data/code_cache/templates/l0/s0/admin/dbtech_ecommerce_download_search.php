<?php
// FROM HASH: 46f245158af03331168c75ac774484c2
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Search downloads');
	$__finalCompiled .= '

';
	$__compilerTemp1 = $__templater->mergeChoiceOptions(array(), $__vars['sortOrders']);
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formRow('
				<div class="inputGroup">
					' . $__templater->formDateInput(array(
		'name' => 'criteria[release_date][start]',
		'value' => $__vars['criteria']['release_date']['start'],
		'size' => '15',
	)) . '
					<span class="inputGroup-text">-</span>
					' . $__templater->formDateInput(array(
		'name' => 'criteria[release_date][end]',
		'value' => $__vars['criteria']['release_date']['end'],
		'size' => '15',
	)) . '
				</div>
			', array(
		'rowtype' => 'input',
		'label' => 'Released between',
	)) . '

			' . $__templater->formCheckBoxRow(array(
		'name' => 'criteria[download_state]',
	), array(array(
		'value' => 'visible',
		'selected' => $__templater->func('in_array', array('visible', $__vars['criteria']['download_state'], ), false),
		'label' => 'Visible',
		'_type' => 'option',
	),
	array(
		'value' => 'deleted',
		'selected' => $__templater->func('in_array', array('deleted', $__vars['criteria']['download_state'], ), false),
		'label' => 'Deleted',
		'_type' => 'option',
	),
	array(
		'value' => 'moderated',
		'selected' => $__templater->func('in_array', array('moderated', $__vars['criteria']['download_state'], ), false),
		'label' => 'Moderated',
		'_type' => 'option',
	)), array(
		'label' => 'State',
	)) . '

			' . $__templater->callMacro('public:dbtech_ecommerce_product_macros', 'product_edit', array(
		'inputName' => 'criteria[product_id]',
		'productId' => '',
		'includeBlank' => false,
		'includeAny' => true,
		'downloadsOnly' => true,
	), $__vars) . '

			<hr class="formRowSep" />

			' . $__templater->formRow('

				<div class="inputPair">
					' . $__templater->formSelect(array(
		'name' => 'order',
	), $__compilerTemp1) . '
					' . $__templater->formSelect(array(
		'name' => 'direction',
		'value' => 'desc',
	), array(array(
		'value' => 'asc',
		'label' => 'Ascending',
		'_type' => 'option',
	),
	array(
		'value' => 'desc',
		'label' => 'Descending',
		'_type' => 'option',
	))) . '
				</div>
			', array(
		'rowtype' => 'input',
		'label' => 'Sort',
	)) . '
		</div>
		' . $__templater->formSubmitRow(array(
		'sticky' => 'true',
		'icon' => 'search',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('dbtech-ecommerce/downloads', ), false),
		'class' => 'block',
	));
	return $__finalCompiled;
}
);
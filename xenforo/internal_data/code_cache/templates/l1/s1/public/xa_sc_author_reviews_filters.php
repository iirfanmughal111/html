<?php
// FROM HASH: 0512e55fbceae35972bf0f576a5b40d2
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__compilerTemp1 = array(array(
		'value' => '-1',
		'label' => 'Any',
		'_type' => 'option',
	));
	$__compilerTemp2 = $__templater->func('range', array(5, 1, ), false);
	if ($__templater->isTraversable($__compilerTemp2)) {
		foreach ($__compilerTemp2 AS $__vars['rating']) {
			$__compilerTemp1[] = array(
				'value' => $__vars['rating'],
				'label' => '' . $__templater->escape($__vars['rating']) . ' star(s)',
				'_type' => 'option',
			);
		}
	}
	$__finalCompiled .= $__templater->form('
	
	' . '
	<div class="menu-row menu-row--separated">
		<label for="ctrl_rating">' . 'Rating' . $__vars['xf']['language']['label_separator'] . '</label>
		<div class="u-inputSpacer">
			' . $__templater->formSelect(array(
		'name' => 'rating',
		'value' => $__vars['filters']['rating'],
		'id' => 'ctrl_rating',
	), $__compilerTemp1) . '
		</div>
	</div>	

	' . '
	<div class="menu-row menu-row--separated">
		' . 'Reviews that mention' . $__vars['xf']['language']['label_separator'] . '
		<div class="u-inputSpacer">
			' . $__templater->formTextBox(array(
		'name' => 'term',
		'value' => $__vars['filters']['term'],
	)) . '
		</div>
	</div>	

	' . '
	<div class="menu-row menu-row--separated">
		' . 'Sort by' . $__vars['xf']['language']['label_separator'] . '
		<div class="inputGroup u-inputSpacer">
			' . $__templater->formSelect(array(
		'name' => 'order',
		'value' => ($__vars['filters']['order'] ?: 'review_date'),
	), array(array(
		'value' => 'review_date',
		'label' => 'Date',
		'_type' => 'option',
	),
	array(
		'value' => 'vote_score',
		'label' => 'Most helpful',
		'_type' => 'option',
	),
	array(
		'value' => 'rating',
		'label' => 'Rating',
		'_type' => 'option',
	))) . '
			<span class="inputGroup-splitter"></span>
			' . $__templater->formSelect(array(
		'name' => 'direction',
		'value' => ($__vars['filters']['direction'] ?: 'desc'),
	), array(array(
		'value' => 'desc',
		'label' => 'Descending',
		'_type' => 'option',
	),
	array(
		'value' => 'asc',
		'label' => 'Ascending',
		'_type' => 'option',
	))) . '
		</div>
	</div>

	<div class="menu-footer">
		<span class="menu-footer-controls">
			' . $__templater->button('Filter', array(
		'type' => 'submit',
		'class' => 'button--primary',
	), '', array(
	)) . '
		</span>
	</div>
	' . $__templater->formHiddenVal('apply', '1', array(
	)) . '
', array(
		'action' => $__templater->func('link', array('showcase/authors/reviews-filters', $__vars['user'], ), false),
	));
	return $__finalCompiled;
}
);
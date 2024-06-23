<?php
// FROM HASH: 0b96f48f9f2f89d56cf560ce3e6ad132
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => $__vars['formBaseKey'] . '[author_rating]',
		'selected' => $__vars['property']['property_value']['author_rating'],
		'label' => '
		' . 'Author rating' . '
	',
		'_type' => 'option',
	),
	array(
		'name' => $__vars['formBaseKey'] . '[rating_avg]',
		'selected' => $__vars['property']['property_value']['rating_avg'],
		'label' => '
		' . 'Avg rating' . '
	',
		'_type' => 'option',
	),
	array(
		'name' => $__vars['formBaseKey'] . '[username]',
		'selected' => $__vars['property']['property_value']['username'],
		'label' => '
		' . 'Username' . '
	',
		'_type' => 'option',
	),
	array(
		'name' => $__vars['formBaseKey'] . '[create_date]',
		'selected' => $__vars['property']['property_value']['create_date'],
		'label' => '
		' . 'Create date | Last update' . '
	',
		'_type' => 'option',
	),
	array(
		'name' => $__vars['formBaseKey'] . '[category]',
		'selected' => $__vars['property']['property_value']['category'],
		'label' => '
		' . 'Category' . '
	',
		'_type' => 'option',
	),
	array(
		'name' => $__vars['formBaseKey'] . '[share_this_item]',
		'selected' => $__vars['property']['property_value']['share_this_item'],
		'label' => '
		' . 'Share this item' . '
	',
		'_type' => 'option',
	)), array(
		'rowclass' => $__vars['rowClass'],
		'label' => $__templater->escape($__vars['titleHtml']),
		'hint' => $__templater->escape($__vars['hintHtml']),
		'explain' => $__templater->escape($__vars['property']['description']),
	));
	return $__finalCompiled;
}
);
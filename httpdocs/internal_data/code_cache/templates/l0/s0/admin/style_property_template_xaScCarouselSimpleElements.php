<?php
// FROM HASH: 7b5bf9cd153270406467b22d4e11380a
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => $__vars['formBaseKey'] . '[cover_image]',
		'selected' => $__vars['property']['property_value']['cover_image'],
		'label' => '
		' . 'Cover image' . '
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
		'name' => $__vars['formBaseKey'] . '[title]',
		'selected' => $__vars['property']['property_value']['title'],
		'label' => '
		' . 'Title' . '
	',
		'_type' => 'option',
	),
	array(
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
		'name' => $__vars['formBaseKey'] . '[preview_snippet]',
		'selected' => $__vars['property']['property_value']['preview_snippet'],
		'label' => '
		' . 'Item preview snippet' . '
	',
		'_type' => 'option',
	),
	array(
		'name' => $__vars['formBaseKey'] . '[author]',
		'selected' => $__vars['property']['property_value']['author'],
		'label' => '
		' . 'Author' . '
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
		'name' => $__vars['formBaseKey'] . '[view_count]',
		'selected' => $__vars['property']['property_value']['view_count'],
		'label' => '
		' . 'Views' . '
	',
		'_type' => 'option',
	),
	array(
		'name' => $__vars['formBaseKey'] . '[reaction_score]',
		'selected' => $__vars['property']['property_value']['reaction_score'],
		'label' => '
		' . 'Reaction score' . '
	',
		'_type' => 'option',
	),
	array(
		'name' => $__vars['formBaseKey'] . '[update_count]',
		'selected' => $__vars['property']['property_value']['update_count'],
		'label' => '
		' . 'Updates' . '
	',
		'_type' => 'option',
	),
	array(
		'name' => $__vars['formBaseKey'] . '[review_count]',
		'selected' => $__vars['property']['property_value']['review_count'],
		'label' => '
		' . 'Reviews' . '
	',
		'_type' => 'option',
	),
	array(
		'name' => $__vars['formBaseKey'] . '[comment_count]',
		'selected' => $__vars['property']['property_value']['comment_count'],
		'label' => '
		' . 'Comments' . '
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
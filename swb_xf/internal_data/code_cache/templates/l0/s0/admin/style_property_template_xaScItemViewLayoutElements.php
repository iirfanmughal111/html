<?php
// FROM HASH: b842358b540f8ef66dfb57bc9a8b8008
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->formCheckBoxRow(array(
	), array(array(
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
	),
	array(
		'name' => $__vars['formBaseKey'] . '[cover_image]',
		'selected' => $__vars['property']['property_value']['cover_image'],
		'label' => '
		' . 'Cover image' . '
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
		'name' => $__vars['formBaseKey'] . '[custom_fields]',
		'selected' => $__vars['property']['property_value']['custom_fields'],
		'label' => '
		' . 'Custom fields' . '
	',
		'_type' => 'option',
	),
	array(
		'name' => $__vars['formBaseKey'] . '[tags]',
		'selected' => $__vars['property']['property_value']['tags'],
		'label' => '
		' . 'Tags' . '
	',
		'_type' => 'option',
	),
	array(
		'name' => $__vars['formBaseKey'] . '[view_item_button]',
		'selected' => $__vars['property']['property_value']['view_item_button'],
		'label' => '
		' . 'View item button' . '
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
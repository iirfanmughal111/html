<?php
// FROM HASH: 37eea2bc51819e2cea7f7010237689d4
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<hr class="formRowSep" />

' . $__templater->formNumberBoxRow(array(
		'name' => 'options[limit]',
		'value' => $__vars['options']['limit'],
		'min' => '1',
	), array(
		'label' => 'Maximum entries',
	)) . '

' . $__templater->formRadioRow(array(
		'name' => 'options[style]',
		'value' => ($__vars['options']['style'] ?: 'simple'),
	), array(array(
		'value' => 'simple',
		'label' => 'Simple',
		'hint' => 'A simple view, designed for narrow spaces such as sidebars.',
		'_type' => 'option',
	),
	array(
		'value' => 'simple_carousel',
		'label' => 'Simple' . ' - ' . 'Carousel',
		'hint' => 'A simple view, designed for narrow spaces such as sidebars, displaying items in a carousel style slider.',
		'_type' => 'option',
	),
	array(
		'value' => 'carousel',
		'label' => 'Full' . ' - ' . 'Carousel',
		'hint' => 'A full size view, displaying featured items in a carousel style slider.',
		'_type' => 'option',
	),
	array(
		'value' => 'featured_grid',
		'label' => 'Full' . ' - ' . 'Grid block',
		'hint' => 'A full size view, displaying featured items in a grid block container.',
		'_type' => 'option',
	),
	array(
		'value' => 'list_view',
		'label' => 'Full' . ' - ' . 'List view',
		'hint' => 'A full size view, displaying as a standard item list.',
		'_type' => 'option',
	),
	array(
		'value' => 'grid_view',
		'label' => 'Full' . ' - ' . 'Grid view',
		'hint' => 'A full size view, displaying as a standard item list.',
		'_type' => 'option',
	),
	array(
		'value' => 'tile_view',
		'label' => 'Full' . ' - ' . 'Tile view',
		'hint' => 'A full size view, displaying as a standard item list.',
		'_type' => 'option',
	),
	array(
		'value' => 'item_view',
		'label' => 'Full' . ' - ' . 'Item view',
		'hint' => 'A full size view, displaying as a standard item list.',
		'_type' => 'option',
	)), array(
		'label' => 'Display style',
	)) . '

' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'options[require_cover_or_content_image]',
		'value' => '1',
		'selected' => $__vars['options']['require_cover_or_content_image'],
		'label' => 'Require cover or content image',
		'hint' => 'Only items that have a cover image set or are in a category that has a content image set, will be fetched. ',
		'_type' => 'option',
	)), array(
	)) . '

' . $__templater->formTokenInputRow(array(
		'name' => 'options[tags]',
		'value' => $__vars['options']['tags'],
		'href' => $__templater->func('link_type', array('public', 'misc/tag-auto-complete', ), false),
	), array(
		'label' => 'Tags',
		'explain' => 'Only items that have these tags applied to them will be fetched.  ',
	)) . '

';
	$__compilerTemp1 = array(array(
		'value' => '0',
		'label' => $__vars['xf']['language']['parenthesis_open'] . 'Any' . $__vars['xf']['language']['parenthesis_close'],
		'_type' => 'option',
	));
	if ($__templater->isTraversable($__vars['prefixGroups'])) {
		foreach ($__vars['prefixGroups'] AS $__vars['groupId'] => $__vars['prefixGroup']) {
			if (($__templater->func('count', array($__vars['prefixesGrouped'][$__vars['groupId']], ), false) > 0)) {
				$__compilerTemp1[] = array(
					'label' => $__templater->func('prefix_group', array('sc_item', $__vars['groupId'], ), false),
					'_type' => 'optgroup',
					'options' => array(),
				);
				end($__compilerTemp1); $__compilerTemp2 = key($__compilerTemp1);
				if ($__templater->isTraversable($__vars['prefixesGrouped'][$__vars['groupId']])) {
					foreach ($__vars['prefixesGrouped'][$__vars['groupId']] AS $__vars['prefixId'] => $__vars['prefix']) {
						$__compilerTemp1[$__compilerTemp2]['options'][] = array(
							'value' => $__vars['prefixId'],
							'label' => $__templater->func('prefix_title', array('sc_item', $__vars['prefixId'], ), true),
							'_type' => 'option',
						);
					}
				}
			}
		}
	}
	$__finalCompiled .= $__templater->formSelectRow(array(
		'name' => 'options[item_prefix_ids][]',
		'size' => '7',
		'multiple' => 'true',
		'value' => ($__vars['options']['item_prefix_ids'] ?: 0),
	), $__compilerTemp1, array(
		'label' => 'Prefixes',
	)) . '

';
	$__compilerTemp3 = array(array(
		'value' => '0',
		'label' => 'All categories or contextual category',
		'_type' => 'option',
	));
	$__compilerTemp4 = $__templater->method($__vars['categoryTree'], 'getFlattened', array(0, ));
	if ($__templater->isTraversable($__compilerTemp4)) {
		foreach ($__compilerTemp4 AS $__vars['treeEntry']) {
			$__compilerTemp3[] = array(
				'value' => $__vars['treeEntry']['record']['category_id'],
				'label' => '
			' . $__templater->filter($__templater->func('repeat', array('&nbsp;&nbsp;', $__vars['treeEntry']['depth'], ), false), array(array('raw', array()),), true) . $__templater->escape($__vars['treeEntry']['record']['title']) . '
		',
				'_type' => 'option',
			);
		}
	}
	$__finalCompiled .= $__templater->formSelectRow(array(
		'name' => 'options[item_category_ids][]',
		'value' => ($__vars['options']['item_category_ids'] ?: 0),
		'multiple' => 'multiple',
		'size' => '7',
	), $__compilerTemp3, array(
		'label' => 'Category limit',
		'explain' => 'If no categories are explicitly selected, this widget will pull from all categories unless used within a Showcase category. In this case, the content will be limited to that category and descendents.',
	));
	return $__finalCompiled;
}
);
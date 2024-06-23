<?php
// FROM HASH: 4cf8993c42eb99d80f28480c6edf290a
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => $__vars['inputName'] . '[enable_full_page_map]',
		'selected' => $__vars['option']['option_value']['enable_full_page_map'],
		'label' => 'Enable full page map',
		'hint' => 'This option requires a Google Maps Geocoding API Key and a Google Maps JavaScript API Key. These API Keys must be set in the Showcase Map Options!',
		'data-hide' => 'true',
		'_dependent' => array('
			<div class="inputGroup" style="margin-top: 10px;">
				<span class="inputGroup-text">
					' . 'Sort by' . $__vars['xf']['language']['label_separator'] . '
				</span>
				' . $__templater->formSelect(array(
		'name' => $__vars['inputName'] . '[marker_fetch_order]',
		'value' => $__vars['option']['option_value']['marker_fetch_order'],
	), array(array(
		'value' => 'rating_weighted',
		'label' => 'Rating',
		'_type' => 'option',
	),
	array(
		'value' => 'reaction_score',
		'label' => 'Reaction score',
		'_type' => 'option',
	),
	array(
		'value' => 'view_count',
		'label' => 'Views',
		'_type' => 'option',
	),
	array(
		'value' => 'create_date',
		'label' => 'Create date',
		'_type' => 'option',
	),
	array(
		'value' => 'last_update',
		'label' => 'Last update',
		'_type' => 'option',
	))) . '
			</div>

			<div class="inputGroup" style="margin-top: 15px;">
				<span class="inputGroup-text">
					' . 'Marker limit' . $__vars['xf']['language']['label_separator'] . '
				</span>
				' . $__templater->formNumberBox(array(
		'name' => $__vars['inputName'] . '[marker_limit]',
		'value' => ($__vars['option']['option_value']['marker_limit'] ? $__vars['option']['option_value']['marker_limit'] : 100),
		'min' => '1',
		'size' => '5',
	)) . '
			</div>
		'),
		'_type' => 'option',
	)), array(
		'label' => $__templater->escape($__vars['option']['title']),
		'hint' => $__templater->escape($__vars['hintHtml']),
		'explain' => $__templater->escape($__vars['explainHtml']),
		'html' => $__templater->escape($__vars['listedHtml']),
	));
	return $__finalCompiled;
}
);
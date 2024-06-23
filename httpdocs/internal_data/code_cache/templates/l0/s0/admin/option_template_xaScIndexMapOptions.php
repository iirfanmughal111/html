<?php
// FROM HASH: e64062f16ca45edb826f86e26e333c17
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => $__vars['inputName'] . '[enable_map]',
		'selected' => $__vars['option']['option_value']['enable_map'],
		'label' => 'Enable map',
		'hint' => 'This option requires a Google Maps Geocoding API Key and a Google Maps JavaScript API Key. These API Keys must be set in the Showcase Map Options!',
		'data-hide' => 'true',
		'_dependent' => array('
			' . $__templater->formRadio(array(
		'name' => $__vars['inputName'] . '[map_display_location]',
		'value' => ($__vars['option']['option_value']['map_display_location'] ? $__vars['option']['option_value']['map_display_location'] : 'above_listing'),
	), array(array(
		'value' => 'above_listing',
		'label' => 'Location: Above listing',
		'_type' => 'option',
	),
	array(
		'value' => 'below_listing',
		'label' => 'Location: Below listing',
		'_type' => 'option',
	))) . '

			<div class="inputGroup" style="margin-top: 10px;">
				<span class="inputGroup-text">
					' . 'Container height' . $__vars['xf']['language']['label_separator'] . '
				</span>
				' . $__templater->formNumberBox(array(
		'name' => $__vars['inputName'] . '[container_height]',
		'value' => ($__vars['option']['option_value']['container_height'] ? $__vars['option']['option_value']['container_height'] : 400),
		'min' => '200',
		'max' => '800',
		'step' => '10',
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
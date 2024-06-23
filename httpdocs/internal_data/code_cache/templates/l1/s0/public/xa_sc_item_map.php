<?php
// FROM HASH: b1181f6ab5050b518b62bb3202b3727c
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped($__templater->func('prefix', array('sc_item', $__vars['item'], 'escaped', ), true) . ($__vars['item']['meta_title'] ? $__templater->escape($__vars['item']['meta_title']) : $__templater->escape($__vars['item']['title'])) . ' - ' . 'Map');
	$__finalCompiled .= '

';
	$__compilerTemp1 = $__vars;
	$__compilerTemp1['pageSelected'] = 'map';
	$__templater->wrapTemplate('xa_sc_item_wrapper', $__compilerTemp1);
	$__finalCompiled .= '

';
	if ($__vars['item']['location'] AND ($__vars['category']['allow_location'] AND (($__vars['xf']['options']['xaScLocationDisplayType'] == 'map_own_tab') AND $__vars['xf']['options']['xaScGoogleMapsEmbedApiKey']))) {
		$__finalCompiled .= '
	<div class="block">
		';
		$__compilerTemp2 = '';
		$__compilerTemp2 .= '
					' . $__templater->callMacro('xa_sc_item_wrapper_macros', 'action_buttons', array(
			'item' => $__vars['item'],
		), $__vars) . '
				';
		if (strlen(trim($__compilerTemp2)) > 0) {
			$__finalCompiled .= '
			<div class="block-outer">
				<div class="block-outer-opposite">
				' . $__compilerTemp2 . '
				</div>
			</div>
		';
		}
		$__finalCompiled .= '

		<div class="block-container">
			<h3 class="block-header">' . 'Location' . '</h3>
			<div class="block-body block-row contentRow-lesser">
				<p class="mapLocationName"><a href="' . $__templater->func('link', array('misc/location-info', '', array('location' => $__vars['item']['location'], ), ), true) . '" rel="nofollow" target="_blank" class="">' . $__templater->escape($__vars['item']['location']) . '</a></p>
			</div>	
			<div class="block-body block-row">
				<div class="mapContainer">
					<iframe
						width="100%" height="600" frameborder="0" style="border: 0"
						src="https://www.google.com/maps/embed/v1/place?key=' . $__templater->escape($__vars['xf']['options']['xaScGoogleMapsEmbedApiKey']) . '&q=' . $__templater->filter($__vars['item']['location'], array(array('censor', array()),), true) . '">
					</iframe>
				</div>
			</div>	
		</div>
	</div>
';
	}
	return $__finalCompiled;
}
);
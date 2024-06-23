<?php
// FROM HASH: 005e506d6ba49ca3fc3e645a3e5af236
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if (!$__templater->test($__vars['mapItems'], 'empty', array())) {
		$__finalCompiled .= '
	';
		$__templater->includeCss('xa_sc.less');
		$__finalCompiled .= '
	
	<div class="block"' . $__templater->func('widget_data', array($__vars['widget'], ), true) . ' data-type="sc_item">
		<div class="block-container">
			<h3 class="block-header">
				<a href="' . $__templater->escape($__vars['link']) . '" rel="nofollow">' . ($__templater->escape($__vars['title']) ?: 'Map') . '</a>
			</h3>
			<div class="block-body">
				<div class="block-row">			
					' . $__templater->callMacro('xa_sc_map_macros', 'items_map', array(
			'mapItems' => $__vars['mapItems'],
			'mapId' => 'sc-map-widget-' . $__vars['widget']['id'],
			'containerHeight' => ($__vars['container_height'] ?: 400),
		), $__vars) . '					
				</div>
			</div>
		</div>
	</div>
';
	}
	return $__finalCompiled;
}
);
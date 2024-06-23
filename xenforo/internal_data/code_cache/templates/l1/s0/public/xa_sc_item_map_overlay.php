<?php
// FROM HASH: c069e718c9f59279668e8d37bb4c0757
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped($__templater->func('prefix', array('sc_item', $__vars['item'], 'escaped', ), true) . $__templater->escape($__vars['item']['title']));
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['item'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

';
	if ($__vars['item']['location'] AND ($__vars['category']['allow_location'] AND $__vars['xf']['options']['xaScGoogleMapsEmbedApiKey'])) {
		$__finalCompiled .= '
	<div class="block">
		<div class="block-container">
			<div class="block-body block-row">
				<div class="mapContainer">
					<iframe
						width="100%" height="600" frameborder="0" style="border: 0"
						src="https://www.google.com/maps/embed/v1/place?key=' . $__templater->escape($__vars['xf']['options']['xaScGoogleMapsEmbedApiKey']) . '&q=' . $__templater->filter($__vars['item']['location'], array(array('censor', array()),), true) . ($__vars['xf']['options']['xaScLocalizeGoogleMaps'] ? ('&language=' . $__templater->filter($__vars['xf']['language']['language_code'], array(array('substr', array()),), true)) : '') . '">
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
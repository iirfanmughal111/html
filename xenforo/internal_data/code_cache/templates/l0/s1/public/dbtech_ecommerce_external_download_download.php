<?php
// FROM HASH: 8024464a8f40dcee9ce127f7a3af3e08
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__templater->isTraversable($__vars['download']['Product']['product_versions'])) {
		foreach ($__vars['download']['Product']['product_versions'] AS $__vars['version'] => $__vars['text']) {
			if (($__vars['data']['versions'][$__vars['version']] AND ($__vars['license'] ? $__vars['data']['versions'][$__vars['version']]['full'] : $__vars['data']['versions'][$__vars['version']]['demo']))) {
				$__finalCompiled .= '
	<h3 class="block-formSectionHeader">
		' . $__templater->escape($__templater->method($__vars['download']['Product'], 'getVersionLabel', array($__vars['version'], ))) . '
	</h3>
	<ul class="block-body">
		<li class="block-row block-row--separated">
			<div class="contentRow">
				<div class="contentRow-main">
						<span class="contentRow-extra">
							' . $__templater->button('Download', array(
					'href' => $__templater->func('link', array('dbtech-ecommerce/release/download', $__vars['download'], array('version' => $__vars['version'], 'license_key' => ($__vars['license'] ? $__vars['license']['license_key'] : null), ), ), false),
					'icon' => 'download',
				), '', array(
				)) . '
						</span>
					<h3 class="contentRow-title">' . $__templater->escape($__vars['download']['title']) . '</h3>
					<div class="contentRow-minor">
						' . $__templater->func('date_dynamic', array($__vars['download']['release_date'], array(
					'data-full-date' => 'true',
				))) . '
					</div>
				</div>
			</div>
		</li>
	</ul>
';
			}
		}
	}
	return $__finalCompiled;
}
);
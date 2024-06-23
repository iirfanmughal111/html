<?php
// FROM HASH: ad21ef8953fec412da5ae9e0792644bb
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__templater->isTraversable($__vars['download']['Product']['product_versions'])) {
		foreach ($__vars['download']['Product']['product_versions'] AS $__vars['version'] => $__vars['text']) {
			if (($__vars['data']['versions'][$__vars['version']] AND ($__vars['license'] ? $__vars['data']['versions'][$__vars['version']]['full'] : $__vars['data']['versions'][$__vars['version']]['demo']))) {
				$__finalCompiled .= '
	';
				$__compilerTemp1 = '';
				$__compilerTemp1 .= '
				';
				$__compilerTemp2 = ($__vars['license'] ? $__vars['data']['versions'][$__vars['version']]['full'] : $__vars['data']['versions'][$__vars['version']]['demo']);
				if ($__templater->isTraversable($__compilerTemp2)) {
					foreach ($__compilerTemp2 AS $__vars['file']) {
						$__compilerTemp1 .= '
					<li class="block-row block-row--separated">
						<div class="contentRow">
							<div class="contentRow-main">
								<span class="contentRow-extra">
									' . $__templater->button('Download', array(
							'href' => $__templater->func('link', array('dbtech-ecommerce/download-version', array('version_id' => $__vars['version'], 'license_key' => $__vars['license']['license_key'], ), array('download_id' => $__vars['download']['download_id'], 'file' => $__vars['file']['attachment_id'], ), ), false),
							'icon' => 'download',
						), '', array(
						)) . '
								</span>
								<h3 class="contentRow-title">' . $__templater->escape($__vars['file']['filename']) . '</h3>
								<div class="contentRow-minor">
									' . $__templater->filter($__vars['file']['file_size'], array(array('file_size', array()),), true) . '
								</div>
								<div class="contentRow-minor">
									' . $__templater->func('date_dynamic', array($__vars['download']['release_date'], array(
							'data-full-date' => 'true',
						))) . '
								</div>
							</div>
						</div>
					</li>
				';
					}
				}
				$__compilerTemp1 .= '
			';
				if (strlen(trim($__compilerTemp1)) > 0) {
					$__finalCompiled .= '
		<h3 class="block-formSectionHeader">
			' . $__templater->escape($__templater->method($__vars['download']['Product'], 'getVersionLabel', array($__vars['version'], ))) . '
		</h3>
		<ul class="block-body">
			' . $__compilerTemp1 . '
		</ul>
	';
				}
				$__finalCompiled .= '
';
			}
		}
	}
	return $__finalCompiled;
}
);
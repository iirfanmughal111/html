<?php
// FROM HASH: 3fa59819f5e229db9257ca97cb0709ed
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped($__templater->func('prefix', array('dbtechEcommerceProduct', $__vars['product'], 'escaped', ), true) . $__templater->escape($__vars['product']['title']) . ' - ' . 'Downloads');
	$__finalCompiled .= '
';
	$__templater->pageParams['pageH1'] = $__templater->preEscaped($__templater->func('prefix', array('dbtechEcommerceProduct', $__vars['product'], ), true) . $__templater->escape($__vars['product']['title']) . ' - ' . 'Downloads');
	$__finalCompiled .= '

';
	$__vars['type'] = ($__vars['license'] ? 'full' : 'demo');
	$__finalCompiled .= '

';
	if ($__templater->isTraversable($__vars['product']['product_versions'])) {
		foreach ($__vars['product']['product_versions'] AS $__vars['version'] => $__vars['text']) {
			$__finalCompiled .= '
	';
			$__vars['selected'] = null;
			$__compilerTemp1 = array(array(
				'value' => '0',
				'label' => $__vars['xf']['language']['parenthesis_open'] . 'None' . $__vars['xf']['language']['parenthesis_close'],
				'_type' => 'option',
			));
			if ($__templater->isTraversable($__vars['downloads'])) {
				foreach ($__vars['downloads'] AS $__vars['downloadId'] => $__vars['download']) {
					if (($__templater->method($__vars['download'], 'canDownload', array($__vars['license'], )) AND !$__templater->test($__vars['download']['DownloadData']['versions'][$__vars['version']][$__vars['type']], 'empty', array()))) {
						if (!$__vars['download']['is_unstable']) {
							$__vars['selected'] = (($__vars['selected'] === null) ? true : false);
						}
						$__compilerTemp1[] = array(
							'value' => $__vars['downloadId'],
							'selected' => ($__vars['selected'] AND ((!$__vars['download']['is_unstable']) ? true : false)),
							'label' => ($__templater->escape($__vars['download']['version_string']) . ($__vars['download']['is_unstable'] ? (' ' . $__vars['xf']['language']['parenthesis_open'] . 'Unstable' . $__vars['xf']['language']['parenthesis_close']) : '')),
							'_type' => 'option',
						);
					}
				}
			}
			$__finalCompiled .= $__templater->form('
		<div class="block-container">
			<div class="block-body">
				<h3 class="block-formSectionHeader">' . $__templater->escape($__vars['text']) . '</h3>

				' . '' . '

				' . $__templater->formSelectRow(array(
				'name' => 'download_id',
			), $__compilerTemp1, array(
				'label' => 'Product version',
			)) . '
			</div>

			' . $__templater->formHiddenVal('version', $__vars['version'], array(
			)) . '
			' . $__templater->formHiddenVal('license_key', $__vars['license']['license_key'], array(
			)) . '

			' . $__templater->formSubmitRow(array(
				'icon' => 'download',
				'submit' => 'Download',
			), array(
			)) . '
		</div>
	', array(
				'action' => $__templater->func('link', array('dbtech-ecommerce/download-version', array('version_id' => $__vars['version'], 'license_key' => $__vars['license']['license_key'], ), ), false),
				'class' => 'block',
			)) . '
';
		}
	}
	return $__finalCompiled;
}
);
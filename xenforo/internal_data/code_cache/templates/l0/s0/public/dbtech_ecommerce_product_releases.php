<?php
// FROM HASH: 823325c3d7e0bfa2914536169a3c4f40
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if (!$__vars['_noWrap']) {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped($__templater->func('prefix', array('dbtechEcommerceProduct', $__vars['product'], 'escaped', ), true) . $__templater->escape($__vars['product']['title']) . ' - ' . 'Releases');
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageH1'] = $__templater->preEscaped($__templater->func('prefix', array('dbtechEcommerceProduct', $__vars['product'], ), true) . $__templater->escape($__vars['product']['title']) . ' - ' . 'Releases');
		$__finalCompiled .= '

	';
		$__compilerTemp1 = $__vars;
		$__compilerTemp1['pageSelected'] = 'releases';
		$__templater->wrapTemplate('dbtech_ecommerce_product_wrapper', $__compilerTemp1);
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

<span class="u-anchorTarget" id="releases"></span>

';
	if ($__vars['_noWrap']) {
		$__finalCompiled .= '
	<div class="block">
		<div class="block-container">
			<h2 class="block-formSectionHeader">' . 'Available downloads' . ($__vars['_header'] ? (' - ' . $__templater->escape($__vars['_header'])) : '') . '</h2>
';
	}
	$__finalCompiled .= '
<div class="block-body">
	';
	$__compilerTemp2 = array(array(
		'_type' => 'cell',
		'html' => 'Version',
	)
,array(
		'_type' => 'cell',
		'html' => 'Release date',
	));
	if ($__vars['hasDownload'] AND $__templater->method($__vars['xf']['visitor'], 'hasPermission', array('dbtechEcommerceAdmin', 'viewDownloadLog', ))) {
		$__compilerTemp2[] = array(
			'_type' => 'cell',
			'html' => 'Downloads',
		);
	}
	if ($__vars['hasDownload']) {
		$__compilerTemp2[] = array(
			'_type' => 'cell',
			'html' => '',
		);
	}
	if ($__vars['hasDiscussion']) {
		$__compilerTemp2[] = array(
			'_type' => 'cell',
			'html' => '',
		);
	}
	$__compilerTemp3 = '';
	if ($__templater->isTraversable($__vars['downloads'])) {
		foreach ($__vars['downloads'] AS $__vars['download']) {
			$__compilerTemp3 .= '
			';
			$__compilerTemp4 = '';
			if ($__vars['download']['is_unstable']) {
				$__compilerTemp4 .= '
							' . (' ' . $__vars['xf']['language']['parenthesis_open'] . 'Unstable' . $__vars['xf']['language']['parenthesis_close']) . '
						';
			}
			$__compilerTemp5 = '';
			if (!$__vars['_noWrap']) {
				$__compilerTemp5 .= '
						';
				$__compilerTemp6 = '';
				$__compilerTemp6 .= '
										';
				if ($__templater->isTraversable($__vars['download']['FullVersions'])) {
					foreach ($__vars['download']['FullVersions'] AS $__vars['productVersion'] => $__vars['downloadVersion']) {
						$__compilerTemp6 .= '
											<li>' . $__templater->escape($__templater->method($__vars['product'], 'getVersionLabel', array($__vars['productVersion'], ))) . '</li>
										';
					}
				}
				$__compilerTemp6 .= '
									';
				if (strlen(trim($__compilerTemp6)) > 0) {
					$__compilerTemp5 .= '
							<div class="dataList-subRow" dir="auto">
								<ul class="listInline listInline--bullet listInline--selfInline">
									' . $__compilerTemp6 . '
								</ul>
							</div>
						';
				}
				$__compilerTemp5 .= '
					';
			}
			$__compilerTemp7 = array(array(
				'class' => 'dataList-cell--main',
				'href' => $__templater->func('link', array('dbtech-ecommerce/release', $__vars['download'], array('license_key' => $__vars['license']['license_key'], ), ), false),
				'overlay' => 'true',
				'_type' => 'cell',
				'html' => '
					<div class="dataList-textRow" dir="auto">
						' . $__templater->escape($__vars['download']['version_string']) . '
						' . $__compilerTemp4 . '
					</div>

					' . $__compilerTemp5 . '
				',
			)
,array(
				'_type' => 'cell',
				'html' => $__templater->func('date_dynamic', array($__vars['download']['release_date'], array(
			))),
			));
			if ($__vars['hasDownload'] AND $__templater->method($__vars['xf']['visitor'], 'hasPermission', array('dbtechEcommerceAdmin', 'viewDownloadLog', ))) {
				$__compilerTemp7[] = array(
					'_type' => 'cell',
					'html' => $__templater->filter($__vars['download']['download_count'], array(array('number', array()),), true),
				);
			}
			if ($__vars['hasDownload']) {
				if ($__vars['license']) {
					if ($__templater->method($__vars['download'], 'canDownload', array($__vars['license'], ))) {
						$__compilerTemp7[] = array(
							'href' => $__templater->func('link', array('dbtech-ecommerce/release/download', $__vars['download'], array('license_key' => $__vars['license']['license_key'], ), ), false),
							'_type' => 'action',
							'html' => 'Download',
						);
					} else {
						$__compilerTemp7[] = array(
							'class' => 'dataList-cell--alt',
							'_type' => 'cell',
							'html' => '',
						);
					}
				} else {
					if ($__templater->method($__vars['download'], 'canDownload', array())) {
						$__compilerTemp7[] = array(
							'href' => $__templater->func('link', array('dbtech-ecommerce/release/download', $__vars['download'], ), false),
							'_type' => 'action',
							'html' => 'Download demo',
						);
					} else {
						$__compilerTemp7[] = array(
							'class' => 'dataList-cell--alt',
							'_type' => 'cell',
							'html' => '',
						);
					}
				}
			}
			if ($__vars['hasDiscussion']) {
				if ($__templater->method($__vars['download'], 'hasViewableDiscussion', array())) {
					$__compilerTemp7[] = array(
						'href' => $__templater->func('link', array('threads', $__vars['download']['Discussion'], ), false),
						'_type' => 'action',
						'html' => '
							' . 'Join discussion' . '
						',
					);
				} else {
					$__compilerTemp7[] = array(
						'class' => 'dataList-cell--alt',
						'_type' => 'cell',
						'html' => '',
					);
				}
			}
			$__compilerTemp3 .= $__templater->dataRow(array(
				'rowclass' => (($__vars['download']['download_state'] == 'deleted') ? 'dataList-row--deleted' : ''),
			), $__compilerTemp7) . '
		';
		}
	}
	$__finalCompiled .= $__templater->dataList('
		' . $__templater->dataRow(array(
		'rowtype' => 'header',
	), $__compilerTemp2) . '
		' . $__compilerTemp3 . '
	', array(
		'data-xf-init' => 'responsive-data-list',
	)) . '
</div>
';
	if ($__vars['_noWrap']) {
		$__finalCompiled .= '
	</div>
</div>
';
	}
	return $__finalCompiled;
}
);
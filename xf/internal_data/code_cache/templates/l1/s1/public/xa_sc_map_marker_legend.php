<?php
// FROM HASH: 0288ddfb1d35bab7e725b30d7101c884
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped($__templater->escape($__vars['category']['title']) . ' - ' . 'Map marker legend');
	$__finalCompiled .= '

';
	$__templater->includeCss('xa_sc_map_marker_legend.less');
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['category'], 'getBreadcrumbs', array(true, )));
	$__finalCompiled .= '

<div class="block">
	<div class="block-container">
		<div class="block-body">
			';
	$__compilerTemp1 = '';
	if ($__vars['category']['map_options']['custom_featured_map_marker_url']) {
		$__compilerTemp1 .= '	
							<img src="' . $__templater->func('base_url', array($__vars['category']['map_options']['custom_featured_map_marker_url'], ), true) . '" />
						';
	} else {
		$__compilerTemp1 .= '
							<img src="' . $__templater->func('base_url', array($__vars['xf']['options']['xaScDefaultFeaturedMapMarkerIconUrl'], ), true) . '" />
						';
	}
	$__compilerTemp2 = '';
	if ($__vars['category']['map_options']['custom_map_marker_url']) {
		$__compilerTemp2 .= '	
							<img src="' . $__templater->func('base_url', array($__vars['category']['map_options']['custom_map_marker_url'], ), true) . '" />
						';
	} else {
		$__compilerTemp2 .= '
							<img src="' . $__templater->func('base_url', array($__vars['xf']['options']['xaScDefaultMapMarkerIconUrl'], ), true) . '" />
						';
	}
	$__compilerTemp3 = '';
	if ($__templater->isTraversable($__vars['descendants'])) {
		foreach ($__vars['descendants'] AS $__vars['descendant_category']) {
			$__compilerTemp3 .= '
					';
			$__compilerTemp4 = '';
			if ($__vars['descendant_category']['map_options']['custom_featured_map_marker_url']) {
				$__compilerTemp4 .= '	
								<img src="' . $__templater->func('base_url', array($__vars['descendant_category']['map_options']['custom_featured_map_marker_url'], ), true) . '" />
							';
			} else {
				$__compilerTemp4 .= '
								<img src="' . $__templater->func('base_url', array($__vars['xf']['options']['xaScDefaultFeaturedMapMarkerIconUrl'], ), true) . '" />
							';
			}
			$__compilerTemp3 .= $__templater->dataRow(array(
				'rowclass' => 'dataList-row--noHover',
			), array(array(
				'class' => 'dataList-cell--min dataList-cell--alt',
				'_type' => 'cell',
				'html' => '
							' . $__compilerTemp4 . '
						',
			),
			array(
				'label' => 'Featured' . ' ' . ($__templater->escape($__vars['descendant_category']['content_term']) ?: $__templater->escape($__vars['descendant_category']['title'])),
				'_type' => 'main',
				'html' => '',
			),
			array(
				'class' => 'categoryTitle',
				'_type' => 'cell',
				'html' => '
							<span>
								<span class="categoryTitleText">' . $__templater->escape($__vars['descendant_category']['title']) . '</span>
							</span>
						',
			))) . '

					';
			$__compilerTemp5 = '';
			if ($__vars['descendant_category']['map_options']['custom_map_marker_url']) {
				$__compilerTemp5 .= '	
								<img src="' . $__templater->func('base_url', array($__vars['descendant_category']['map_options']['custom_map_marker_url'], ), true) . '" />
							';
			} else {
				$__compilerTemp5 .= '
								<img src="' . $__templater->func('base_url', array($__vars['xf']['options']['xaScDefaultMapMarkerIconUrl'], ), true) . '" />
							';
			}
			$__compilerTemp3 .= $__templater->dataRow(array(
				'rowclass' => 'dataList-row--noHover',
			), array(array(
				'class' => 'dataList-cell--min dataList-cell--alt',
				'_type' => 'cell',
				'html' => '
							' . $__compilerTemp5 . '
						',
			),
			array(
				'label' => ($__templater->escape($__vars['descendant_category']['content_term']) ?: $__templater->escape($__vars['descendant_category']['title'])),
				'_type' => 'main',
				'html' => '',
			),
			array(
				'class' => 'categoryTitle',
				'_type' => 'cell',
				'html' => '
							<span>
								<span class="categoryTitleText">' . $__templater->escape($__vars['descendant_category']['title']) . '</span>
							</span>
						',
			))) . '
				';
		}
	}
	$__finalCompiled .= $__templater->dataList('
				' . $__templater->dataRow(array(
		'rowtype' => 'header',
	), array(array(
		'class' => 'dataList-cell--min',
		'_type' => 'cell',
		'html' => 'Marker',
	),
	array(
		'_type' => 'cell',
		'html' => 'Title',
	),
	array(
		'class' => 'categoryTitle',
		'_type' => 'cell',
		'html' => 'Category',
	))) . '

				' . $__templater->dataRow(array(
		'rowclass' => 'dataList-row--noHover',
	), array(array(
		'class' => 'dataList-cell--min dataList-cell--alt',
		'_type' => 'cell',
		'html' => '
						' . $__compilerTemp1 . '
					',
	),
	array(
		'label' => 'Featured' . ' ' . ($__templater->escape($__vars['category']['content_term']) ?: $__templater->escape($__vars['category']['title'])),
		'_type' => 'main',
		'html' => '',
	),
	array(
		'class' => 'categoryTitle',
		'_type' => 'cell',
		'html' => '
						<span>
							<span class="categoryTitleText">' . $__templater->escape($__vars['category']['title']) . '</span>
						</span>
					',
	))) . '

				' . $__templater->dataRow(array(
		'rowclass' => 'dataList-row--noHover',
	), array(array(
		'class' => 'dataList-cell--min dataList-cell--alt',
		'_type' => 'cell',
		'html' => '
						' . $__compilerTemp2 . '
					',
	),
	array(
		'label' => ($__templater->escape($__vars['category']['content_term']) ?: $__templater->escape($__vars['category']['title'])),
		'_type' => 'main',
		'html' => '',
	),
	array(
		'class' => 'categoryTitle',
		'_type' => 'cell',
		'html' => '
						<span>
							<span class="categoryTitleText">' . $__templater->escape($__vars['category']['title']) . '</span>
						</span>
					',
	))) . '

				' . $__compilerTemp3 . '
			', array(
		'data-xf-init' => 'responsive-data-list',
	)) . '
		</div>	
	</div>
</div>';
	return $__finalCompiled;
}
);
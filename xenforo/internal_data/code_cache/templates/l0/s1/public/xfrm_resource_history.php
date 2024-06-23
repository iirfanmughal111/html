<?php
// FROM HASH: 5f08255183bd56d2c692c697d97fd974
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped($__templater->func('prefix', array('resource', $__vars['resource'], 'escaped', ), true) . $__templater->escape($__vars['resource']['title']) . ' - ' . 'History');
	$__finalCompiled .= '

';
	$__compilerTemp1 = $__vars;
	$__compilerTemp1['pageSelected'] = 'history';
	$__templater->wrapTemplate('xfrm_resource_wrapper', $__compilerTemp1);
	$__finalCompiled .= '

<div class="block">
	<div class="block-container">
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
	if ($__vars['hasDownload']) {
		$__compilerTemp2[] = array(
			'_type' => 'cell',
			'html' => 'Downloads',
		);
	}
	$__compilerTemp2[] = array(
		'_type' => 'cell',
		'html' => 'Rating',
	);
	if ($__vars['hasDownload'] AND $__templater->method($__vars['resource'], 'canDownload', array())) {
		$__compilerTemp2[] = array(
			'_type' => 'cell',
			'html' => '',
		);
	}
	if ($__vars['hasDelete']) {
		$__compilerTemp2[] = array(
			'_type' => 'cell',
			'html' => '',
		);
	}
	$__compilerTemp3 = '';
	if ($__templater->isTraversable($__vars['versions'])) {
		foreach ($__vars['versions'] AS $__vars['version']) {
			$__compilerTemp3 .= '
					';
			$__compilerTemp4 = '';
			if ($__templater->method($__vars['resource'], 'canViewTeamMembers', array()) AND ($__vars['version']['team_user_id'] AND ($__vars['version']['team_user_id'] != $__vars['resource']['user_id']))) {
				$__compilerTemp4 .= '
								<a role="button"
									tabindex="0"
									data-xf-init="tooltip"
									data-trigger="hover focus click"
									title="' . 'Posted by' . $__vars['xf']['language']['label_separator'] . ' ' . ($__vars['version']['TeamUser'] ? $__templater->escape($__vars['version']['TeamUser']['username']) : $__templater->escape($__vars['version']['team_username'])) . '">' . $__templater->func('trim', array('
									' . $__templater->fontAwesome('fa-info-circle', array(
				)) . '
								'), false) . '</a>
							';
			}
			$__compilerTemp5 = array(array(
				'_type' => 'cell',
				'html' => $__templater->escape($__vars['version']['version_string']),
			)
,array(
				'_type' => 'cell',
				'html' => '
							' . $__templater->func('date_dynamic', array($__vars['version']['release_date'], array(
			))) . '

							' . $__compilerTemp4 . '
						',
			));
			if ($__vars['hasDownload']) {
				$__compilerTemp5[] = array(
					'_type' => 'cell',
					'html' => '
								' . ($__templater->method($__vars['version'], 'isDownloadable', array()) ? $__templater->filter($__vars['version']['download_count'], array(array('number', array()),), true) : 'N/A') . '
							',
				);
			}
			$__compilerTemp5[] = array(
				'_type' => 'cell',
				'html' => '
							' . $__templater->callMacro('rating_macros', 'stars_text', array(
				'rating' => $__vars['version']['rating_avg'],
				'count' => $__vars['version']['rating_count'],
			), $__vars) . '
						',
			);
			if ($__vars['hasDownload'] AND $__templater->method($__vars['resource'], 'canDownload', array())) {
				if ($__templater->method($__vars['version'], 'isDownloadable', array())) {
					$__compilerTemp5[] = array(
						'href' => $__templater->func('link', array('resources/version/download', $__vars['version'], ), false),
						'target' => '_blank',
						'overlay' => ($__vars['version']['file_count'] > 1),
						'_type' => 'action',
						'html' => 'Download',
					);
				} else {
					$__compilerTemp5[] = array(
						'class' => 'dataList-cell--alt',
						'_type' => 'cell',
						'html' => '',
					);
				}
			}
			if ($__vars['hasDelete']) {
				if ($__templater->method($__vars['version'], 'canDelete', array())) {
					$__compilerTemp5[] = array(
						'href' => $__templater->func('link', array('resources/version/delete', $__vars['version'], ), false),
						'_type' => 'delete',
						'html' => '',
					);
				} else {
					$__compilerTemp5[] = array(
						'class' => 'dataList-cell--alt',
						'_type' => 'cell',
						'html' => '',
					);
				}
			}
			$__compilerTemp3 .= $__templater->dataRow(array(
				'rowclass' => (($__vars['version']['version_state'] == 'deleted') ? 'dataList-row--deleted' : ''),
			), $__compilerTemp5) . '
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
	</div>
</div>';
	return $__finalCompiled;
}
);
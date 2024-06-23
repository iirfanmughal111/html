<?php
// FROM HASH: fcd84a2ebec10f9a28982e58a3b80d3a
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->includeCss('public:core_xfmg.less');
	$__finalCompiled .= '

';
	if (!$__templater->test($__vars['items'], 'empty', array())) {
		$__finalCompiled .= '
	';
		$__compilerTemp1 = array();
		if ($__vars['showingAll']) {
			$__compilerTemp1[] = array(
				'class' => 'dataList-cell--min',
				'_type' => 'cell',
				'html' => '
								' . $__templater->formCheckBox(array(
				'standalone' => 'true',
			), array(array(
				'check-all' => '.dataList >',
				'data-xf-init' => 'tooltip',
				'title' => 'Select all',
				'_type' => 'option',
			))) . '
							',
			);
		}
		$__compilerTemp1[] = array(
			'colspan' => '2',
			'_type' => 'cell',
			'html' => 'Title',
		);
		$__compilerTemp1[] = array(
			'class' => 'dataList-cell--min',
			'_type' => 'cell',
			'html' => 'Category',
		);
		if ($__vars['type'] == 'media') {
			$__compilerTemp1[] = array(
				'class' => 'dataList-cell--min',
				'_type' => 'cell',
				'html' => 'Album',
			);
		}
		$__compilerTemp1[] = array(
			'class' => 'dataList-cell--min',
			'_type' => 'cell',
			'html' => 'Author',
		);
		$__compilerTemp1[] = array(
			'class' => 'dataList-cell--min',
			'_type' => 'cell',
			'html' => 'Comment count',
		);
		$__compilerTemp1[] = array(
			'class' => 'dataList-cell--min',
			'_type' => 'cell',
			'html' => 'Date added',
		);
		$__compilerTemp2 = '';
		if ($__templater->isTraversable($__vars['items'])) {
			foreach ($__vars['items'] AS $__vars['item']) {
				$__compilerTemp2 .= '
						';
				$__compilerTemp3 = array();
				if ($__vars['showingAll']) {
					$__compilerTemp3[] = array(
						'name' => 'ids[]',
						'value' => $__templater->method($__vars['item'], 'getEntityId', array()),
						'selected' => true,
						'_type' => 'toggle',
						'html' => '',
					);
				}
				$__compilerTemp3[] = array(
					'class' => 'dataList-cell--min dataList-cell--image',
					'_type' => 'cell',
					'html' => '
								' . $__templater->func('xfmg_thumbnail', array($__vars['item'], 'xfmgThumbnail--smallest', ), true) . '
							',
				);
				if ($__vars['type'] == 'media') {
					$__compilerTemp3[] = array(
						'href' => $__templater->func('link_type', array('public', 'media', $__vars['item'], ), false),
						'label' => $__templater->escape($__vars['item']['title']),
						'_type' => 'main',
						'html' => '',
					);
				} else {
					$__compilerTemp3[] = array(
						'href' => $__templater->func('link_type', array('public', 'media/albums', $__vars['item'], ), false),
						'label' => $__templater->escape($__vars['item']['title']),
						'_type' => 'main',
						'html' => '',
					);
				}
				$__compilerTemp3[] = array(
					'class' => 'dataList-cell--min',
					'_type' => 'cell',
					'html' => '
								' . ($__templater->escape($__vars['item']['Category']['title']) ?: 'N/A') . '
							',
				);
				if ($__vars['type'] == 'media') {
					$__compilerTemp3[] = array(
						'class' => 'dataList-cell--min',
						'_type' => 'cell',
						'html' => '
									' . ($__templater->escape($__vars['item']['Album']['title']) ?: 'N/A') . '
								',
					);
				}
				$__compilerTemp3[] = array(
					'class' => 'dataList-cell--min',
					'_type' => 'cell',
					'html' => '
								' . $__templater->escape($__vars['item']['username']) . '
							',
				);
				$__compilerTemp3[] = array(
					'class' => 'dataList-cell--min',
					'_type' => 'cell',
					'html' => '
								' . $__templater->filter($__vars['item']['comment_count'], array(array('number', array()),), true) . '
							',
				);
				if ($__vars['item']['content_type'] == 'xfmg_media') {
					$__compilerTemp3[] = array(
						'class' => 'dataList-cell--min',
						'_type' => 'cell',
						'html' => '
									' . $__templater->func('date_dynamic', array($__vars['item']['media_date'], array(
					))) . '
								',
					);
				} else {
					$__compilerTemp3[] = array(
						'class' => 'dataList-cell--min',
						'_type' => 'cell',
						'html' => '
									' . $__templater->func('date_dynamic', array($__vars['item']['create_date'], array(
					))) . '
								',
					);
				}
				$__compilerTemp2 .= $__templater->dataRow(array(
				), $__compilerTemp3) . '
					';
			}
		}
		$__compilerTemp4 = '';
		if ($__vars['filter'] AND ($__vars['total'] > $__vars['perPage'])) {
			$__compilerTemp4 .= '
						' . $__templater->dataRow(array(
				'rowclass' => 'dataList-row--note dataList-row--noHover js-filterForceShow',
			), array(array(
				'colspan' => '3',
				'_type' => 'cell',
				'html' => 'There are more records matching your filter. Please be more specific.',
			))) . '
					';
		}
		$__compilerTemp5 = '';
		if ($__vars['showAll']) {
			$__compilerTemp5 .= '
					<span class="block-footer-controls">
						<a href="' . $__templater->func('link', array($__vars['linkPrefix'] . '/list', null, array('criteria' => $__vars['criteria'], 'all' => $__vars['showingAll'], ), ), true) . '">' . 'Show all matches' . '</a>
					</span>
				';
		} else if ($__vars['showingAll']) {
			$__compilerTemp5 .= '
					<span class="block-footer-controls">' . $__templater->button('Batch update', array(
				'type' => 'submit',
			), '', array(
			)) . '</span>
				';
		}
		$__finalCompiled .= $__templater->form('
		<div class="block-container">
			<div class="block-body">
				' . $__templater->dataList('
					' . $__templater->dataRow(array(
			'rowtype' => 'header',
		), $__compilerTemp1) . '
					' . $__compilerTemp2 . '
					' . $__compilerTemp4 . '
				', array(
			'class' => 'dataList--contained',
			'data-xf-init' => 'responsive-data-list',
		)) . '
			</div>
			<div class="block-footer block-footer--split">
				<span class="block-footer-counter">' . $__templater->func('display_totals', array($__vars['items'], $__vars['total'], ), true) . '</span>
				<span class="block-footer-select">' . $__templater->formCheckBox(array(
			'standalone' => 'true',
		), array(array(
			'check-all' => '.dataList',
			'label' => 'Select all',
			'_type' => 'option',
		))) . '</span>
				' . $__compilerTemp5 . '
			</div>
		</div>

		' . $__templater->func('page_nav', array(array(
			'page' => $__vars['page'],
			'total' => $__vars['total'],
			'link' => $__vars['linkPrefix'] . '/list',
			'params' => array('criteria' => $__vars['criteria'], 'order' => $__vars['order'], 'direction' => $__vars['direction'], ),
			'wrapperclass' => 'js-filterHide block-outer block-outer--after',
			'perPage' => $__vars['perPage'],
		))) . '
	', array(
			'action' => $__templater->func('link', array($__vars['linkPrefix'] . '/confirm', ), false),
			'class' => 'block',
			'ajax' => 'true',
			'data-json-name' => 'json',
		)) . '
';
	} else {
		$__finalCompiled .= '
	<div class="blockMessage">' . 'No records matched.' . '</div>
';
	}
	return $__finalCompiled;
}
);
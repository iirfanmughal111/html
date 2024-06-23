<?php
// FROM HASH: a96fc2d14892b0f6ef463a216d5ac891
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Groups');
	$__finalCompiled .= '

';
	if (!$__templater->test($__vars['groups'], 'empty', array())) {
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
			'colspan' => '1',
			'_type' => 'cell',
			'html' => 'Group name',
		);
		$__compilerTemp1[] = array(
			'class' => 'dataList-cell--min',
			'_type' => 'cell',
			'html' => 'Category',
		);
		$__compilerTemp1[] = array(
			'class' => 'dataList-cell--min',
			'_type' => 'cell',
			'html' => 'Owner',
		);
		$__compilerTemp1[] = array(
			'class' => 'dataList-cell--min',
			'_type' => 'cell',
			'html' => 'Member count',
		);
		$__compilerTemp1[] = array(
			'class' => 'dataList-cell--min',
			'_type' => 'cell',
			'html' => 'Last activity',
		);
		$__compilerTemp2 = '';
		if ($__templater->isTraversable($__vars['groups'])) {
			foreach ($__vars['groups'] AS $__vars['group']) {
				$__compilerTemp2 .= '
                        ';
				$__compilerTemp3 = array();
				if ($__vars['showingAll']) {
					$__compilerTemp3[] = array(
						'name' => 'group_ids[]',
						'value' => $__vars['group']['group_id'],
						'selected' => true,
						'_type' => 'toggle',
						'html' => '',
					);
				}
				$__compilerTemp3[] = array(
					'href' => $__templater->func('link_type', array('public', 'groups', $__vars['group'], ), false),
					'label' => $__templater->escape($__vars['group']['name']),
					'_type' => 'main',
					'html' => '',
				);
				$__compilerTemp3[] = array(
					'class' => 'dataList-cell--min',
					'_type' => 'cell',
					'html' => '
                                ' . $__templater->escape($__vars['group']['Category']['title']) . '
                            ',
				);
				$__compilerTemp3[] = array(
					'class' => 'dataList-cell--min',
					'_type' => 'cell',
					'html' => '
                                ' . $__templater->escape($__vars['group']['owner_username']) . '
                            ',
				);
				$__compilerTemp3[] = array(
					'class' => 'dataList-cell--min',
					'_type' => 'cell',
					'html' => '
                                ' . $__templater->filter($__vars['group']['member_count'], array(array('number', array()),), true) . '
                            ',
				);
				$__compilerTemp3[] = array(
					'class' => 'dataList-cell--min',
					'_type' => 'cell',
					'html' => '
                                ' . $__templater->func('date_dynamic', array($__vars['group']['last_activity'], array(
				))) . '
                            ',
				);
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
						<a href="' . $__templater->func('link', array($__vars['linkPrefix'] . '/list', null, array('criteria' => $__vars['criteria'], 'all' => true, ), ), true) . '">' . 'Show all matches' . '</a>
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
			'data-xf-init' => 'responsive-data-list',
		)) . '
            </div>
            <div class="block-footer block-footer--split">
                <span class="block-footer-counter">' . $__templater->func('display_totals', array($__vars['groups'], $__vars['total'], ), true) . '</span>
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
			'link' => ($__vars['linkPrefix'] . '/list'),
			'params' => array('criteria' => $__vars['criteria'], 'order' => $__vars['order'], 'direction' => $__vars['direction'], ),
			'wrapperclass' => 'js-filterHide block-outer block-outer--after',
			'perPage' => $__vars['perPage'],
		))) . '
    ', array(
			'action' => $__templater->func('link', array($__vars['linkPrefix'] . '/batch-update/confirm', ), false),
			'class' => 'block',
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
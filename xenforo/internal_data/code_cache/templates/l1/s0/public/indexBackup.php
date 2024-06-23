<?php
// FROM HASH: 693995825b6ef605899b0174272bf7dd
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Attendance Management System');
	$__finalCompiled .= '
';
	if ($__vars['xf']['visitor']['is_admin']) {
		$__finalCompiled .= '
';
		$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('New Attendance', array(
			'icon' => 'add',
			'href' => $__templater->func('link', array('attendance/Add', ), false),
			'title' => 'New Attendance',
			'data-xf-init' => '_tooltip',
		), '', array(
		)) . '
');
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '
<div class="block-container">
    <div class="block-body">
        ';
	$__compilerTemp1 = '';
	if ($__templater->isTraversable($__vars['users'])) {
		foreach ($__vars['users'] AS $__vars['user']) {
			$__compilerTemp1 .= '
                ';
			$__compilerTemp2 = array();
			if ($__vars['showingAll']) {
				$__compilerTemp2[] = array(
					'name' => 'user_ids[]',
					'value' => $__vars['user']['user_id'],
					'selected' => true,
					'_type' => 'toggle',
					'html' => '',
				);
			}
			if ($__vars['xf']['visitor']['is_admin']) {
				$__compilerTemp2[] = array(
					'class' => 'dataList-cell--min dataList-cell--image dataList-cell--imageSmall',
					'href' => $__templater->func('link', array('users/edit', $__vars['user'], ), false),
					'_type' => 'cell',
					'html' => '
                            ' . $__templater->func('avatar', array($__vars['user'], 's', false, array(
					'href' => '',
				))) . '
                        ',
				);
				$__compilerTemp2[] = array(
					'href' => $__templater->func('link', array('users/edit', $__vars['user'], ), false),
					'label' => $__templater->func('username_link', array($__vars['user'], true, array(
					'notooltip' => 'true',
					'href' => '',
				))),
					'hint' => $__templater->escape($__vars['user']['email']),
					'_type' => 'main',
					'html' => '',
				);
			} else {
				$__compilerTemp2[] = array(
					'class' => 'dataList-cell--min dataList-cell--image dataList-cell--imageSmall',
					'_type' => 'cell',
					'html' => '
                            ' . $__templater->func('avatar', array($__vars['user'], 's', false, array(
					'href' => '',
				))) . '
                        ',
				);
				$__compilerTemp2[] = array(
					'label' => '
                                ' . $__templater->func('username_link', array($__vars['user'], true, array(
					'notooltip' => 'true',
					'href' => '',
				))) . '
                            ',
					'hint' => $__templater->escape($__vars['user']['email']),
					'_type' => 'main',
					'html' => '',
				);
			}
			if ($__vars['xf']['visitor']['is_admin']) {
				$__compilerTemp2[] = array(
					'href' => $__templater->func('link', array('users/delete', $__vars['user'], ), false),
					'_type' => 'delete',
					'html' => '',
				);
			}
			$__compilerTemp1 .= $__templater->dataRow(array(
				'rowclass' => ((($__vars['user']['user_state'] != 'valid') OR $__vars['user']['is_banned']) ? 'dataList-row--deleted' : ''),
			), $__compilerTemp2) . '
            ';
		}
	}
	$__finalCompiled .= $__templater->dataList('
            ' . $__compilerTemp1 . '
          
        ', array(
	)) . '
    </div>
</div>';
	return $__finalCompiled;
}
);
<?php
// FROM HASH: a94961c7654ac010367fa32805ceecea
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Member Roles');
	$__finalCompiled .= '

';
	$__templater->pageParams['pageAction'] = $__templater->preEscaped('
    <div class="buttonGroup">
        ' . $__templater->button('Add new member role', array(
		'href' => $__templater->func('link', array('group-member-roles/add', ), false),
		'icon' => 'add',
		'overlay' => 'true',
	), '', array(
	)) . '
    </div>
');
	$__finalCompiled .= '

<div class="block">
    <div class="block-container">
        <div class="block-body">
            ';
	$__compilerTemp1 = '';
	if ($__templater->isTraversable($__vars['memberRoles'])) {
		foreach ($__vars['memberRoles'] AS $__vars['memberRole']) {
			$__compilerTemp1 .= '
                    ' . $__templater->dataRow(array(
			), array(array(
				'class' => 'dataList-cell--link dataList-cell--main',
				'hash' => $__vars['memberRole']['member_role_id'],
				'_type' => 'cell',
				'html' => '
                            <a href="' . $__templater->func('link', array('group-member-roles/edit', $__vars['memberRole'], ), true) . '">
                                <div class="u-depth">
                                    <div class="dataList-mainRow">' . $__templater->escape($__vars['memberRole']['title']) . '</div>
                                    <div class="dataList-subRow">(' . $__templater->escape($__vars['memberRole']['display_order']) . ' - ' . $__templater->escape($__vars['memberRole']['member_role_id']) . ') ' . $__templater->escape($__vars['memberRole']['description']) . '</div>
                                </div>
                            </a>
                        ',
			),
			array(
				'href' => $__templater->func('link', array('group-member-roles/delete', $__vars['memberRole'], ), false),
				'_type' => 'delete',
				'html' => '',
			))) . '
                ';
		}
	}
	$__finalCompiled .= $__templater->dataList('
                ' . $__compilerTemp1 . '
            ', array(
	)) . '
        </div>

        <div class="block-footer">
            <span class="block-footer-counter">' . $__templater->func('display_totals', array($__vars['total'], ), true) . '</span>
        </div>
    </div>
</div>';
	return $__finalCompiled;
}
);
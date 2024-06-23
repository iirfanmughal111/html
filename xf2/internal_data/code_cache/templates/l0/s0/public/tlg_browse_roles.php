<?php
// FROM HASH: e095bcb2df803de8e00cf3b4e2821c99
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Member Roles');
	$__finalCompiled .= '

';
	$__templater->setPageParam('head.' . 'metaNoindex', $__templater->preEscaped('<meta name="robots" content="noindex" />'));
	$__finalCompiled .= '

<div class="bbWrapper">
    <div class="bbTable">
        <table style="width: 100%">
            <thead>
                <tr>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                    ';
	if ($__templater->isTraversable($__vars['memberRoles'])) {
		foreach ($__vars['memberRoles'] AS $__vars['memberRole']) {
			$__finalCompiled .= '
                        <th>' . $__templater->escape($__vars['memberRole']['title']) . '</th>
                    ';
		}
	}
	$__finalCompiled .= '
                </tr>
            </thead>
            <tbody>
                ';
	if ($__templater->isTraversable($__vars['data'])) {
		foreach ($__vars['data'] AS $__vars['key'] => $__vars['roleGroup']) {
			$__finalCompiled .= '
                    <tr data-key="' . $__templater->escape($__vars['key']) . '">
                        <td colspan="' . ($__vars['totalRoles'] + 2) . '">' . $__templater->escape($__vars['roleGroup']['title']) . '</td>
                    </tr>
                    ';
			if ($__templater->isTraversable($__vars['roleGroup']['perms'])) {
				foreach ($__vars['roleGroup']['perms'] AS $__vars['permDef']) {
					$__finalCompiled .= '
                        <tr>
                            <td>&nbsp;</td>
                            <td>' . $__templater->escape($__vars['permDef']['title']) . '</td>
                            ';
					if ($__templater->isTraversable($__vars['permDef']['perms'])) {
						foreach ($__vars['permDef']['perms'] AS $__vars['allowed']) {
							$__finalCompiled .= '
                                <td style="text-align: center">
                                    ';
							if ($__vars['allowed']) {
								$__finalCompiled .= $__templater->fontAwesome('fa-check-circle', array(
								)) . '
                                    ';
							} else {
								$__finalCompiled .= '&nbsp;';
							}
							$__finalCompiled .= '
                                </td>
                            ';
						}
					}
					$__finalCompiled .= '
                        </tr>
                    ';
				}
			}
			$__finalCompiled .= '
                ';
		}
	}
	$__finalCompiled .= '
            </tbody>
        </table>
    </div>
</div>
';
	return $__finalCompiled;
}
);
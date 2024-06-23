<?php
// FROM HASH: 958ce5be526e1472568937c6b834706f
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Choose file' . $__vars['xf']['language']['ellipsis']);
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['resource'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

<div class="block">
	<div class="block-container">
		<ul class="block-body">
			';
	if ($__templater->isTraversable($__vars['files'])) {
		foreach ($__vars['files'] AS $__vars['file']) {
			$__finalCompiled .= '
				<li class="block-row block-row--separated">
					<div class="contentRow">
						<div class="contentRow-main">
							<span class="contentRow-extra">
								' . $__templater->button('Download', array(
				'href' => $__templater->func('link', array('resources/version/download', $__vars['version'], array('file' => $__vars['file']['attachment_id'], ), ), false),
				'target' => '_blank',
				'icon' => 'download',
			), '', array(
			)) . '
							</span>
							<h3 class="contentRow-title">' . $__templater->escape($__vars['file']['filename']) . '</h3>
							<div class="contentRow-minor">
								' . $__templater->filter($__vars['file']['file_size'], array(array('file_size', array()),), true) . '
							</div>
						</div>
					</div>
				</li>
			';
		}
	}
	$__finalCompiled .= '
		</ul>
	</div>
</div>';
	return $__finalCompiled;
}
);
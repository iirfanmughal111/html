<?php
// FROM HASH: e9e5b330876765ae8c9141f354bbe3ef
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<div class="block"' . $__templater->func('widget_data', array($__vars['widget'], ), true) . '>
	<div class="block-container">
		<h3 class="block-minorHeader">' . $__templater->escape($__vars['title']) . '</h3>
		<div class="block-body block-row">
			';
	if ($__templater->isTraversable($__vars['users'])) {
		foreach ($__vars['users'] AS $__vars['user']) {
			$__finalCompiled .= '
				<dl class="pairs pairs--justified count--threads">
					<dt>' . $__templater->escape($__vars['user']['username']) . '</dt>
					<dd>' . $__templater->escape($__vars['user']['total']) . '</dd>
				</dl>
			';
		}
	}
	$__finalCompiled .= '
		</div>
	</div>
</div>';
	return $__finalCompiled;
}
);
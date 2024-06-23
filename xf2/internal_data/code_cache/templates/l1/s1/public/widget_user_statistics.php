<?php
// FROM HASH: 3e3a493684fe78f44d0948e6d5cf698f
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<div class="block"' . $__templater->func('widget_data', array($__vars['widget'], ), true) . '>
	<div class="block-container">
		<h3 class="block-minorHeader">' . $__templater->escape($__vars['title']) . '</h3>
		<div class="block-body block-row">
			<dl class="pairs pairs--justified">
				<dt>' . 'Members online' . '</dt>
				<dd>' . $__templater->filter($__vars['counts']['members'], array(array('number', array()),), true) . '</dd>
			</dl>

			<dl class="pairs pairs--justified">
				<dt>' . 'Guests online' . '</dt>
				<dd>' . $__templater->filter($__vars['counts']['guests'], array(array('number', array()),), true) . '</dd>
			</dl>

			<dl class="pairs pairs--justified">
				<dt>' . 'Total visitors' . '</dt>
				<dd>' . $__templater->filter($__vars['counts']['total'], array(array('number', array()),), true) . '</dd>
			</dl>
		
		<h3 class="block-minorHeader">' . 'fs_register_latest_provider' . '</h3>
		
		';
	if ($__templater->isTraversable($__vars['ProviderUsers'])) {
		foreach ($__vars['ProviderUsers'] AS $__vars['user']) {
			$__finalCompiled .= '
			<dl class="pairs pairs--justified">
				<dt><a href="' . $__templater->func('link', array('members', $__vars['user'], ), true) . '">  ' . $__templater->escape($__vars['user']['username']) . '</a></dt>
				<dd>' . $__templater->func('date_dynamic', array($__vars['user']['register_date'], array(
			))) . '</dd>
			</dl>
		';
		}
	}
	$__finalCompiled .= '
			
			<h3 class="block-minorHeader">' . 'fs_register_latest_male' . '</h3>
		
				';
	if ($__templater->isTraversable($__vars['MaleUsers'])) {
		foreach ($__vars['MaleUsers'] AS $__vars['user']) {
			$__finalCompiled .= '
					<dl class="pairs pairs--justified">
						<dt><a href="' . $__templater->func('link', array('members', $__vars['user'], ), true) . '">  ' . $__templater->escape($__vars['user']['username']) . '</a></dt>
						<dd>' . $__templater->func('date_dynamic', array($__vars['user']['register_date'], array(
			))) . '</dd>
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
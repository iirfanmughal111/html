<?php
// FROM HASH: 888ee133f281098e214c2465cb504059
return array(
'macros' => array('node_list_tabs' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'selected' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    <h2 class="tabs--standalone tabs--standalone--closer tabs hScroller"
        data-xf-init="tabs h-scroller" style="max-width:360px" role="tablist">
        <span class="hScroller-scroll">
            <a class="tabs-tab' . (($__vars['selected'] == '') ? ' is-active' : '') . '" role="tab" tabindex="0"
               href="' . $__templater->func('link', array('nodes', ), true) . '">' . 'System nodes' . '</a>
            <a class="tabs-tab' . (($__vars['selected'] == 'group_nodes') ? ' is-active' : '') . '" role="tab" tabindex="0"
               href="' . $__templater->func('link', array('nodes', null, array('tab' => 'group_nodes', ), ), true) . '">' . 'Group nodes' . '</a>
        </span>
    </h2>
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';

	return $__finalCompiled;
}
);
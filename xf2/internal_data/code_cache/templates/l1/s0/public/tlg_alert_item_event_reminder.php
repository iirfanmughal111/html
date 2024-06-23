<?php
// FROM HASH: e56eca31de25d89396ca76f913dbbbec
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__vars['remaining'] > 0) {
		$__finalCompiled .= '
    ';
		if ($__vars['remainingUnit'] == 'hour') {
			$__finalCompiled .= '
        ' . 'Event ' . (((('<a href="' . $__templater->func('link', array('group-events', $__vars['content'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['content']['event_name'])) . '</a>') . ' in the group ' . (((('<a href="' . $__templater->func('link', array('groups', $__vars['content']['Group'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['content']['Group']['name'])) . '</a>') . ' will start in ' . $__templater->escape($__vars['remaining']) . ' hour(s).' . '
    ';
		} else {
			$__finalCompiled .= '
        ' . 'Event ' . (((('<a href="' . $__templater->func('link', array('group-events', $__vars['content'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['content']['event_name'])) . '</a>') . ' in the group ' . (((('<a href="' . $__templater->func('link', array('groups', $__vars['content']['Group'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['content']['Group']['name'])) . '</a>') . ' will start in ' . $__templater->escape($__vars['remaining']) . ' minute(s).' . '
    ';
		}
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
    ' . 'Event ' . (((('<a href="' . $__templater->func('link', array('group-events', $__vars['content'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['content']['event_name'])) . '</a>') . ' in the group ' . (((('<a href="' . $__templater->func('link', array('groups', $__vars['content']['Group'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['content']['Group']['name'])) . '</a>') . ' has been started.' . '
';
	}
	return $__finalCompiled;
}
);
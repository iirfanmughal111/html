<?php
// FROM HASH: 01666f2d576a7777ec300072128d8141
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<li class="block-row block-row--separated ' . ($__templater->method($__vars['event'], 'isIgnored', array()) ? 'is-ignored' : '') . ' js-inlineModContainer" data-author="' . ($__templater->escape($__vars['event']['User']['username']) ?: $__templater->escape($__vars['event']['username'])) . '">
    <div class="contentRow ' . ((!$__templater->method($__vars['event'], 'isVisible', array())) ? 'is-deleted' : '') . '">
		<span class="contentRow-figure">
			' . $__templater->func('avatar', array($__vars['event']['User'], 's', false, array(
		'defaultname' => $__vars['event']['username'],
	))) . '
		</span>
        <div class="contentRow-main">
            <h3 class="contentRow-title">
                <a href="' . $__templater->func('link', array('group-events', $__vars['event'], ), true) . '">' . $__templater->func('highlight', array($__vars['event']['event_name'], $__vars['options']['term'], ), true) . '</a>
            </h3>

            <div class="contentRow-snippet">' . $__templater->func('snippet', array($__vars['event']['FirstComment']['message'], 300, array('term' => $__vars['options']['term'], 'stripQuote' => true, ), ), true) . '</div>

            <div class="contentRow-minor contentRow-minor--hideLinks">
                <ul class="listInline listInline--bullet">
                    ';
	if (($__vars['options']['mod'] == 'tl_group_event') AND $__templater->method($__vars['event'], 'canUseInlineModeration', array())) {
		$__finalCompiled .= '
                        <li>' . $__templater->formCheckBox(array(
			'standalone' => 'true',
		), array(array(
			'value' => $__vars['event']['event_id'],
			'class' => 'js-inlineModToggle',
			'data-xf-init' => 'tooltip',
			'title' => $__templater->filter('Select for moderation', array(array('for_attr', array()),), false),
			'_type' => 'option',
		))) . '</li>
                    ';
	}
	$__finalCompiled .= '
                    <li>' . $__templater->func('username_link', array($__vars['event']['User'], false, array(
		'defaultname' => $__vars['event']['username'],
	))) . '</li>
                    <li>' . 'Event' . '</li>
                    <li>' . $__templater->func('date_dynamic', array($__vars['event']['created_date'], array(
	))) . '</li>
                    <li>' . 'Comments' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->filter($__vars['event']['comment_count'], array(array('number', array()),), true) . '</li>
                    <li>' . 'Group' . $__vars['xf']['language']['label_separator'] . ' <a href="' . $__templater->func('link', array('groups', $__vars['event']['Group'], ), true) . '"
                                                      data-preview-url="' . $__templater->func('link', array('groups/preview', $__vars['event']['Group'], ), true) . '"
                                                      data-xf-init="preview-tooltip">' . $__templater->escape($__vars['event']['Group']['name']) . '</a></li>
                </ul>
            </div>
        </div>
    </div>
</li>';
	return $__finalCompiled;
}
);
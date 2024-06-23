<?php
// FROM HASH: f1735b67575c87553bb34fdd9544140e
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<li class="block-row block-row--separated' . ($__templater->method($__vars['group'], 'isIgnored', array()) ? ' is-ignored' : '') . ' js-inlineModContainer"
    data-author="' . ($__templater->escape($__vars['group']['User']['username']) ?: $__templater->escape($__vars['group']['owner_username'])) . '">
    <div class="contentRow' . ((!$__templater->method($__vars['group'], 'isVisible', array())) ? ' is-deleted' : '') . '">
		<span class="contentRow-figure">
            ' . $__templater->callback('Truonglv\\Groups\\Callback', 'renderAvatar', '', array('group' => $__vars['group'], 'linkClass' => 'avatar avatar--s', )) . '
		</span>
        <div class="contentRow-main">
            <h3 class="contentRow-title">
                <a href="' . $__templater->func('link', array('groups', $__vars['group'], ), true) . '">' . $__templater->func('highlight', array($__vars['group']['name'], $__vars['options']['term'], ), true) . '</a>
            </h3>

            <div class="contentRow-snippet">' . $__templater->func('snippet', array($__vars['group']['description'], 300, array('term' => $__vars['options']['term'], 'stripQuote' => true, ), ), true) . '</div>

            <div class="contentRow-minor contentRow-minor--hideLinks">
                <ul class="listInline listInline--bullet">
                    ';
	if (($__vars['options']['mod'] == 'tl_group') AND $__templater->method($__vars['group'], 'canUseInlineModeration', array())) {
		$__finalCompiled .= '
                        <li>' . $__templater->formCheckBox(array(
			'standalone' => 'true',
		), array(array(
			'value' => $__vars['group']['group_id'],
			'class' => 'js-inlineModToggle',
			'data-xf-init' => 'tooltip',
			'title' => $__templater->filter('Select for moderation', array(array('for_attr', array()),), false),
			'_type' => 'option',
		))) . '</li>
                    ';
	}
	$__finalCompiled .= '
                    <li>' . $__templater->func('username_link', array($__vars['group']['User'], false, array(
		'defaultname' => $__vars['group']['owner_username'],
	))) . '</li>
                    <li>' . 'Group' . '</li>
                    <li>' . $__templater->func('date_dynamic', array($__vars['group']['created_date'], array(
	))) . '</li>
                    <li>' . 'Category' . $__vars['xf']['language']['label_separator'] . ' <a href="' . $__templater->func('link', array('group-categories', $__vars['group']['Category'], ), true) . '">' . $__templater->escape($__vars['group']['Category']['category_title']) . '</a></li>
                </ul>
            </div>
        </div>
    </div>
</li>';
	return $__finalCompiled;
}
);
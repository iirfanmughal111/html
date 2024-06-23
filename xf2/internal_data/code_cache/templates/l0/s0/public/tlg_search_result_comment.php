<?php
// FROM HASH: d7cd023a5b43da786490d94d28a67e06
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<li class="block-row block-row--separated' . ($__templater->method($__vars['comment'], 'isIgnored', array()) ? ' is-ignored' : '') . ' js-inlineModContainer"
    data-author="' . ($__templater->escape($__vars['comment']['User']['username']) ?: $__templater->escape($__vars['comment']['username'])) . '">
    <div class="contentRow' . ((!$__templater->method($__vars['comment'], 'isVisible', array())) ? ' is-deleted' : '') . '">
		<span class="contentRow-figure">
			' . $__templater->func('avatar', array($__vars['comment']['User'], 's', false, array(
		'defaultname' => $__vars['comment']['username'],
	))) . '
		</span>
        <div class="contentRow-main">
            <h3 class="contentRow-title">
                <a href="' . $__templater->func('link', array('group-comments', $__vars['comment'], ), true) . '">' . $__templater->func('highlight', array($__vars['comment']['Group']['name'], $__vars['options']['term'], ), true) . '</a>
            </h3>

            <div class="contentRow-snippet">' . $__templater->func('snippet', array($__vars['comment']['message'], 300, array('term' => $__vars['options']['term'], 'stripQuote' => true, ), ), true) . '</div>

            <div class="contentRow-minor contentRow-minor--hideLinks">
                <ul class="listInline listInline--bullet">
                    ';
	if (($__vars['options']['mod'] == 'tl_group_comment') AND $__templater->method($__vars['comment'], 'canUseInlineModeration', array())) {
		$__finalCompiled .= '
                        <li>' . $__templater->formCheckBox(array(
			'standalone' => 'true',
		), array(array(
			'value' => $__vars['comment']['comment_id'],
			'class' => 'js-inlineModToggle',
			'data-xf-init' => 'tooltip',
			'title' => $__templater->filter('Select for moderation', array(array('for_attr', array()),), false),
			'_type' => 'option',
		))) . '</li>
                    ';
	}
	$__finalCompiled .= '
                    <li>' . $__templater->func('username_link', array($__vars['comment']['User'], false, array(
		'defaultname' => $__vars['comment']['username'],
	))) . '</li>
                    <li>' . 'Comment' . '</li>
                    <li>' . $__templater->func('date_dynamic', array($__vars['comment']['comment_date'], array(
	))) . '</li>
                    <li>' . 'Group' . $__vars['xf']['language']['label_separator'] . ' <a href="' . $__templater->func('link', array('groups', $__vars['comment']['Group'], ), true) . '"
                                                      data-preview-url="' . $__templater->func('link', array('groups/preview', $__vars['comment']['Group'], ), true) . '"
                                                      data-xf-init="preview-tooltip">' . $__templater->escape($__vars['comment']['Group']['name']) . '</a></li>
                </ul>
            </div>
        </div>
    </div>
</li>';
	return $__finalCompiled;
}
);
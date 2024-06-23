<?php
// FROM HASH: f24b1ffc912cf6452c056b7619d55cc4
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<li class="block-row block-row--separated ' . ($__templater->method($__vars['resource'], 'isIgnored', array()) ? 'is-ignored' : '') . ' js-inlineModContainer"
    data-author="' . ($__templater->escape($__vars['resource']['User']['username']) ?: $__templater->escape($__vars['resource']['username'])) . '">
    <div class="contentRow">
		<span class="contentRow-figure">
			' . $__templater->func('avatar', array($__vars['resource']['User'], 's', false, array(
		'defaultname' => $__vars['resource']['username'],
	))) . '
		</span>
        <div class="contentRow-main">
            <h3 class="contentRow-title">
                <a href="' . $__templater->func('link', array('group-resources', $__vars['resource'], ), true) . '">' . $__templater->func('highlight', array($__vars['resource']['title'], $__vars['options']['term'], ), true) . '</a>
            </h3>

            <div class="contentRow-snippet">' . $__templater->func('snippet', array($__vars['resource']['FirstComment']['message'], 300, array('term' => $__vars['options']['term'], 'stripQuote' => true, ), ), true) . '</div>

            <div class="contentRow-minor contentRow-minor--hideLinks">
                <ul class="listInline listInline--bullet">
                    ';
	if ($__vars['options']['mod'] == 'tl_group_resource') {
		$__finalCompiled .= '
                        <li>' . $__templater->formCheckBox(array(
			'standalone' => 'true',
		), array(array(
			'value' => $__vars['resource']['resource_id'],
			'class' => 'js-inlineModToggle',
			'data-xf-init' => 'tooltip',
			'title' => $__templater->filter('Select for moderation', array(array('for_attr', array()),), false),
			'_type' => 'option',
		))) . '</li>
                    ';
	}
	$__finalCompiled .= '
                    <li>' . $__templater->func('username_link', array($__vars['resource']['User'], false, array(
		'defaultname' => $__vars['resource']['username'],
	))) . '</li>
                    <li>' . 'Resource' . '</li>
                    <li>' . $__templater->func('date_dynamic', array($__vars['resource']['resource_date'], array(
	))) . '</li>
                    <li>' . 'Comments' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->filter($__vars['resource']['comment_count'], array(array('number', array()),), true) . '</li>
                    <li>' . 'Group' . $__vars['xf']['language']['label_separator'] . ' <a href="' . $__templater->func('link', array('groups', $__vars['resource']['Group'], ), true) . '"
                                                      data-preview-url="' . $__templater->func('link', array('groups/preview', $__vars['resource']['Group'], ), true) . '"
                                                      data-xf-init="preview-tooltip">' . $__templater->escape($__vars['resource']['Group']['name']) . '</a></li>
                </ul>
            </div>
        </div>
    </div>
</li>';
	return $__finalCompiled;
}
);
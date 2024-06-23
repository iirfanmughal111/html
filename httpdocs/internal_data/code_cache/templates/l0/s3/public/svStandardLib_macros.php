<?php
// FROM HASH: 65542bc0bfd3cae396832aafdc7fc9cd
return array(
'macros' => array('render_prefix_filter' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'filters' => '!',
		'prefixId' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__compilerTemp1 = '';
	if (!$__templater->func('is_scalar', array($__vars['prefixId'], ), false)) {
		$__compilerTemp1 .= '
		';
		$__vars['prefixId'] = (!$__templater->test($__vars['prefixId']['prefix_id'], 'empty', array()) ? $__vars['prefixId']['prefix_id'] : 0);
		$__compilerTemp1 .= '
	';
	}
	$__vars['filterRoute'] = (($__vars['__globals']['filterRoute'] !== null) ? $__vars['__globals']['filterRoute'] : 'forums');
	$__vars['filterContainer'] = (((($__vars['filterRoute'] === 'forums') AND ($__vars['__globals']['filterContainer'] !== null))) ? $__vars['__globals']['filterContainer'] : null);
	$__vars['removeFilter'] = ($__vars['prefixId'] === $__vars['filters']['prefix_id']);
	$__vars['filters'] = ($__vars['removeFilter'] ? $__templater->filter($__vars['filters'], array(array('replace', array('prefix_id', null, )),), false) : $__templater->filter($__vars['filters'], array(array('replace', array('prefix_id', $__vars['prefixId'], )),), false));
	$__compilerTemp2 = '';
	$__compilerTemp3 = '';
	$__compilerTemp3 .= $__templater->func('trim', array($__templater->func('prefix', array('thread', $__vars['prefixId'], ), true)), false);
	if (strlen(trim($__compilerTemp3)) > 0) {
		$__compilerTemp2 .= '
		<a href="' . $__templater->func('link', array($__vars['filterRoute'], $__vars['filterContainer'], $__vars['filters'], ), true) . '" class="labelLink" rel="nofollow" data-xf-init="tooltip" title="' . $__templater->filter(($__vars['removeFilter'] ? 'Remove from filters' : 'Add to filters'), array(array('for_attr', array()),), true) . '"  rel="nofollow">
			' . $__compilerTemp3 . '
		</a>
	';
	}
	$__finalCompiled .= $__templater->func('trim', array('
	
	' . $__compilerTemp1 . '
	
	' . '' . '
	' . '' . '

	' . '' . '
	' . '' . '
	' . $__compilerTemp2 . '
'), false);
	return $__finalCompiled;
}
),
'render_forum_filter' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'filters' => '!',
		'nodeId' => '!',
		'forum' => '!',
		'removeFilter' => '!',
		'noFilterValue' => '',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__vars['filterRoute'] = (($__vars['__globals']['filterRoute'] !== null) ? $__vars['__globals']['filterRoute'] : 'forums');
	$__vars['filterContainer'] = (((($__vars['filterRoute'] === 'forums') AND ($__vars['__globals']['filterContainer'] !== null))) ? $__vars['__globals']['filterContainer'] : $__vars['forum']);
	$__vars['removeFilter'] = $__templater->func('in_array', array($__vars['nodeId'], ($__vars['filters']['nodes'] ?: array()), true, ), false);
	$__vars['filters']['nodes'] = ($__vars['removeFilter'] ? ($__templater->filter($__vars['filters']['nodes'], array(array('replaceValue', array($__vars['nodeId'], null, )),), false) ?: $__vars['noFilterValue']) : $__templater->filter($__vars['filters']['nodes'], array(array('addValue', array($__vars['nodeId'], )),), false));
	$__finalCompiled .= $__templater->func('trim', array('
	
	' . '' . '
	' . '' . '
	
	' . '' . '
	' . '' . '	
	<a href="' . $__templater->func('link', array($__vars['filterRoute'], $__vars['filterContainer'], $__vars['filters'], ), true) . '" class="labelLink" rel="nofollow" data-xf-init="tooltip" title="' . $__templater->filter(($__vars['removeFilter'] ? 'Remove from filters' : 'Add to filters'), array(array('for_attr', array()),), true) . '"  rel="nofollow">
		' . $__templater->escape($__vars['forum']['title']) . '
	</a>
'), false);
	return $__finalCompiled;
}
),
'dynamic_quick_filter' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'key' => '',
		'ajax' => '',
		'class' => '',
		'page' => '',
		'filter' => array(),
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    ';
	$__templater->includeJs(array(
		'src' => 'xf/filter.js',
		'min' => '1',
	));
	$__finalCompiled .= '
	';
	$__templater->includeJs(array(
		'src' => 'sv/vendor/domurl/url.js',
		'addon' => 'SV/StandardLib',
		'min' => '1',
	));
	$__finalCompiled .= '
	';
	$__templater->includeJs(array(
		'src' => 'sv/lib/xf/filter.js',
		'addon' => 'SV/StandardLib',
		'min' => '1',
	));
	$__finalCompiled .= '
	<script class="js-extraPhrases" type="application/json">
		{
			"no_items_matched_your_filter": "' . $__templater->filter('No items matched your filter.', array(array('escape', array('js', )),), true) . '"
		}
	</script>
	';
	$__templater->includeCss('sv_quick_filter.less');
	$__finalCompiled .= '
	
    <div class="' . $__templater->escape($__vars['class']) . ' quickFilter u-jsOnly"
         data-xf-init="sv-dynamic-filter"
         data-key="' . $__templater->escape($__vars['key']) . '"
         data-ajax="' . $__templater->escape($__vars['ajax']) . '"
         data-search-target=".userList"
         data-search-row=".userList-row"
         data-search-row-group=".contentRow"
		 data-search-limit=".username"
         data-no-results-format="<div class=&quot;blockMessage js-filterNoResults&quot;>%s</div>">
		<div class="inputGroup inputGroup--inline inputGroup--joined">
			<input type="text" class="input js-filterInput" value="' . $__templater->escape($__vars['filter']['text']) . '" placeholder="' . $__templater->filter('Filter' . $__vars['xf']['language']['ellipsis'], array(array('for_attr', array()),), true) . '" data-xf-key="' . $__templater->filter('f', array(array('for_attr', array()),), true) . '" />
			<span class="inputGroup-text">
				' . $__templater->formCheckBox(array(
		'standalone' => 'true',
	), array(array(
		'class' => 'js-filterPrefix',
		'label' => 'Prefix',
		'checked' => $__vars['filter']['prefix'],
		'_type' => 'option',
	))) . '
			</span>
			<i class="inputGroup-text js-filterClear is-disabled" aria-hidden="true"></i>
        </div>
    </div>
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

' . '

';
	return $__finalCompiled;
}
);
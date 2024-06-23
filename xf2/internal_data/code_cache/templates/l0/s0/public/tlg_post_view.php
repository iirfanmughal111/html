<?php
// FROM HASH: 7bacbf7608cedb094954f951c74efcde
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__compilerTemp1 = $__vars;
	$__compilerTemp1['pageSelected'] = $__templater->preEscaped('newsFeed');
	$__templater->wrapTemplate('tlg_group_wrapper', $__compilerTemp1);
	$__finalCompiled .= '

';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped($__templater->escape($__templater->method($__vars['post'], 'getMetadataTitle', array())));
	$__finalCompiled .= '

';
	$__vars['fpSnippet'] = $__templater->func('snippet', array($__vars['post']['FirstComment']['message'], 0, array('stripBbCode' => true, ), ), false);
	$__finalCompiled .= '
';
	if ($__templater->method($__vars['post'], 'getMetadataShareImage', array())) {
		$__finalCompiled .= '
    ';
		$__vars['image'] = $__templater->preEscaped($__templater->escape($__templater->method($__vars['post'], 'getMetadataShareImage', array())));
		$__finalCompiled .= '
';
	} else if ($__templater->func('property', array('publicMetadataLogoUrl', ), false)) {
		$__finalCompiled .= '
    ';
		$__vars['image'] = $__templater->preEscaped($__templater->func('base_url', array($__templater->func('property', array('publicMetadataLogoUrl', ), false), true, ), true));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

' . $__templater->callMacro('metadata_macros', 'metadata', array(
		'description' => $__vars['fpSnippet'],
		'shareUrl' => $__templater->func('link', array('canonical:group-posts', $__vars['post'], ), false),
		'imageUrl' => $__vars['image'],
		'type' => 'article',
		'title' => $__templater->method($__vars['post'], 'getMetadataTitle', array()),
		'canonicalUrl' => $__templater->func('link', array('canonical:group-posts', $__vars['post'], array('page' => $__vars['page'], ), ), false),
	), $__vars) . '

<div class="block block--messages" data-xf-init="' . ($__vars['canInlineMod'] ? 'inline-mod' : '') . '"
     data-type="tl_group_wall_post"
     data-href="' . $__templater->func('link', array('inline-mod', ), true) . '">
    <div class="block-container lbContainer"
         data-xf-init="lightbox"
         data-message-selector=".js-post"
         data-lb-id="group-' . $__templater->escape($__vars['group']['group_id']) . '"
         data-lb-universal="' . $__templater->escape($__vars['xf']['options']['lightBoxUniversal']) . '">
        <div class="block-body js-newPostsContainer">
            ' . $__templater->callMacro('tlg_post_macros', 'post', array(
		'showFull' => true,
		'post' => $__vars['post'],
	), $__vars) . '
        </div>
    </div>
</div>

' . $__templater->func('page_nav', array(array(
		'link' => 'group-posts',
		'data' => $__vars['post'],
		'page' => $__vars['page'],
		'total' => $__vars['total'],
		'perPage' => $__vars['perPage'],
	)));
	return $__finalCompiled;
}
);
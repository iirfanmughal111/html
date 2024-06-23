<?php
// FROM HASH: f8ed0138608dd7f7347d25932832c3fb
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped($__templater->escape($__vars['group']['name']));
	$__finalCompiled .= '

';
	$__compilerTemp1 = $__vars;
	$__compilerTemp1['pageSelected'] = $__templater->preEscaped('newsFeed');
	$__templater->wrapTemplate('tlg_group_wrapper', $__compilerTemp1);
	$__finalCompiled .= '

';
	if ($__vars['group']['AvatarAttachment']) {
		$__finalCompiled .= '
    ';
		$__vars['imageUrl'] = $__templater->preEscaped($__templater->escape($__templater->method($__vars['group']['AvatarAttachment']['Data'], 'getThumbnailUrl', array(true, ))));
		$__finalCompiled .= '
';
	} else if ($__templater->func('property', array('publicMetadataLogoUrl', ), false)) {
		$__finalCompiled .= '
    ';
		$__vars['imageUrl'] = $__templater->preEscaped($__templater->func('base_url', array($__templater->func('property', array('publicMetadataLogoUrl', ), false), true, ), true));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

' . $__templater->callMacro('metadata_macros', 'metadata', array(
		'description' => $__vars['group']['short_description'],
		'shareUrl' => $__templater->func('link', array('canonical:groups', $__vars['group'], ), false),
		'imageUrl' => $__vars['imageUrl'],
		'canonicalUrl' => $__templater->func('link', array('canonical:groups', $__vars['group'], array('page' => $__vars['page'], ), ), false),
	), $__vars) . '

';
	$__templater->setPageParam('ldJsonHtml', '
    ' . $__templater->callMacro('tlg_group_macros', 'structured_data', array(
		'group' => $__vars['group'],
	), $__vars) . '
');
	$__finalCompiled .= '

';
	if ($__templater->method($__vars['group'], 'canAddPost', array())) {
		$__finalCompiled .= '
';
		$__templater->includeJs(array(
			'src' => 'xf/message.js',
			'min' => '1',
		));
		$__vars['lastPost'] = $__templater->filter($__vars['posts'], array(array('last', array()),), false);
		$__finalCompiled .= $__templater->form('

    ' . '' . '
    ' . '' . '

    <div class="block-container">
        <div class="block-body">
            ' . $__templater->callMacro('quick_reply_macros', 'body', array(
			'message' => '',
			'attachmentData' => $__vars['attachmentData'],
			'forceHash' => $__vars['attachmentHash'],
			'messageSelector' => '.js-post',
			'multiQuoteStorageKey' => '',
			'lastDate' => $__vars['lastPost']['post_date'],
			'showPreviewButton' => false,
			'simple' => true,
			'submitText' => 'Post',
		), $__vars) . '
        </div>
    </div>
', array(
			'action' => $__templater->func('link', array('groups/add-post', $__vars['group'], ), false),
			'ajax' => 'true',
			'class' => 'block js-quickReply',
			'data-xf-init' => 'attachment-manager quick-reply',
			'data-ascending' => 'false',
			'data-message-container' => '.js-newPostsContainer',
		)) . '
';
	}
	$__finalCompiled .= '

';
	if (!$__templater->test($__vars['stickyPosts'], 'empty', array())) {
		$__finalCompiled .= '
    <div class="block block--messages" data-xf-init="' . ($__vars['canInlineMod'] ? 'inline-mod' : '') . '" data-type="tl_group_wall_post"
         data-href="' . $__templater->func('link', array('inline-mod', ), true) . '">
        <div class="block-container lbContainer"
             data-xf-init="lightbox"
             data-message-selector=".js-post"
             data-lb-id="group-' . $__templater->escape($__vars['group']['group_id']) . '"
             data-lb-universal="' . $__templater->escape($__vars['xf']['options']['lightBoxUniversal']) . '">
            <h3 class="block-minorHeader">' . $__templater->fontAwesome('fa-thumbtack', array(
		)) . ' ' . 'Sticky posts' . '</h3>
            <div class="block-body">
                ';
		if ($__templater->isTraversable($__vars['stickyPosts'])) {
			foreach ($__vars['stickyPosts'] AS $__vars['post']) {
				$__finalCompiled .= '
                    ' . $__templater->callMacro('tlg_post_macros', 'post', array(
					'post' => $__vars['post'],
					'showLoader' => true,
				), $__vars) . '
                ';
			}
		}
		$__finalCompiled .= '
            </div>
        </div>
    </div>
';
	}
	$__finalCompiled .= '

<div class="block block--messages" data-xf-init="' . ($__vars['canInlineMod'] ? 'inline-mod' : '') . '"
     data-type="tl_group_wall_post"
     data-href="' . $__templater->func('link', array('inline-mod', ), true) . '">
    <div class="block-container lbContainer"
         data-xf-init="lightbox"
         data-message-selector=".js-post"
         data-lb-id="group-' . $__templater->escape($__vars['group']['group_id']) . '"
         data-lb-universal="' . $__templater->escape($__vars['xf']['options']['lightBoxUniversal']) . '">

        <div class="block-body js-newPostsContainer">
            ';
	$__compilerTemp2 = true;
	if ($__templater->isTraversable($__vars['posts'])) {
		foreach ($__vars['posts'] AS $__vars['post']) {
			$__compilerTemp2 = false;
			$__finalCompiled .= '
                ' . $__templater->callMacro('tlg_post_macros', 'post', array(
				'post' => $__vars['post'],
				'showLoader' => true,
			), $__vars) . '
            ';
		}
	}
	if ($__compilerTemp2) {
		$__finalCompiled .= '
                <div class="js-newMessagesIndicator">
                    ';
		if ($__templater->test($__vars['stickyPosts'], 'empty', array())) {
			$__finalCompiled .= '
                        <div class="block-row">' . 'There are no posts to display' . '</div>
                    ';
		}
		$__finalCompiled .= '
                </div>
            ';
	}
	$__finalCompiled .= '
        </div>
    </div>

    <div class="block-outer block-outer--after">
        ' . $__templater->func('page_nav', array(array(
		'link' => $__vars['pageNavLink'],
		'data' => $__vars['group'],
		'page' => $__vars['page'],
		'total' => $__vars['total'],
		'perPage' => $__vars['perPage'],
	))) . '
        ' . $__templater->func('show_ignored', array(array(
		'wrapperclass' => 'block-outer-opposite',
	))) . '
    </div>
</div>';
	return $__finalCompiled;
}
);
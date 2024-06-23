<?php
// FROM HASH: c52dbd0be5019d63c5695ebb79037a87
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('About');
	$__finalCompiled .= '

';
	$__compilerTemp1 = $__vars;
	$__compilerTemp1['pageSelected'] = $__templater->preEscaped('about');
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
	$__compilerTemp2 = '';
	$__compilerTemp2 .= $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
		'type' => 'tl_groups',
		'group' => 'above_info',
		'onlyInclude' => $__vars['category']['field_cache'],
		'set' => $__vars['group']['custom_fields'],
	), $__vars);
	if (strlen(trim($__compilerTemp2)) > 0) {
		$__finalCompiled .= '
    <div class="block">
        <div class="block-container">
            <div class="block-body block-row">
                ' . $__compilerTemp2 . '
            </div>
        </div>
    </div>
';
	}
	$__finalCompiled .= '

<div class="block">
    <div class="block-container">
        <h3 class="block-minorHeader">' . 'Description' . '</h3>
        <div class="block-row">
            ';
	$__templater->includeCss('message.less');
	$__finalCompiled .= '
            ' . $__templater->callMacro('lightbox_macros', 'setup', array(
		'canViewAttachments' => true,
	), $__vars) . '

            <div class="lbContainer" data-lb-id="group-' . $__templater->escape($__vars['group']['group_id']) . '"
                 data-message-selector=".js-groupDescription" data-xf-init="lightbox"
                 data-lb-universal="' . $__templater->escape($__vars['xf']['options']['lightBoxUniversal']) . '">
                <article class="message message--groupDescription js-groupDescription"
                         data-content="js-option-' . $__templater->escape($__vars['option']['question_option_id']) . '"
                         data-author="' . ($__vars['group']['User'] ? $__templater->escape($__vars['group']['User']['username']) : $__templater->escape($__vars['group']['owner_username'])) . '"
                         id="js-groupDescription-' . $__templater->escape($__vars['group']['group_id']) . '">
                    <div class="message-content js-messageContent">
                        <div class="message-userContent lbContainer js-lbContainer"
                             data-lb-id="option-' . $__templater->escape($__vars['option']['question_option_id']) . '"
                             data-lb-caption-desc="' . ($__vars['group']['User'] ? $__templater->escape($__vars['group']['User']['username']) : $__templater->escape($__vars['group']['owner_username'])) . ' &middot; ' . $__templater->func('date_time', array($__vars['group']['created_date'], ), true) . '">
                            <article>
                                ' . $__templater->func('bb_code', array($__vars['group']['description'], 'tl_group', $__vars['group'], ), true) . '
                            </article>
                        </div>
                </article>
            </div>
        </div>
    </div>
</div>

<div class="block">
    <div class="block-container">
        <div class="block-body block-row">
            <dl class="pairs pairs--columns pairs--spaced pairs--fixedSmall">
                <dt>' . 'Category' . '</dt>
                <dd><a href="' . $__templater->func('link', array('group-categories', $__vars['group']['Category'], ), true) . '">' . $__templater->escape($__vars['group']['Category']['category_title']) . '</a></dd>
            </dl>
            <!-- TLG_GROUP_ABOUT: BELOW_CATEGORY -->

            ';
	if ($__vars['xf']['options']['enableTagging'] AND ($__templater->method($__vars['group'], 'canEditTags', array()) OR $__vars['group']['tags'])) {
		$__finalCompiled .= '
            <dl class="pairs pairs--columns pairs--spaced pairs--fixedSmall">
                <dt>' . 'Tags' . '</dt>
                <dd>
                    ';
		if ($__vars['group']['tags']) {
			$__finalCompiled .= '
                        ';
			if ($__templater->isTraversable($__vars['group']['tags'])) {
				foreach ($__vars['group']['tags'] AS $__vars['tag']) {
					$__finalCompiled .= '
                            <a href="' . $__templater->func('link', array('tags', $__vars['tag'], ), true) . '" class="tagItem" dir="auto">' . $__templater->escape($__vars['tag']['tag']) . '</a>
                        ';
				}
			}
			$__finalCompiled .= '
                        ';
		} else {
			$__finalCompiled .= '
                        ' . 'None' . '
                    ';
		}
		$__finalCompiled .= '

                    ';
		if ($__templater->method($__vars['group'], 'canEditTags', array())) {
			$__finalCompiled .= '
                        <a href="' . $__templater->func('link', array('groups/tags', $__vars['group'], ), true) . '" class="u-concealed" data-xf-click="overlay"
                           data-xf-init="tooltip" title="' . $__templater->filter('Edit tags', array(array('for_attr', array()),), true) . '">
                            ' . $__templater->fontAwesome('fa-pencil', array(
			)) . '
                            <span class="u-srOnly">' . 'Edit' . '</span>
                        </a>
                    ';
		}
		$__finalCompiled .= '
                </dd>
            </dl>
            ';
	}
	$__finalCompiled .= '
            <!-- TLG_GROUP_ABOUT: BELOW_TAGS -->
        </div>
    </div>
</div>

';
	$__compilerTemp3 = '';
	$__compilerTemp3 .= $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
		'type' => 'tl_groups',
		'group' => 'below_info',
		'onlyInclude' => $__vars['category']['field_cache'],
		'set' => $__vars['group']['custom_fields'],
	), $__vars);
	if (strlen(trim($__compilerTemp3)) > 0) {
		$__finalCompiled .= '
    <div class="block">
        <div class="block-container">
            <div class="block-body block-row">
                ' . $__compilerTemp3 . '
            </div>
        </div>
    </div>
';
	}
	$__finalCompiled .= '

';
	$__compilerTemp4 = '';
	$__compilerTemp4 .= $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
		'type' => 'tl_groups',
		'group' => 'extra_tab',
		'onlyInclude' => $__vars['category']['field_cache'],
		'set' => $__vars['group']['custom_fields'],
	), $__vars);
	if (strlen(trim($__compilerTemp4)) > 0) {
		$__finalCompiled .= '
<div class="block">
    <div class="block-container">
        <div class="block-body block-row">
            ' . $__compilerTemp4 . '
        </div>
    </div>
</div>
';
	}
	$__finalCompiled .= '

<div class="block">
    <div class="block-container">
        <h3 class="block-minorHeader">' . 'Staff member' . '</h3>
        <div class="block-separator"></div>
        <div class="block-body">
            <div class="block-row">
                <ol class="listInline">
                    ';
	if ($__templater->isTraversable($__vars['managers'])) {
		foreach ($__vars['managers'] AS $__vars['manager']) {
			$__finalCompiled .= '
                        ' . $__templater->func('avatar', array($__vars['manager']['User'], 's', false, array(
				'defaultname' => $__vars['manager']['username'],
			))) . '
                    ';
		}
	}
	$__finalCompiled .= '
                </ol>
            </div>
        </div>
    </div>
</div>

';
	$__compilerTemp5 = '';
	$__compilerTemp5 .= '
                        ';
	if ($__templater->isTraversable($__vars['members'])) {
		foreach ($__vars['members'] AS $__vars['member']) {
			if ($__templater->method($__vars['member'], 'isValidMember', array())) {
				$__compilerTemp5 .= '
                            ' . $__templater->func('avatar', array($__vars['member']['User'], 's', false, array(
					'defaultname' => $__vars['member']['username'],
				))) . '
                        ';
			}
		}
	}
	$__compilerTemp5 .= '
                    ';
	if (strlen(trim($__compilerTemp5)) > 0) {
		$__finalCompiled .= '
<div class="block">
    <div class="block-container">
        <h3 class="block-minorHeader">' . 'Members' . '</h3>
        <div class="block-separator"></div>
        <div class="block-body">
            <div class="block-row">
                <ol class="listInline">
                    ' . $__compilerTemp5 . '
                </ol>
            </div>
        </div>

        <div class="block-footer"><a href="' . $__templater->func('link', array('groups/members', $__vars['group'], ), true) . '">' . 'See all members' . '</a></div>
    </div>
</div>
';
	}
	return $__finalCompiled;
}
);
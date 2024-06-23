<?php
// FROM HASH: 33e994c7f9f59569254380c850f4e78f
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped($__templater->escape($__vars['group']['name']));
	$__finalCompiled .= '

';
	$__templater->includeCss('fs_forum_gorups_style.less');
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__vars['subForums']['AvatarAttachment']) {
		$__compilerTemp1 .= '
        <a href="' . $__templater->func('link', array('forumGroups', $__vars['subForums'], ), true) . '"
       class="groupAvatar groupAvatar--link groupAvatar--default" style="background-color:#e08585;color:#8f2424">
			<img src="' . $__templater->escape($__vars['subForums']['AvatarAttachment']['thumbnail_url']) . '"
                 class="groupAvatar--img bbImage" width="250" height="250"
                 data-width="' . $__templater->escape($__vars['subForums']['AvatarAttachment']['width']) . '"
                 data-height="' . $__templater->escape($__vars['subForums']['AvatarAttachment']['height']) . '"
                 alt="' . $__templater->escape($__vars['subForums']['title']) . '"/>
    </a>
			   
			';
	} else {
		$__compilerTemp1 .= '
			   ' . '
			  ' . $__templater->func('avatar', array($__vars['subForums']['User'], 'l', false, array(
		))) . '
			';
	}
	$__templater->modifySideNavHtml(null, '
	
	<!-- Avatar -->
	
	<div class="block groupAvatar-block">
           ' . $__compilerTemp1 . '
    </div>
	
	<!-- Avatar -->

', 'replace');
	$__finalCompiled .= '

<div class="groupWrapper groupWrapper-' . $__templater->escape($__vars['subForums']['node_id']) . '">

	<!-- Header -->

	';
	$__compilerTemp2 = '';
	if ($__vars['subForums']['node_state'] == 'visible') {
		$__compilerTemp2 .= '
					<div class="p-title-pageAction">
						<a href="' . $__templater->func('link', array('forums/post-thread', $__vars['subForums'], ), true) . '" class="button--cta button button--icon button--icon--write"><span class="button-text">
							' . 'Post thread' . '
						</span></a>

						';
		if ($__vars['xf']['visitor']['user_id'] AND ($__vars['subForums']['room_path'] AND ($__vars['xf']['visitor']['user_id'] == $__vars['subForums']['user_id']))) {
			$__compilerTemp2 .= '
							<a href="' . $__templater->func('link', array($__vars['subForums']['room_path'], ), true) . '" class="button--cta button button--icon"><span class="button-text">
							<i class="fas fa-comment-dots"></i> ' . 'Chat Room' . '
						</span></a>
						';
		}
		$__compilerTemp2 .= '

						';
		if ($__vars['xf']['visitor']['user_id'] AND ($__vars['xf']['visitor']['user_id'] == $__vars['subForums']['user_id'])) {
			$__compilerTemp2 .= '
							<a href="' . $__templater->func('link', array('forumGroups/moderator-list', $__vars['subForums'], ), true) . '" class="button--cta button button--icon button--icon--list"><span class="button-text">
							' . 'Moderator List' . '
						</span></a>
						';
		}
		$__compilerTemp2 .= '
					</div>
					';
	}
	$__templater->setPageParam('headerHtml', '
        <div class="contentRow contentRow--hideFigureNarrow">
            <div class="contentRow-main">
                <div class="p-title">
                    <h1 class="p-title-value">
                        ' . $__templater->escape($__vars['subForums']['title']) . '
                    </h1>
                
				' . $__compilerTemp2 . '
					
					</div>
				<div class="p-description">
                        ' . $__templater->escape($__vars['subForums']['description']) . '
                    </div>
            </div>
        </div>
    ');
	$__finalCompiled .= '

	<!-- Header -->

	<!-- Cover Header -->

	<div class="block">
        <div class="block-container groupCover-header">
            <div class="block-body">
               <div class="groupCover groupCoverFrame groupCover--default" style="background-color:#' . $__templater->escape($__templater->method($__vars['subForums'], 'getRandomColor', array())) . ';color:#fff">
        <a href="' . $__templater->func('link', array('forumGroups', $__vars['subForums'], ), true) . '" style="color:#fff">
                ';
	if ($__vars['subForums']['CoverAttachment']) {
		$__finalCompiled .= '
                <img data-crop="' . $__templater->filter($__templater->method($__vars['subForums'], 'getCoverCropData', array()), array(array('json', array()),), true) . '"
                     class="groupCover--img groupCover--lazy" data-xf-init="fs-forum-groups-cover-setup"
                     ' . ($__templater->method($__vars['subForums'], 'getImageAttributes', array()) ? (' ' . $__templater->escape($__templater->method($__vars['subForums'], 'getImageAttributes', array()))) : '') . '/>
            ';
	} else {
		$__finalCompiled .= '
                <span class="groupCover--text">' . $__templater->func('snippet', array($__vars['subForums']['title'], 25, ), true) . '</span>
            ';
	}
	$__finalCompiled .= '
        </a>
    </div>
            </div>

            ';
	if (!$__templater->test($__vars['isEditing'], 'empty', array())) {
		$__finalCompiled .= '
                ' . $__templater->callMacro(null, 'cover_editor_setup', array(
			'group' => $__vars['group'],
			'width' => $__vars['baseWidth'],
			'height' => $__vars['baseHeight'],
		), $__vars) . '
            ';
	} else {
		$__finalCompiled .= '
				
				';
		if ($__vars['subForums']['node_state'] == 'visible') {
			$__finalCompiled .= '
				 <div class="gridCard--header--actions">
		   
			<div class="buttonGroup-buttonWrapper">
                ' . $__templater->button($__templater->fontAwesome('fa-cog', array(
			)), array(
				'class' => 'button--link menuTrigger',
				'data-xf-click' => 'menu',
				'aria-expanded' => 'false',
				'aria-haspopup' => 'true',
				'title' => $__templater->filter('More options', array(array('for_attr', array()),), false),
			), '', array(
			)) . '
					<div class="menu" data-menu="menu" aria-hidden="true">
                    <div class="menu-content">
                                <a href="' . $__templater->func('link', array('forumGroups/add-moderator', $__vars['subForums'], ), true) . '"
                                       class="menu-linkRow"
                                       data-xf-click="overlay">
                                ' . 'Add Moderator' . '
                            </a>
						
						<a href="' . $__templater->func('link', array('forumGroups/avatar', $__vars['subForums'], ), true) . '"
                                       class="menu-linkRow"
                                       data-xf-click="overlay">
                                ' . 'Upload avatar' . '
                            </a>
						
						<a href="' . $__templater->func('link', array('forumGroups/cover', $__vars['subForums'], ), true) . '"
                                       class="menu-linkRow"
                                       data-xf-click="overlay">
                                ' . 'Upload cover' . '
                            </a>
                           
                            <hr class="menu-separator" />
                    </div>
                </div>
            </div>   
			   
		   </div>
				
            ';
		}
		$__finalCompiled .= '
			';
	}
	$__finalCompiled .= '
        </div>
		
	<!-- Approval Status -->
		
		';
	if (($__vars['subForums']['node_state'] == 'moderated') OR ($__vars['subForums']['node_state'] == 'deleted')) {
		$__finalCompiled .= '
		<div class="block-outer">
			<dl class="blockStatus" style="margin-top: 10px;">
				<dt>' . 'Status' . '</dt>
					';
		if ($__vars['subForums']['node_state'] == 'deleted') {
			$__finalCompiled .= '
						<dd class="blockStatus-message blockStatus-message--deleted">
							' . $__templater->callMacro('deletion_macros', 'notice', array(
				'log' => $__vars['thread']['DeletionLog'],
			), $__vars) . '
						</dd>
					';
		} else if ($__vars['subForums']['node_state'] == 'moderated') {
			$__finalCompiled .= '
						<dd class="blockStatus-message blockStatus-message--moderated">
							' . 'Awaiting approval before being displayed publicly.' . '
						</dd>
					';
		}
		$__finalCompiled .= '
			</dl>
		</div>
		';
	}
	$__finalCompiled .= '
    </div>
	
	<!-- Approval Status -->

	<!-- Cover Header -->
		
	<!-- Thread Lists -->
	
	';
	if ($__vars['subForums']['node_state'] == 'visible') {
		$__finalCompiled .= '
	
	';
		if (!$__templater->test($__vars['threads'], 'empty', array())) {
			$__finalCompiled .= '
		<div class="block-container">
		<div class="block-body">
				<div class="structItemContainer">
					
						<div class="structItemContainer-group js-threadList">
							';
			if (!$__templater->test($__vars['threads'], 'empty', array())) {
				$__finalCompiled .= '
								';
				if ($__templater->isTraversable($__vars['threads'])) {
					foreach ($__vars['threads'] AS $__vars['thread']) {
						$__finalCompiled .= '
									' . $__templater->callMacro(null, 'fs_forum_groups_thread_list_macros::item', $__templater->combineMacroArgumentAttributes(null, array(
							'thread' => $__vars['thread'],
							'forum' => $__vars['forum'],
						)), $__vars) . '
								';
					}
				}
				$__finalCompiled .= '
							';
			}
			$__finalCompiled .= '
						</div>		
			</div>
			</div>
		</div>
					';
		} else {
			$__finalCompiled .= '
						<div class="blockMessage  ">
							<div class="structItem-cell">' . 'There are no threads in this forum.' . '</div>
						</div>
					';
		}
		$__finalCompiled .= '
					
	';
	}
	$__finalCompiled .= '
	
	<!-- Thread Lists -->
	
</div>';
	return $__finalCompiled;
}
);
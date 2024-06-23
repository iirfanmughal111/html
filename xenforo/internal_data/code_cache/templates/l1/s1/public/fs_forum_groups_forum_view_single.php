<?php
// FROM HASH: c4837bdfce83d617aab2cf58ad63641e
return array(
'macros' => array('fs_forum_groups_forum_view_single_macro' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'subForums' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
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
			  ' . $__templater->func('avatar', array($__vars['xf']['visitor'], 'l', false, array(
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
        </div>
    </div>

	<!-- Cover Header -->
	
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
	
</div>
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';

	return $__finalCompiled;
}
);
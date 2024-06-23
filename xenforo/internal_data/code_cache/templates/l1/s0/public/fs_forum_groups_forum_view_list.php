<?php
// FROM HASH: 8dc73e0f3e10b0a24ede2915e658142f
return array(
'macros' => array('fs_forum_groups_forum_view_list_macro' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'subForum' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '	  			
	  <div class="gridCard js-inlineModContainer visible public" id="' . $__templater->func('unique_id', array(), true) . '">
		  
        <div class="gridCard--container">
			
			<!-- Cover -->
			
			<div class="gridCard--cover">
			
				<div class="groupCover--wrapper">
                
        <div class="groupCover groupCoverFrame groupCover--default" style="background-color:#' . $__templater->escape($__templater->method($__vars['subForum'], 'getRandomColor', array())) . ';color:#70dbb8">
            <a href="' . $__templater->func('link', array('forums', $__vars['subForum'], ), true) . '" style="color:#fff">
                ';
	if ($__vars['subForum']['CoverAttachment']) {
		$__finalCompiled .= '
                <img data-crop="' . $__templater->filter($__templater->method($__vars['subForum'], 'getCoverCropData', array()), array(array('json', array()),), true) . '"
                     class="groupCover--img groupCover--lazy" data-xf-init="fs-forum-groups-cover-setup" data-force-height="100"
                     ' . ($__templater->method($__vars['subForum'], 'getImageAttributes', array()) ? (' ' . $__templater->escape($__templater->method($__vars['subForum'], 'getImageAttributes', array()))) : '') . '/>
            ';
	} else {
		$__finalCompiled .= '
                <span class="groupCover--text">' . $__templater->func('snippet', array($__vars['subForum']['title'], 25, ), true) . '</span>
            ';
	}
	$__finalCompiled .= '
            </a>
        </div>
                   
                

            </div>
			
			</div>
			
			<!-- Cover -->
			
			
			<!-- Header -->
			
	  
	   <div class="gridCard--header">
		   
			<!-- Avatar -->
		   
		   <div class="gridCard--header--avatar">
			   
           ';
	if ($__vars['subForum']['AvatarAttachment']) {
		$__finalCompiled .= '
			   <a href="' . $__templater->func('link', array('forums', $__vars['subForum'], ), true) . '"
       class="groupAvatar groupAvatar--link groupAvatar--default" style="background-color:#e08585;color:#8f2424">
			<img src="' . $__templater->escape($__vars['subForum']['AvatarAttachment']['thumbnail_url']) . '"
                 class="groupAvatar--img bbImage" width="100" height="100"
                 data-width="' . $__templater->escape($__vars['subForum']['AvatarAttachment']['width']) . '"
                 data-height="' . $__templater->escape($__vars['subForum']['AvatarAttachment']['height']) . '"
                 alt="' . $__templater->escape($__vars['subForum']['title']) . '"/>
    </a>		   
			   
			';
	} else {
		$__finalCompiled .= '
			   ' . '
			   			  ' . $__templater->func('avatar', array($__vars['xf']['visitor'], 'l', false, array(
		))) . '
			';
	}
	$__finalCompiled .= '
		   </div>
		   
			<!-- Avatar -->
		   
			<!-- Header Main -->
              
                <div class="gridCard--header--main">
                    
		' . $__templater->func('trim', array('
        <a href="' . $__templater->func('link', array('forums', $__vars['subForum'], ), true) . '" class="gridCard--header--title"
           data-tp-primary="on">
            <span>' . $__templater->escape($__vars['subForum']['title']) . '</span>
        </a>
    '), false) . '					
					
					
		';
	$__compilerTemp1 = '';
	if ($__vars['xf']['options']['tl_groups_enableForums']) {
		$__compilerTemp1 .= '
                <li class="groupItem-stat groupItem-stat--discussionCount">
                    ' . $__templater->fontAwesome('fa-comment', array(
		)) . '
                    ' . $__templater->escape($__vars['subForum']['Forum']['message_count']) . '
                </li>
            ';
	}
	$__finalCompiled .= $__templater->func('trim', array('
        <ul class="listInline group--counterList u-muted">
            <li class="groupItem-stat groupItem-stat--viewCount" style="margin-right:10px;">
                ' . $__templater->fontAwesome('fa-eye', array(
	)) . '
                ' . $__templater->escape($__templater->method($__vars['subForum'], 'getViewCounts', array())) . '
            </li>
  
            ' . $__compilerTemp1 . '
        </ul>
    '), false) . '
						
                </div>
		   
			<!-- Header Main -->
		   
		
		   
            </div>
			
			<!-- Header -->
			
	  ';
	$__compilerTemp2 = '';
	$__compilerTemp2 .= '
					
						<div class="groupList--description u-muted">' . $__templater->escape($__vars['subForum']['description']) . '</div>
						
					';
	if (strlen(trim($__compilerTemp2)) > 0) {
		$__finalCompiled .= '
                <div class="gridCard--body">
                    ' . $__compilerTemp2 . '
                </div>
            ';
	}
	$__finalCompiled .= '
			
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

    <!--Sub Forums List View--->';
	return $__finalCompiled;
}
);
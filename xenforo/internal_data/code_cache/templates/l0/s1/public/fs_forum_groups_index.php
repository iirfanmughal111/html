<?php
// FROM HASH: ca9272065b9ea5b51b1f60a7c25fc993
return array(
'macros' => array('forum_groups_sub_forum_list' => array(
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
            <a href="' . $__templater->func('link', array('forumGroups', $__vars['subForum'], ), true) . '" style="color:#fff">
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
			   <a href="' . $__templater->func('link', array('forumGroups', $__vars['subForum'], ), true) . '"
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
			   			  ' . $__templater->func('avatar', array($__vars['subForum']['User'], 'l', false, array(
		))) . '
			';
	}
	$__finalCompiled .= '
		   </div>
		   
			<!-- Avatar -->
		   
			<!-- Header Main -->
              
                <div class="gridCard--header--main">
                    
		' . $__templater->func('trim', array('
        <a href="' . $__templater->func('link', array('forumGroups', $__vars['subForum'], ), true) . '" class="gridCard--header--title"
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
		   
			<!-- Action -->
		   
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
                                <a href="' . $__templater->func('link', array('forumGroups/add-moderator', $__vars['subForum'], ), true) . '"
                                       class="menu-linkRow"
                                       data-xf-click="overlay">
                                ' . 'Add Moderator' . '
                            </a>
                           
                            <hr class="menu-separator" />
                    </div>
                </div>
            </div>   
			   
		   </div>
		   
			<!-- Action -->
		   
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
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Forum Groups');
	$__templater->pageParams['pageNumber'] = $__vars['page'];
	$__finalCompiled .= '

';
	$__templater->setPageParam('searchConstraints', array('Auctions' => array('search_type' => 'fs_auction_auctions', ), ));
	$__finalCompiled .= '

';
	if ($__vars['xf']['visitor']['user_id']) {
		$__templater->pageParams['pageAction'] = $__templater->preEscaped('
    ' . $__templater->button('Add Group', array(
			'href' => $__templater->func('link', array('forumGroups/add', ), false),
			'class' => 'button--cta',
			'icon' => 'write',
		), '', array(
		)) . '
');
	}
	$__finalCompiled .= '
  

<div
  class="block"
  data-xf-init="' . ($__vars['canInlineMod'] ? 'inline-mod' : '') . '"
  data-type="fs_forum_groups"
  data-href="' . $__templater->func('link', array('inline-mod', ), true) . '"
>
  <div class="block-outer">

  </div>
  <div class="block-container">

    <!--Listing View--->
    <div class="block-body">
		
    <!--Sub Forums List View--->
		';
	if ($__templater->func('count', array($__vars['subForums'], ), false) != 0) {
		$__finalCompiled .= '
            
			    ';
		$__vars['dummyArray'] = $__templater->func('range', array(0, 3, ), false);
		$__finalCompiled .= '
		
		
			 	<div class="block groupListBlock" data-xf-init="inline-mod"
         data-type="tl_group"
         data-href="' . $__templater->func('link', array('inline-mod', ), true) . '">
		
		<div class="groupList h-dFlex h-dFlex--wrap gridCardList--flex--' . $__templater->escape($__vars['xf']['options']['fs_forum_gorups_per_row']) . '-col" data-xf-init="tl_groups_list">
		
		 ';
		if ($__templater->isTraversable($__vars['subForums'])) {
			foreach ($__vars['subForums'] AS $__vars['value']) {
				$__finalCompiled .= '

    				' . $__templater->callMacro(null, 'forum_groups_sub_forum_list', array(
					'subForum' => $__vars['value'],
				), $__vars) . '
			 
			 
  ';
			}
		}
		$__finalCompiled .= '
					</div>
		</div>
			';
	}
	$__finalCompiled .= '
		
	';
	$__templater->includeCss('fs_forum_gorups_group_list.less');
	$__finalCompiled .= '
    ';
	$__templater->includeCss('fs_forum_gorups_style.less');
	$__finalCompiled .= '
	';
	$__templater->includeCss('fs_forum_gorups_grid_card.less');
	$__finalCompiled .= '
		
    <!--Sub Forums List View--->

      <div class="block-footer">
        <span class="block-footer-counter"
          >' . $__templater->func('display_totals', array($__vars['totalReturn'], $__vars['total'], ), true) . '</span
        >
      </div>
    </div>
  </div>

  <div class="block-outer block-outer--after">

    ' . $__templater->func('show_ignored', array(array(
		'wrapperclass' => 'block-outer-opposite',
	))) . '
  </div>
</div>

';
	$__templater->setPageParam('sideNavTitle', 'Sub Communties');
	$__finalCompiled .= '

<!-- Filter Bar Macro Start -->

    <!--Sub Forums List View--->

' . '

    <!--Sub Forums List View--->';
	return $__finalCompiled;
}
);
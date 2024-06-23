<?php
// FROM HASH: d8947653b5b1aab758f08de94c3fb605
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->includeCss('DC_LinkProxy.less');
	$__finalCompiled .= '
';
	$__templater->inlineCss('
	.count {
		-webkit-animation: fadeout .5s calc(' . $__vars['redirect_time'] . ' + 1) 1 linear;
    	-webkit-animation-fill-mode: forwards;	
	}
	
	.l-half:before, .r-half:before
	{
		-webkit-animation-duration: ' . $__vars['redirect_time'] . 's;
        -webkit-animation-iteration-count: 1;
        -webkit-animation-timing-function: linear;
        -webkit-animation-fill-mode: forwards;
	}
');
	$__finalCompiled .= '
';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Redirecting...');
	$__finalCompiled .= '

';
	$__templater->pageParams['noH1'] = true;
	$__finalCompiled .= '
';
	$__templater->setPageParam('head.' . 'metaNoindex', $__templater->preEscaped('<meta name="robots" content="noindex" />'));
	$__finalCompiled .= '

<div class="DC_LinkProxy">
	<div class="DC_LinkProxy__wrapper">
		';
	if ($__vars['xf']['options']['fs_link_html_position']) {
		$__finalCompiled .= '
			<div class="custom_html">
			' . $__templater->filter($__vars['html'], array(array('raw', array()),), true) . '
				
			</div>
		';
	}
	$__finalCompiled .= '
		<h3 class="DC_LinkProxy__title">' . 'Please be careful...' . '</h3>
		<div class="DC_LinkProxy__content">
			<div class="message">
				' . 'You are going to' . ': <b>' . $__templater->escape($__vars['url']) . '</b>
				<br/><br/>
				' . 'The referring site you are being redirected to is not controlled by us, so please remember not to enter your Username and Password unless you are on our board. Also be sure to download software from sites you trust. And remember to read the site\'s Privacy Policy.' . '
			</div>
			
			';
	if ($__vars['xf']['options']['DC_LinkProxy_AutoRedirection']) {
		$__finalCompiled .= '
				' . $__templater->includeTemplate('DC_LinkProxy.js', $__vars) . '
				<div class="message redirecting">
					' . 'You will be redirected in' . '
					
					<div class="circle">
						<div class="count"><span id="DC_LinkProxy_AutoRedirection__timer">' . $__templater->escape($__vars['redirect_time']) . '</span></div>
						<div class="l-half"></div>
						<div class="r-half"></div>
					</div>
				
				</div>
			';
	}
	$__finalCompiled .= '
				
			' . $__templater->button('Continune', array(
		'href' => $__vars['url'],
		'rel' => 'nofollow',
		'id' => 'continue_btn',
		'class' => 'DC_LinkProxy_Continune d-none',
	), '', array(
	)) . '
			
		</div>
		';
	if (!$__vars['xf']['options']['fs_link_html_position']) {
		$__finalCompiled .= '
			<div class="custom_html">
			' . $__templater->filter($__vars['html'], array(array('raw', array()),), true) . '
				
			</div>
		';
	}
	$__finalCompiled .= '
		
		
	</div>
</div>';
	return $__finalCompiled;
}
);
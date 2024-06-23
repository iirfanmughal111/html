<?php
// FROM HASH: c29141bdc63867de7ea4340beffba2e9
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->includeCss('fs_rule_landing_page.less');
	$__finalCompiled .= '
<div class="container-landing">
	' . '
	<br>
	<br>
	<h3 class="header-title">' . 'Profiles, References, Verifications and Listing Forums.' . '</h3>
	<div class="swb-rules"> ' . '	<h4>
				YOU MUST BE 21 YEARS OF AGE OR OVER TO ENTER THIS SITE!
			</h4>
			<br>
			<p class="text-rules">
				This verification website includes mature content, explicit sexual material, and adult language.
Access is restricted to individuals who are at least 21 years old and are physically
situated in the location from where they are accessing the site. By entering this site, you
affirm that you are at least 21 years old and consent to our Terms & Conditions.
Unauthorized utilization of this website may be a violation of local, federal, or
international laws.
			</p>
			<p class="text-rules">
				SouthWestBoard neither generates nor oversees any content featured in our listings. However, all listings must adhere to our age restrictions and abide by our
terms and conditions.
				
			</p>
			
			<p class="text-rules">
				SouthWestBoard strictly prohibits any engagement with child pornography or any
involvement of minors on our platform. By using our site, you commit to promptly report
any unlawful services or activities that breach our Terms of Use.
				
			</p>
			<p class="text-rules">
				Furthermore, you consent to report any suspected instances of minor exploitation
and/or human trafficking to the relevant authorities.
			</p>
		' . '
		<br>
		
			' . $__templater->button('Agree', array(
		'href' => $__templater->func('link', array('forums/', $__vars['null'], ), false),
		'class' => 'button-agree',
	), '', array(
	)) . '
			' . $__templater->button('Disagree and leave', array(
		'href' => 'http://google.com/',
		'class' => 'button-disagree',
	), '', array(
	)) . '
		
	</div>
	<br>
	<br>
	<br>
	<footer class="landing-footer">
		<!-- Footer content --><a  href="' . $__templater->escape($__vars['xf']['contactUrl']) . '">' . 'Customer Support' . '</a> | <a href="' . $__templater->escape($__vars['xf']['tosUrl']) . '">' . 'Terms' . '</a>| <a href="' . $__templater->escape($__vars['xf']['privacyPolicyUrl']) . '">' . 'Privacy' . '</a>| <a href="' . $__templater->func('link', array('help/compliance', ), true) . '">' . '2257 Exemption' . '</a>| <a href="' . $__templater->escape($__vars['xf']['homePageUrl']) . '">' . 'Home' . '</a> </footer>';
	return $__finalCompiled;
}
);
<?php
// FROM HASH: 9b3ae0156f842533a5ee5425737d0a99
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->includeCss('regsitration_steps.less');
	$__finalCompiled .= '
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" integrity="sha512-tS3S5qG0BlhnQROyJXvNjeEM4UpMXHrQfTGmbQ1gKmelCxlSEBUaxhRBj/EFTzpbP4RVSrpEikbmdJobCvhE3g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" integrity="sha512-sMXtMNL1zRzolHYKEujM2AqCLUR9F2C4/05cdbxjjLSRvMQIciEPCQZo++nk7go3BtSuK9kfa/s+a4f4i5pLkw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<div class="block"' . $__templater->func('widget_data', array($__vars['widget'], ), true) . '>
	';
	if ($__templater->func('count', array($__vars['featured_threads'], ), false)) {
		$__finalCompiled .= '
	
	<div class="block-container">
		<h3 class="block-minorHeader">' . 'fs_regisgter_featuerd_title' . '</h3>
		<div class="block-body block-row">
			<div class="owl-carousel">
				';
		if ($__templater->isTraversable($__vars['featured_threads'])) {
			foreach ($__vars['featured_threads'] AS $__vars['thread']) {
				$__finalCompiled .= '
					<div class="text-center">
						';
				if ($__templater->func('count', array($__vars['thread']['FirstPost']['Attachments'], ), false)) {
					$__finalCompiled .= '
						<img src="' . $__templater->func('link', array('full:attachments', $__templater->method($__vars['thread']['FirstPost']['Attachments'], 'first', array()), ), true) . '" alt="' . $__templater->escape($__vars['thread']['title']) . '" class="thread-img1" />
							';
				} else {
					$__finalCompiled .= '
							' . $__templater->func('avatar', array($__vars['thread']['User'], 'l', false, array(
						'href' => $__templater->func('link', array('members', $__vars['thread']['User'], ), false),
						'class' => 'square-border',
					))) . '
						';
				}
				$__finalCompiled .= '
						
						<p>
							<a href="' . $__templater->func('link', array('threads', $__vars['thread'], ), true) . '">  ' . $__templater->escape($__vars['thread']['title']) . '</a>
						</p>
						
					</div>
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
		
</div>

';
	$__templater->inlineJs('
	$(document).ready(function(){
	  $(\'.owl-carousel\').owlCarousel({
		loop:true,
		margin:10,
		autoplay:true,
		autoplaySpeed:2000,
		autoplayTimeout:3000,
		responsiveClass:true,
		nav:true,
	    navRewind:true,
		center:true,
		responsive:{
			0:{
				items:1,
			
			},
			400:{
				items:2,
			
			},
			600:{
				items:4,
			
			},
			1000:{
				items:5,
				
			
			}
		}

	})
});
');
	return $__finalCompiled;
}
);
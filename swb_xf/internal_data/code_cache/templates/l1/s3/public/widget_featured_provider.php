<?php
// FROM HASH: 53ebdab6bda6b060402b9ad5a98c537a
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->includeCss('regsitration_steps.less');
	$__finalCompiled .= '
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" integrity="sha512-tS3S5qG0BlhnQROyJXvNjeEM4UpMXHrQfTGmbQ1gKmelCxlSEBUaxhRBj/EFTzpbP4RVSrpEikbmdJobCvhE3g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" integrity="sha512-sMXtMNL1zRzolHYKEujM2AqCLUR9F2C4/05cdbxjjLSRvMQIciEPCQZo++nk7go3BtSuK9kfa/s+a4f4i5pLkw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<div class="block"' . $__templater->func('widget_data', array($__vars['widget'], ), true) . '>
	<div class="block-container">
		<h3 class="block-minorHeader">' . $__templater->escape($__vars['title']) . '</h3>
		<div class="block-body block-row">
			<div class="owl-carousel">
				';
	if ($__templater->isTraversable($__vars['users'])) {
		foreach ($__vars['users'] AS $__vars['user']) {
			$__finalCompiled .= '
					<div class="text-center">
						' . $__templater->func('avatar', array($__vars['user'], 'l', false, array(
				'href' => $__templater->func('link', array('members', $__vars['user'], ), false),
				'class' => 'square-border',
			))) . '
						<p>
							<a href="' . $__templater->func('link', array('members', $__vars['user'], ), true) . '">  ' . $__templater->escape($__vars['user']['username']) . '</a>
						</p>
						
					</div>
				';
		}
	}
	$__finalCompiled .= '
			</div>
		</div>
	</div>
</div>

';
	$__templater->inlineJs('
	$(document).ready(function(){
	  $(\'.owl-carousel\').owlCarousel({
		loop:true,
		margin:1,
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
				items:3,
			
			},
			1000:{
				items:4,
				
				loop:true
			}
		}

	})
});
');
	return $__finalCompiled;
}
);
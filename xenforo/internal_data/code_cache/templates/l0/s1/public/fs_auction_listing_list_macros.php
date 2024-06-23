<?php
// FROM HASH: b20d213e6ef1ae95e6dd32a88fc68f55
return array(
'macros' => array('listing' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'listing' => '!',
		'category' => null,
		'showWatched' => true,
		'allowInlineMod' => true,
		'chooseName' => '',
		'extraInfo' => '',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__templater->includeCss('structured_list.less');
	$__finalCompiled .= '
	';
	$__templater->includeCss('fs_auction_list_view.less');
	$__finalCompiled .= '
	
	<div class="structItem structItem--listing js-inlineModContainer " id="auction-' . $__templater->escape($__vars['listing']['auction_id']) . '" data-author="' . ($__templater->escape($__vars['listing']['Thread']['username']) ?: '') . '">
		<div class="structItem-cell structItem-cell--icon structItem-cell--iconExpanded structItem-cell--iconListingCoverImage">
			<div class="structItem-iconContainer">
				';
	if ($__vars['listing']['Thread']['auction_end_date'] > $__vars['xf']['time']) {
		$__finalCompiled .= '
						<a href="' . $__templater->func('link', array('auction/view-auction', $__vars['listing'], ), true) . '" class="" data-tp-primary="on">
							<img src ="' . ($__templater->func('count', array($__vars['listing']['Thread']['FirstPost']['Attachments'], ), false) ? $__templater->func('link', array('full:attachments', $__templater->method($__vars['listing']['Thread']['FirstPost']['Attachments'], 'first', array()), ), true) : $__templater->func('base_url', array('styles/FS/AuctionPlugin/no_image.png', true, ), true)) . '" onload="timmerCounter(' . $__templater->escape($__vars['listing']['auction_id']) . ',' . $__templater->escape($__vars['listing']['Thread']['auction_end_date']) . ')" style="min-height: 92px; max-height: 92px;"></a>
				';
	} else {
		$__finalCompiled .= '
						<a href="' . $__templater->func('link', array('auction/view-auction', $__vars['listing'], ), true) . '" class="" data-tp-primary="on">
							<img src ="' . ($__templater->func('count', array($__vars['listing']['Thread']['FirstPost']['Attachments'], ), false) ? $__templater->func('link', array('full:attachments', $__templater->method($__vars['listing']['Thread']['FirstPost']['Attachments'], 'first', array()), ), true) : $__templater->func('base_url', array('styles/FS/AuctionPlugin/no_image.png', true, ), true)) . '" style="min-height: 92px; max-height: 92px;"></a>
				';
	}
	$__finalCompiled .= '
						
			</div>
		</div>
			
				
		<div class="structItem-cell structItem-cell--main" data-xf-init="touch-proxy">
		
			<div class="structItem-title">
			<span class="label label--blue label--smallest">
						' . $__templater->func('phrase_dynamic', array($__templater->method($__vars['listing']['Thread']['Prefix'], 'getPhraseName', array()), ), true) . '
					</span>
				<a href="' . $__templater->func('link', array('auction/view-auction', $__vars['listing'], ), true) . '" class="" data-tp-primary="on">' . $__templater->escape($__vars['listing']['Thread']['title']) . '</a>
					' . '
						</div>
			<div class="structItem-minor">

					<ul class="structItem-parts">
						<li>' . $__templater->func('username_link', array($__vars['listing']['Thread']['User'], false, array(
		'defaultname' => $__vars['listing']['Thread']['User'],
	))) . '</li>
						<li class="structItem-startDate">
							' . $__templater->func('date_dynamic', array($__vars['listing']['Thread']['post_date'], array(
	))) . ' 
							
						</li>
						
							<li>' . $__templater->func('snippet', array($__vars['listing']['Category']['title'], 50, array('stripBbCode' => true, ), ), true) . '</li>
				
				</ul>
			</div>
			
		
			
				<div class="auction-category">' . $__templater->func('snippet', array($__vars['listing']['Thread']['FirstPost']['message'], 100, array('stripBbCode' => true, ), ), true) . '</div>
			
		
		</div>
		<div class="structItem-cell structItem-cell--listingMeta">

			<dl class="pairs pairs--justified structItem-minor structItem-metaItem structItem-metaItem--type">
				<dt >' . 'Expire' . '</dt>
				<dd>
					' . $__templater->escape($__templater->method($__vars['listing']['Thread'], 'getFormatedTime12', array())) . '

				</dd>
			</dl>
			<dl class="pairs pairs--justified structItem-minor structItem-metaItem structItem-metaItem--type">
				<dt id="counter-before">
				
				</dt>
				<dd>	';
	if ($__vars['listing']['Thread']['auction_end_date'] < $__vars['xf']['time']) {
		$__finalCompiled .= '
						<li>
							<span class="label label--orange label--smallest">' . 'Bidding Closed' . ' 
								<i class="structItem-status structItem-status--locked" 
								   aria-hidden="true" title="' . $__templater->filter('Locked', array(array('for_attr', array()),), true) . '"></i>
							<span class="u-srOnly">' . 'auctionLocked' . '</span>
							</span>
						</li>
					';
	} else {
		$__finalCompiled .= '
						<li>
							<div id="auction-counter-' . $__templater->escape($__vars['listing']['auction_id']) . '">
								
						<span class="label  label--blue label--counter" id="days-auction-' . $__templater->escape($__vars['listing']['auction_id']) . '">
							 ' . '00 D' . '
						</span>
						<span class="label  label--blue label--counter" id="hours-auction-' . $__templater->escape($__vars['listing']['auction_id']) . '">
							 ' . '00 H' . '
						</span>
							<span class="label  label--blue label--counter" id="minutes-auction-' . $__templater->escape($__vars['listing']['auction_id']) . '">
							' . '00 M' . '
						</span>
							<span class="label  label--blue label--counter" id="seconds-auction-' . $__templater->escape($__vars['listing']['auction_id']) . '">
							 ' . '00 S' . '
						</span>
							</div>
						
					</li>
					';
	}
	$__finalCompiled .= ' </dd>
			</dl>
				';
	if ($__templater->method($__vars['listing']['Thread'], 'getMaxBidOfAuction', array($__vars['listing']['auction_id'], ))) {
		$__finalCompiled .= '
					
							<dl style="margin-top:5px;" class="pairs pairs--justified structItem-minor structItem-metaItem structItem-metaItem--expiration">
					<dt ><b>' . 'Current bid' . '</b></dt>
					<dd >
							<b>' . ' $' . $__templater->escape($__templater->method($__vars['listing']['Thread'], 'getMaxBidOfAuction', array($__vars['listing']['auction_id'], ))) . '
					</b>
								</dd>
				</dl>
							
						';
	}
	$__finalCompiled .= '
			
				<dl class="pairs pairs--justified structItem-minor structItem-metaItem structItem-metaItem--expiration">
					<dt>' . 'Bid' . '</dt>
					<dd>
						' . ' $' . $__templater->escape($__vars['listing']['Thread']['custom_fields']['starting_bid']) . '
					</dd>
				</dl>
		</div>
	</div>
';
	return $__finalCompiled;
}
),
'listing_grid' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'listing' => '!',
		'category' => null,
		'showWatched' => true,
		'allowInlineMod' => true,
		'chooseName' => '',
		'extraInfo' => '',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	';
	$__templater->includeCss('structured_list.less');
	$__finalCompiled .= '
	
	';
	$__templater->includeCss('fs_auctions.less');
	$__finalCompiled .= '
	
	';
	$__templater->includeCss('fs_auction_listing_grid_view.less');
	$__finalCompiled .= '

	
	<div id="auction-' . $__templater->escape($__vars['listing']['auction_id']) . '" class="structItem structItem--listing js-inlineModContainer js-listingListItem-' . $__templater->escape($__vars['listing']['auction_id']) . '" style="display: grid;
    justify-content: center;
}">
		<div class="structItem-cell structItem-cell--icon structItem-cell--iconExpanded structItem-cell--iconListingCoverImage">
			<div class="structItem-iconContainer" >
				';
	if ($__vars['listing']['Thread']['auction_end_date'] > $__vars['xf']['time']) {
		$__finalCompiled .= '
					<a href="' . $__templater->func('link', array('auction/view-auction', $__vars['listing'], ), true) . '" class="" data-tp-primary="on"><img src ="' . ($__templater->func('count', array($__vars['listing']['Thread']['FirstPost']['Attachments'], ), false) ? $__templater->func('link', array('full:attachments', $__templater->method($__vars['listing']['Thread']['FirstPost']['Attachments'], 'first', array()), ), true) : $__templater->func('base_url', array('styles/FS/AuctionPlugin/no_image.png', true, ), true)) . '" loading="lazy" class="auction-itemGrid-img" onload="timmerCounter(' . $__templater->escape($__vars['listing']['auction_id']) . ',' . $__templater->escape($__vars['listing']['Thread']['auction_end_date']) . ')"></a>
				';
	} else {
		$__finalCompiled .= '
							<a href="' . $__templater->func('link', array('auction/view-auction', $__vars['listing'], ), true) . '" class="" data-tp-primary="on"><img src ="' . ($__templater->func('count', array($__vars['listing']['Thread']['FirstPost']['Attachments'], ), false) ? $__templater->func('link', array('full:attachments', $__templater->method($__vars['listing']['Thread']['FirstPost']['Attachments'], 'first', array()), ), true) : $__templater->func('base_url', array('styles/FS/AuctionPlugin/no_image.png', true, ), true)) . '" class="auction-itemGrid-img" loading="lazy"></a>
				';
	}
	$__finalCompiled .= '
				
			</div>
		</div>
		
		<div class="structItem-cell structItem-cell--listingMeta">
			<div class="structItem-cell structItem-cell--main" >

			
				<div >
					<span class="auction-category">' . $__templater->func('snippet', array($__vars['listing']['Category']['title'], 50, array('stripBbCode' => true, ), ), true) . '</span>
				</div>
				
				<div >
	
					';
	if ($__vars['listing']['Thread']['auction_end_date'] < $__vars['xf']['time']) {
		$__finalCompiled .= '
						
				<dl class="pairs pairs--justified structItem-minor structItem-metaItem structItem-metaItem--expiration">
					<dt>' . 'Expire' . '</dt>
					<dd>		
						<span class="label label--orange label--smallest">' . 'Bidding Closed' . ' 
								<i class="structItem-status structItem-status--locked" 
								   aria-hidden="true" title="' . $__templater->filter('Locked', array(array('for_attr', array()),), true) . '"></i>
							<span class="u-srOnly">' . 'auctionLocked' . '</span>
						</span>
						</dd>
				</dl>						
					';
	} else {
		$__finalCompiled .= '
						
				<dl class="pairs pairs--justified structItem-minor structItem-metaItem structItem-metaItem--expiration">
						<dt>' . 'Expire' . '</dt>
						<dd id="auction-counter-' . $__templater->escape($__vars['listing']['auction_id']) . '">	
							
							<span class="label  label--blue label--counter" id="days-auction-' . $__templater->escape($__vars['listing']['auction_id']) . '">
								 ' . '00 D' . '
							</span>
							<span class="label  label--blue label--counter" id="hours-auction-' . $__templater->escape($__vars['listing']['auction_id']) . '">
								' . '00 H' . '
							</span>
							<span class="label  label--blue label--counter" id="minutes-auction-' . $__templater->escape($__vars['listing']['auction_id']) . '">
						 		' . '00 M' . '
							</span>
							<span class="label  label--blue label--counter" id="seconds-auction-' . $__templater->escape($__vars['listing']['auction_id']) . '">
								' . '00 S' . '
							</span>
							
						</dd>
				</dl>
	
			';
	}
	$__finalCompiled .= '
				</div>

			<div class="structItem-title">
					<span class="label label--blue label--smallest">
						' . $__templater->func('phrase_dynamic', array($__templater->method($__vars['listing']['Thread']['Prefix'], 'getPhraseName', array()), ), true) . '
							
					</span>
			<a href="' . $__templater->func('link', array('auction/view-auction', $__vars['listing'], ), true) . '" class="" data-tp-primary="on">' . $__templater->func('snippet', array($__vars['listing']['Thread']['title'], 33, array('stripBbCode' => true, ), ), true) . '</a>
			</div>
			<div class="structItem-minor">
					<ul class="structItem-parts">
						<li>' . $__templater->func('username_link', array($__vars['listing']['Thread']['User'], false, array(
		'defaultname' => $__vars['listing']['Thread']['User'],
	))) . '</li>
						<li class="structItem-startDate">' . $__templater->func('date_dynamic', array($__vars['listing']['Thread']['post_date'], array(
	))) . '</li>
						';
	if ((!$__vars['category']) OR $__templater->method($__vars['category'], 'hasChildren', array())) {
		$__finalCompiled .= '
							<li>' . 'Expire' . ' . ' . $__templater->escape($__templater->method($__vars['listing']['Thread'], 'getFormatedTime12', array())) . '
</li>
						';
	}
	$__finalCompiled .= '
				<div class="auction-category">' . $__templater->func('snippet', array($__vars['listing']['Thread']['FirstPost']['message'], 50, array('stripBbCode' => true, ), ), true) . '</div>
						';
	if ($__templater->method($__vars['listing']['Thread'], 'getMaxBidOfAuction', array($__vars['listing']['auction_id'], ))) {
		$__finalCompiled .= '
					
							<dl class="pairs pairs--justified structItem-minor structItem-metaItem structItem-metaItem--expiration">
								<dt ><b>' . 'Current bid' . '</b></dt>
					<dd >
							<b>' . ' $' . $__templater->escape($__templater->method($__vars['listing']['Thread'], 'getMaxBidOfAuction', array($__vars['listing']['auction_id'], ))) . '</b>
					</dd>
				</dl>
							
						';
	}
	$__finalCompiled .= '
							
					</ul>

			</div>

				<dl class="pairs pairs--justified structItem-minor structItem-metaItem structItem-metaItem--expiration">
					<dt>' . 'Bid' . '</dt>
					<dd>
						' . ' $' . $__templater->escape($__vars['listing']['Thread']['custom_fields']['starting_bid']) . '
					</dd>
				</dl>
		
		</div>

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

';
	return $__finalCompiled;
}
);
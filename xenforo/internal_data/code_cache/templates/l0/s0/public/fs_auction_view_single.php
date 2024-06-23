<?php
// FROM HASH: 5f70b625ffd6b05c5d82c2e8c2e1a73a
return array(
'macros' => array('singleAuction' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'auction' => '!',
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
	
	<div class="structItem structItem--listing js-inlineModContainer">	
		<div class="structItem-cell structItem-cell--main" data-xf-init="touch-proxy">	
			<div class="message-fields message-fields--after">
				<dl class="pairs pairs--columns pairs--fixedSmall pairs--customField" data-field="threadCustomField">
					<dt>' . 'AUCTION ENDS ON' . '</dt>
					<dd>
						' . $__templater->escape($__templater->method($__vars['auction']['Thread'], 'getFormatedTime12', array())) . '
				
					</dd>
				</dl>
			
			</div>
			' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
		'type' => 'threads',
		'group' => 'after',
		'onlyInclude' => $__vars['thread']['Forum']['field_cache'],
		'set' => $__vars['auction']['Thread']['custom_fields'],
		'wrapperClass' => 'message-fields message-fields--after',
	), $__vars) . '

		</div>
		
		
		
		<div class="structItem-cell structItem-cell--listingMeta" style="width:320px">

				<div id="auction-counter" style="display:none;">
								
						<span class="label  label--blue label--counter-single" id="days-auction">
							 ' . '00 D' . '
						</span>
						<span class="label  label--blue label--counter-single" id="hours-auction">
							 ' . '00 H' . '
						</span>
							<span class="label  label--blue label--counter-single" id="minutes-auction">
							' . '00 M' . '
						</span>
							<span class="label  label--blue label--counter-single" id="seconds-auction">
							 ' . '00 S' . '
						</span>
				</div>
			
		</div>
	</div>
';
	return $__finalCompiled;
}
),
'bidding_table_list' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'bidding' => $__vars['bidding'],
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
  ' . $__templater->dataRow(array(
		'rowtype' => 'header',
	), array(array(
		'_type' => 'cell',
		'html' => ' ' . 'By User' . ' ',
	),
	array(
		'_type' => 'cell',
		'html' => ' ' . 'Bid At' . ' ',
	),
	array(
		'_type' => 'cell',
		'html' => ' ' . 'Amount' . ' ',
	))) . '
  ';
	if ($__templater->isTraversable($__vars['bidding'])) {
		foreach ($__vars['bidding'] AS $__vars['val']) {
			$__finalCompiled .= '
    ' . $__templater->dataRow(array(
			), array(array(
				'href' => $__templater->func('link', array('members/', $__vars['val'], ), false),
				'_type' => 'cell',
				'html' => ' ' . $__templater->escape($__vars['val']['User']['username']) . ' ',
			),
			array(
				'_type' => 'cell',
				'html' => ' ' . $__templater->func('date_dynamic', array($__vars['val']['created_at'], array(
			))),
			),
			array(
				'_type' => 'cell',
				'html' => ' ' . $__templater->escape($__vars['val']['bidding_amount']) . ' ',
			))) . '
  ';
		}
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped($__templater->escape($__vars['auction']['Thread']['title']));
	$__finalCompiled .= '
<style>
.attachmentList > li:first-child{display:none}

</style>
';
	if (($__vars['xf']['visitor']['user_id'] == $__vars['auction']['Thread']['user_id']) OR $__vars['xf']['visitor']['is_admin']) {
		$__compilerTemp1 = '';
		if ($__vars['auction']['Thread']['auction_end_date'] > $__vars['xf']['time']) {
			$__compilerTemp1 .= '
	' . $__templater->button('Bumping', array(
				'href' => $__templater->func('link', array('auction/categories/bumping', $__vars['auction'], ), false),
				'class' => 'button button--icon button--icon--add',
				'icon' => 'add',
			), '', array(
			)) . '
	';
		}
		$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('Delete', array(
			'href' => $__templater->func('link', array('auction/categories/delete', $__vars['auction'], ), false),
			'overlay' => 'true',
			'class' => 'button button--icon button--icon--edit',
			'icon' => 'delete',
		), '', array(
		)) . '
	' . $__templater->button('Edit', array(
			'href' => $__templater->func('link', array(('posts/' . $__vars['auction']['Thread']['FirstPost']['post_id']) . '/edit', ), false),
			'class' => 'button button--icon button--icon--edit',
			'icon' => 'edit',
		), '', array(
		)) . '
	' . $__compilerTemp1 . '
');
	}
	$__finalCompiled .= '

	';
	$__templater->includeCss('message.less');
	$__finalCompiled .= '
<script>

// For All Auctions
function DateTimeConverter(unixdatetime) {

  var wStart_time = new Date(unixdatetime *1000).toLocaleString("en-US", {
    hour12: false,
	 //  timeZone: \'America/Los_Angeles\',
    // timeZone:\'Europe/London\',
    timeStyle: "long",
  });
  var tempHumanDate = new Date(unixdatetime * 1000).toLocaleDateString("en-US", {
	 timeZone: \'America/Los_Angeles\',
	 year: \'numeric\', 
	 month: \'numeric\', 
	 day: \'numeric\',
  });

  var humanDate = new Date(tempHumanDate);
  var year = humanDate.getFullYear();
  var month = (humanDate.getMonth() + 1).toString().padStart(2, "0");
  var date = humanDate.getDate();

  var fulldate = year + "-" + month + "-" + date + " " + wStart_time + ":00";
  // FormatingDateEspecialyForIOS
  var tempCountTimmer = fulldate.split(/[- :]/);
  // Apply each element to the Date function
  var tempDateObject = new Date(
    tempCountTimmer[0],
    tempCountTimmer[1] - 1,
    tempCountTimmer[2],
    tempCountTimmer[3],
    tempCountTimmer[4],
    tempCountTimmer[5]
  );
  var CountDownDateTime = new Date(tempDateObject).getTime();
	//console.log(tempDateObject);
  return CountDownDateTime;
}

function timmerCounter(start_datetime) {
	
  let countDownDate = DateTimeConverter(start_datetime);
    var tempNow = new Date().getTime();
	
	if(countDownDate - tempNow > 0){
		document.getElementById("auction-counter").style.display = "block";
	}

  var counter = setInterval(function () {
    // Get today\'s date and time
    var now = new Date().getTime();
    // Find the distance between now and the count down date
    var timeDistance = countDownDate - now;
    document.getElementById("days-auction").innerHTML =
      Math.floor(timeDistance / (1000 * 60 * 60 * 24)) + " D";
    document.getElementById("hours-auction").innerHTML =
      Math.floor((timeDistance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)) +
      " H";
    document.getElementById("minutes-auction").innerHTML =
      Math.floor((timeDistance % (1000 * 60 * 60)) / (1000 * 60)) + " M";
    document.getElementById("seconds-auction").innerHTML =
      Math.floor((timeDistance % (1000 * 60)) / 1000) + " S";

    // If the count down is over, write some text
    if (timeDistance < 0) {
		document.getElementById("auction-counter").style.display = "none";
      clearInterval(counter);
    }
  }, 1000);
}

</script>

<header class="message-attribution message-attribution--split" style="color: #8c8c8c; font-size: 12px; padding-bottom: 3px; border-bottom: 1px solid #c9c9c9;">
		<ul class="message-attribution-main listInline ' . $__templater->escape($__vars['mainClass']) . '">
			<li class="u-concealed">
				<a href="' . $__templater->func('link', array('auction', $__vars['auction'], array('auction_id' => $__vars['auction']['auction_id'], ), ), true) . '" rel="nofollow">
					' . $__templater->func('date_dynamic', array($__vars['auction']['Thread']['post_date'], array(
		'itemprop' => 'datePublished',
	))) . '
				</a>
			</li>
		</ul>
		<ul class="message-attribution-opposite message-attribution-opposite--list ' . $__templater->escape($__vars['oppositeClass']) . '">

		';
	if ($__templater->method($__vars['auction']['Thread']['FirstPost'], 'isUnread', array())) {
		$__finalCompiled .= '
				<li><span class="message-newIndicator">' . 'New' . '</span></li>
			';
	} else if ($__templater->method($__vars['auction']['Thread'], 'isUnread', array())) {
		$__finalCompiled .= '
				<li><span class="message-newIndicator" title="' . 'New replies' . '">' . 'New' . '</span></li>
			';
	}
	$__finalCompiled .= '
			
			<li>
				<a href="' . ($__templater->func('link', array((('auction/' . $__vars['auction']['category_id']) . '/') . $__vars['auction']['auction_id'], ), true) . '/view-auction') . '"
					class="message-attribution-gadget"
					data-xf-init="share-tooltip"
					data-href="' . $__templater->func('link', array('posts/share', $__vars['auction']['Thread']['FirstPost'], ), true) . '"
					aria-label="' . $__templater->filter('Share', array(array('for_attr', array()),), true) . '"
					rel="nofollow">
					' . $__templater->fontAwesome('fa-share-alt', array(
	)) . '
				</a>
			</li>
			<li>
						' . $__templater->callMacro('bookmark_macros', 'link', array(
		'content' => $__vars['auction']['Thread']['FirstPost'],
		'class' => 'message-attribution-gadget bookmarkLink--highlightable',
		'confirmUrl' => $__templater->func('link', array('posts/bookmark', $__vars['auction']['Thread']['FirstPost'], ), false),
		'showText' => false,
	), $__vars) . '
				</li>
				<li>
					<a href="' . $__templater->func('link', array('auction', $__vars['auction'], array('auction_id' => $__vars['auction']['auction_id'], ), ), true) . '" rel="nofollow">
						#' . $__templater->escape($__vars['auction']['auction_id']) . '
					</a>
				</li>
		</ul>
	</header>


' . $__templater->callMacro(null, 'singleAuction', array(
		'auction' => $__vars['auction'],
	), $__vars) . '


' . '

';
	if ($__vars['auction']['Thread']['auction_end_date'] > $__vars['xf']['time']) {
		$__finalCompiled .= '


	
	<div style="text-align: center;"><div class="bbImageWrapper  js-lbImage" >

			
	<img src="' . ($__templater->func('count', array($__vars['auction']['Thread']['FirstPost']['Attachments'], ), false) ? $__templater->func('link', array('full:attachments', $__templater->method($__vars['auction']['Thread']['FirstPost']['Attachments'], 'first', array()), ), true) : $__templater->func('base_url', array('styles/FS/AuctionPlugin/no_image.png', true, ), true)) . '" alt="' . $__templater->escape($__vars['attachment']['filename']) . '"
					 class="bbImage"

 onload="timmerCounter(' . $__templater->escape($__vars['auction']['Thread']['auction_end_date']) . ')"   class="bbImage" />
		</div>
	</div>
		';
	} else {
		$__finalCompiled .= '
	<div style="text-align: center;"><div class="bbImageWrapper  js-lbImage" >


			<img src="' . ($__templater->func('count', array($__vars['auction']['Thread']['FirstPost']['Attachments'], ), false) ? $__templater->func('link', array('full:attachments', $__templater->method($__vars['auction']['Thread']['FirstPost']['Attachments'], 'first', array()), ), true) : $__templater->func('base_url', array('styles/FS/AuctionPlugin/no_image.png', true, ), true)) . '" alt="' . $__templater->escape($__vars['attachment']['filename']) . '"
					  class="bbImage"

 loading="lazy" />
		</div>
	</div>
		';
	}
	$__finalCompiled .= '
	
	';
	$__templater->includeCss('attachments.less');
	$__finalCompiled .= '
		<section class="message-attachments">
			<h4 class="block-textHeader">' . 'Attachments' . '</h4>
			<ul class="attachmentList">
			
					';
	if ($__templater->isTraversable($__vars['auction']['Thread']['FirstPost']['Attachments'])) {
		foreach ($__vars['auction']['Thread']['FirstPost']['Attachments'] AS $__vars['attachment']) {
			$__finalCompiled .= '
						' . $__templater->callMacro('attachment_macros', 'attachment_list_item', array(
				'attachment' => $__vars['attachment'],
				'canView' => 'true',
			), $__vars) . '
					';
		}
	}
	$__finalCompiled .= '
			
			</ul>
		</section>

	   <div class="block-container">
		<div class="block-body">
				';
	if ($__templater->func('count', array($__vars['bidding'], ), false)) {
		$__finalCompiled .= '
						<h3 class="block-minorHeader"> ' . 'Top Bid :' . '</h3>

				  ' . $__templater->dataList('
					  ' . $__templater->dataRow(array(
			'rowtype' => 'header',
		), array(array(
			'_type' => 'cell',
			'html' => ' ' . 'By User' . ' ',
		),
		array(
			'_type' => 'cell',
			'html' => ' ' . 'Bid At' . ' ',
		),
		array(
			'_type' => 'cell',
			'html' => ' ' . 'Amount' . ' ',
		))) . '
					' . $__templater->dataRow(array(
		), array(array(
			'href' => $__templater->func('link', array('members/', $__vars['bidding'], ), false),
			'_type' => 'cell',
			'html' => ' ' . $__templater->escape($__vars['bidding'][$__vars['highestBidId']]['User']['username']) . ' ',
		),
		array(
			'_type' => 'cell',
			'html' => ' ' . $__templater->func('date_dynamic', array($__vars['bidding'][$__vars['highestBidId']]['created_at'], array(
		))),
		),
		array(
			'_type' => 'cell',
			'html' => ' ' . $__templater->escape($__vars['bidding'][$__vars['highestBidId']]['bidding_amount']) . ' ',
		))) . '
				', array(
			'data-xf-init' => 'responsive-data-list',
			'style' => 'border-bottom:1px solid #dfdfdf;',
		)) . '

			';
	}
	$__finalCompiled .= '
		';
	if (($__vars['auction']['Thread']['auction_end_date'] > $__vars['xf']['time']) AND ($__vars['xf']['visitor']['user_id'] != 0)) {
		$__finalCompiled .= '
			';
		if ($__vars['xf']['visitor']['user_id'] != $__vars['auction']['Thread']['User']['user_id']) {
			$__finalCompiled .= '
				';
			$__vars['bidDropDownRange'] = $__templater->func('range', array(0, $__vars['dropDownListLimit'], ), false);
			$__vars['tempSum'] = ($__vars['bidding'][$__vars['highestBidId']]['bidding_amount'] ? ($__vars['bidding'][$__vars['highestBidId']]['bidding_amount'] + $__vars['auction']['Thread']['custom_fields']['bid_increament']) : ($__vars['auction']['Thread']['custom_fields']['bid_increament'] + $__vars['auction']['Thread']['custom_fields']['starting_bid']));
			$__vars['sum'] = $__vars['tempSum'];
			$__compilerTemp2 = array();
			if ($__templater->isTraversable($__vars['bidDropDownRange'])) {
				foreach ($__vars['bidDropDownRange'] AS $__vars['key'] => $__vars['val']) {
					$__compilerTemp2[] = array(
						'value' => ($__vars['sum'] + $__vars['bidIncrementFromDb']),
						'label' => ($__vars['sum'] + $__vars['bidIncrementFromDb']),
						'_type' => 'option',
					);
					$__vars['sum'] = ($__vars['sum'] + $__vars['auction']['Thread']['custom_fields']['bid_increament']);
				}
			}
			$__finalCompiled .= $__templater->form('
						' . $__templater->formRow('
	
						<!--bidDropDownRange value is  bid dropdown List items count-->
	
							' . '' . '
					
					<div class="inputChoices">
							' . $__templater->formRadio(array(
				'name' => 'use_biddingAmountTyp',
			), array(array(
				'label' => 'Bid from Dropdown',
				'_dependent' => array('
										<!--sum value is  bid increament+bidstart -->
										' . '' . '

										' . '' . '
										
										' . $__templater->formSelect(array(
				'name' => 'bidding_amount',
			), $__compilerTemp2) . '
									'),
				'_type' => 'option',
			),
			array(
				'label' => 'Custom Bid',
				'name' => 'use_biddingAmountTyp',
				'_dependent' => array('
										 ' . $__templater->formNumberBox(array(
				'name' => 'bidding_amount',
				'value' => $__vars['tempSum'],
				'min' => $__vars['tempSum'],
			)) . '
									'),
				'_type' => 'option',
			))) . '
					</div>
		', array(
				'label' => 'Bidding Cost',
			)) . '
			' . $__templater->formSubmitRow(array(
				'icon' => 'save',
				'sticky' => 'true',
			), array(
			)) . '
		', array(
				'action' => $__templater->func('link', array('auction/categories/bidding', $__vars['auction'], ), false),
				'ajax' => 'true',
				'class' => 'block',
				'data-xf-init' => 'attachment-manager',
			)) . '
	';
		} else {
			$__finalCompiled .= '
		<div style="display:flex; justify-content: center; padding:0.7rem;">
				<span >' . 'You can not bid your own Auction' . '</span>	
			</div>
	';
		}
		$__finalCompiled .= '
	
	';
	} else {
		$__finalCompiled .= '
		<div style="display:flex; justify-content: center; padding:0.7rem;">
					<span >' . 'Bidding Not Allowed.' . '</span>	
		</div>
	
	';
	}
	$__finalCompiled .= '
		   </div>
	  </div>



';
	if ($__templater->func('count', array($__vars['bidding'], ), false)) {
		$__finalCompiled .= '
	<div class="block" style="margin-top:1rem;">
	  <div class="block-container">
		<div class="block-body">
		  ' . $__templater->dataList('
			  ' . $__templater->callMacro(null, 'bidding_table_list', array(
			'bidding' => $__vars['bidding'],
		), $__vars) . '
		  ', array(
			'data-xf-init' => 'responsive-data-list',
		)) . '
			
			<div class="block-outer block-outer--after">
		' . $__templater->func('show_ignored', array(array(
			'wrapperclass' => 'block-outer-opposite',
		))) . '
	</div>
		</div>
	  </div>
	</div>
';
	}
	$__finalCompiled .= '


';
	return $__finalCompiled;
}
);
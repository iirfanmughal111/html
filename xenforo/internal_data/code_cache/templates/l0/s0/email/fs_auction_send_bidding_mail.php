<?php
// FROM HASH: fe452e0d7ce11630a2147e6d3b03d0f4
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__vars['auctionLink'] = $__templater->preEscaped($__templater->func('link', array(((('auction/' . $__vars['auction']['category_id']) . '/') . $__vars['auction']['auction_id']) . '/view-auction', ), true));
	$__finalCompiled .= '

<mail:subject>
	' . 'Bid on auction: ' . $__templater->escape($__vars['auction']['title']) . '' . '
</mail:subject>

	' . '<br>
Hello ' . $__templater->escape($__vars['auction']['User']['username']) . ', 
<br><br>
Your auction <a href="' . $__templater->escape($__vars['auctionLink']) . '"> ' . $__templater->escape($__vars['auction']['title']) . '</a> get new bid from user: ' . $__templater->func('username_link', array($__vars['bid_visitor'], false, array('defaultname' => $__vars['bid_visitor']['username'], ), ), true) . '.
<br><br>
Thankyou...!
<br><br>' . '

<table cellpadding="10" cellspacing="0" border="0" width="100%" class="linkBar" >
	<tr>
		<td >
			<a href="' . $__templater->escape($__vars['auctionLink']) . '" class="button">' . 'Visit auction' . '</a>
		</td>
	</tr>
</table>';
	return $__finalCompiled;
}
);
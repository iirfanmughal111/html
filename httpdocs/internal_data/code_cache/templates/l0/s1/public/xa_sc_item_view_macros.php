<?php
// FROM HASH: b91c0be3591b734c80c7fde65964e063
return array(
'macros' => array('business_hours' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'item' => '!',
		'category' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<div class="block">
		<div class="block-container scBusinessHours">
			<h3 class="block-minorHeader">' . 'Business hours' . '</h3>

			<div class="block-body block-row block-row--minor">
				<dl class="pairs pairs--justified">
					<dt>' . 'Monday' . '</dt>
					';
	if ($__vars['item']['business_hours']['open_monday']) {
		$__finalCompiled .= '
						';
		if ($__templater->method($__vars['item'], 'isBusinessOpen24Hours', array('mon', ))) {
			$__finalCompiled .= '
							<dd>' . 'Open 24 hours' . '</dd>
						';
		} else {
			$__finalCompiled .= '
							<dd>' . $__templater->escape($__vars['item']['business_hours']['monday_open_hour']) . ':' . $__templater->escape($__vars['item']['business_hours']['monday_open_minute']) . ' - ' . $__templater->escape($__vars['item']['business_hours']['monday_close_hour']) . ':' . $__templater->escape($__vars['item']['business_hours']['monday_close_minute']) . '</dd>
						';
		}
		$__finalCompiled .= '	
					';
	} else {
		$__finalCompiled .= '
						<dd>' . 'Closed' . '</dd>
					';
	}
	$__finalCompiled .= '
				</dl>
				';
	if ($__vars['item']['business_hours']['open_monday'] AND ((!$__templater->method($__vars['item'], 'isBusinessOpen24Hours', array('mon', ))) AND ($__vars['item']['business_hours']['monday_open_hour_2'] > 0))) {
		$__finalCompiled .= '
					<dl class="pairs pairs--justified additionalHours">
						<dt></dt>
						<dd>' . $__templater->escape($__vars['item']['business_hours']['monday_open_hour_2']) . ':' . $__templater->escape($__vars['item']['business_hours']['monday_open_minute_2']) . ' - ' . $__templater->escape($__vars['item']['business_hours']['monday_close_hour_2']) . ':' . $__templater->escape($__vars['item']['business_hours']['monday_close_minute_2']) . '</dd>
					</dl>
				';
	}
	$__finalCompiled .= '	
				';
	if ($__vars['item']['business_hours']['open_monday'] AND ((!$__templater->method($__vars['item'], 'isBusinessOpen24Hours', array('mon', ))) AND ($__vars['item']['business_hours']['monday_open_hour_3'] > 0))) {
		$__finalCompiled .= '
					<dl class="pairs pairs--justified additionalHours">	
						<dt></dt>
						<dd>' . $__templater->escape($__vars['item']['business_hours']['monday_open_hour_3']) . ':' . $__templater->escape($__vars['item']['business_hours']['monday_open_minute_3']) . ' - ' . $__templater->escape($__vars['item']['business_hours']['monday_close_hour_3']) . ':' . $__templater->escape($__vars['item']['business_hours']['monday_close_minute_3']) . '</dd>
					</dl>
				';
	}
	$__finalCompiled .= '


				<dl class="pairs pairs--justified" style="padding-top: 3px;">
					<dt>' . 'Tuesday' . '</dt>
					';
	if ($__vars['item']['business_hours']['open_tuesday']) {
		$__finalCompiled .= '
						';
		if ($__templater->method($__vars['item'], 'isBusinessOpen24Hours', array('tue', ))) {
			$__finalCompiled .= '
							<dd>' . 'Open 24 hours' . '</dd>
						';
		} else {
			$__finalCompiled .= '	
							<dd>' . $__templater->escape($__vars['item']['business_hours']['tuesday_open_hour']) . ':' . $__templater->escape($__vars['item']['business_hours']['tuesday_open_minute']) . ' - ' . $__templater->escape($__vars['item']['business_hours']['tuesday_close_hour']) . ':' . $__templater->escape($__vars['item']['business_hours']['tuesday_close_minute']) . '</dd>
						';
		}
		$__finalCompiled .= '
					';
	} else {
		$__finalCompiled .= '
						<dd>' . 'Closed' . '</dd>
					';
	}
	$__finalCompiled .= '
				</dl>
				';
	if ($__vars['item']['business_hours']['open_tuesday'] AND ((!$__templater->method($__vars['item'], 'isBusinessOpen24Hours', array('tue', ))) AND ($__vars['item']['business_hours']['tuesday_open_hour_2'] > 0))) {
		$__finalCompiled .= '
					<dl class="pairs pairs--justified additionalHours">
						<dt></dt>
						<dd>' . $__templater->escape($__vars['item']['business_hours']['tuesday_open_hour_2']) . ':' . $__templater->escape($__vars['item']['business_hours']['tuesday_open_minute_2']) . ' - ' . $__templater->escape($__vars['item']['business_hours']['tuesday_close_hour_2']) . ':' . $__templater->escape($__vars['item']['business_hours']['tuesday_close_minute_2']) . '</dd>
					</dl>
				';
	}
	$__finalCompiled .= '	
				';
	if ($__vars['item']['business_hours']['open_tuesday'] AND ((!$__templater->method($__vars['item'], 'isBusinessOpen24Hours', array('tue', ))) AND ($__vars['item']['business_hours']['tuesday_open_hour_3'] > 0))) {
		$__finalCompiled .= '
					<dl class="pairs pairs--justified additionalHours">	
						<dt></dt>
						<dd>' . $__templater->escape($__vars['item']['business_hours']['tuesday_open_hour_3']) . ':' . $__templater->escape($__vars['item']['business_hours']['tuesday_open_minute_3']) . ' - ' . $__templater->escape($__vars['item']['business_hours']['tuesday_close_hour_3']) . ':' . $__templater->escape($__vars['item']['business_hours']['tuesday_close_minute_3']) . '</dd>
					</dl>
				';
	}
	$__finalCompiled .= '


				<dl class="pairs pairs--justified" style="padding-top: 3px;">
					<dt>' . 'Wednesday' . '</dt>
					';
	if ($__vars['item']['business_hours']['open_wednesday']) {
		$__finalCompiled .= '
						';
		if ($__templater->method($__vars['item'], 'isBusinessOpen24Hours', array('wed', ))) {
			$__finalCompiled .= '
							<dd>' . 'Open 24 hours' . '</dd>
						';
		} else {
			$__finalCompiled .= '	
							<dd>' . $__templater->escape($__vars['item']['business_hours']['wednesday_open_hour']) . ':' . $__templater->escape($__vars['item']['business_hours']['wednesday_open_minute']) . ' - ' . $__templater->escape($__vars['item']['business_hours']['wednesday_close_hour']) . ':' . $__templater->escape($__vars['item']['business_hours']['wednesday_close_minute']) . '</dd>
						';
		}
		$__finalCompiled .= '
					';
	} else {
		$__finalCompiled .= '
						<dd>' . 'Closed' . '</dd>
					';
	}
	$__finalCompiled .= '
				</dl>	
				';
	if ($__vars['item']['business_hours']['open_wednesday'] AND ((!$__templater->method($__vars['item'], 'isBusinessOpen24Hours', array('wed', ))) AND ($__vars['item']['business_hours']['wednesday_open_hour_2'] > 0))) {
		$__finalCompiled .= '
					<dl class="pairs pairs--justified additionalHours">
						<dt></dt>
						<dd>' . $__templater->escape($__vars['item']['business_hours']['wednesday_open_hour_2']) . ':' . $__templater->escape($__vars['item']['business_hours']['wednesday_open_minute_2']) . ' - ' . $__templater->escape($__vars['item']['business_hours']['wednesday_close_hour_2']) . ':' . $__templater->escape($__vars['item']['business_hours']['wednesday_close_minute_2']) . '</dd>
					</dl>
				';
	}
	$__finalCompiled .= '	
				';
	if ($__vars['item']['business_hours']['open_wednesday'] AND ((!$__templater->method($__vars['item'], 'isBusinessOpen24Hours', array('wed', ))) AND ($__vars['item']['business_hours']['wednesday_open_hour_3'] > 0))) {
		$__finalCompiled .= '
					<dl class="pairs pairs--justified additionalHours">	
						<dt></dt>
						<dd>' . $__templater->escape($__vars['item']['business_hours']['wednesday_open_hour_3']) . ':' . $__templater->escape($__vars['item']['business_hours']['wednesday_open_minute_3']) . ' - ' . $__templater->escape($__vars['item']['business_hours']['wednesday_close_hour_3']) . ':' . $__templater->escape($__vars['item']['business_hours']['wednesday_close_minute_3']) . '</dd>
					</dl>
				';
	}
	$__finalCompiled .= '


				<dl class="pairs pairs--justified" style="padding-top: 3px;">
					<dt>' . 'Thursday' . '</dt>
					';
	if ($__vars['item']['business_hours']['open_thursday']) {
		$__finalCompiled .= '
						';
		if ($__templater->method($__vars['item'], 'isBusinessOpen24Hours', array('thu', ))) {
			$__finalCompiled .= '
							<dd>' . 'Open 24 hours' . '</dd>
						';
		} else {
			$__finalCompiled .= '	
							<dd>' . $__templater->escape($__vars['item']['business_hours']['thursday_open_hour']) . ':' . $__templater->escape($__vars['item']['business_hours']['thursday_open_minute']) . ' - ' . $__templater->escape($__vars['item']['business_hours']['thursday_close_hour']) . ':' . $__templater->escape($__vars['item']['business_hours']['thursday_close_minute']) . '</dd>
						';
		}
		$__finalCompiled .= '
					';
	} else {
		$__finalCompiled .= '
						<dd>' . 'Closed' . '</dd>
					';
	}
	$__finalCompiled .= '
				</dl>
				';
	if ($__vars['item']['business_hours']['open_thursday'] AND ((!$__templater->method($__vars['item'], 'isBusinessOpen24Hours', array('thu', ))) AND ($__vars['item']['business_hours']['thursday_open_hour_2'] > 0))) {
		$__finalCompiled .= '
					<dl class="pairs pairs--justified additionalHours">
						<dt></dt>
						<dd>' . $__templater->escape($__vars['item']['business_hours']['thursday_open_hour_2']) . ':' . $__templater->escape($__vars['item']['business_hours']['thursday_open_minute_2']) . ' - ' . $__templater->escape($__vars['item']['business_hours']['thursday_close_hour_2']) . ':' . $__templater->escape($__vars['item']['business_hours']['thursday_close_minute_2']) . '</dd>
					</dl>
				';
	}
	$__finalCompiled .= '	
				';
	if ($__vars['item']['business_hours']['open_thursday'] AND ((!$__templater->method($__vars['item'], 'isBusinessOpen24Hours', array('thu', ))) AND ($__vars['item']['business_hours']['thursday_open_hour_3'] > 0))) {
		$__finalCompiled .= '
					<dl class="pairs pairs--justified additionalHours">	
						<dt></dt>
						<dd>' . $__templater->escape($__vars['item']['business_hours']['thursday_open_hour_3']) . ':' . $__templater->escape($__vars['item']['business_hours']['thursday_open_minute_3']) . ' - ' . $__templater->escape($__vars['item']['business_hours']['thursday_close_hour_3']) . ':' . $__templater->escape($__vars['item']['business_hours']['thursday_close_minute_3']) . '</dd>
					</dl>
				';
	}
	$__finalCompiled .= '


				<dl class="pairs pairs--justified" style="padding-top: 3px;">
					<dt>' . 'Friday' . '</dt>
					';
	if ($__vars['item']['business_hours']['open_friday']) {
		$__finalCompiled .= '
						';
		if ($__templater->method($__vars['item'], 'isBusinessOpen24Hours', array('fri', ))) {
			$__finalCompiled .= '
							<dd>' . 'Open 24 hours' . '</dd>
						';
		} else {
			$__finalCompiled .= '	
							<dd>' . $__templater->escape($__vars['item']['business_hours']['friday_open_hour']) . ':' . $__templater->escape($__vars['item']['business_hours']['friday_open_minute']) . ' - ' . $__templater->escape($__vars['item']['business_hours']['friday_close_hour']) . ':' . $__templater->escape($__vars['item']['business_hours']['friday_close_minute']) . '</dd>
						';
		}
		$__finalCompiled .= '
					';
	} else {
		$__finalCompiled .= '
						<dd>' . 'Closed' . '</dd>
					';
	}
	$__finalCompiled .= '
				</dl>
				';
	if ($__vars['item']['business_hours']['open_friday'] AND ((!$__templater->method($__vars['item'], 'isBusinessOpen24Hours', array('fri', ))) AND ($__vars['item']['business_hours']['friday_open_hour_2'] > 0))) {
		$__finalCompiled .= '
					<dl class="pairs pairs--justified additionalHours">
						<dt></dt>
						<dd>' . $__templater->escape($__vars['item']['business_hours']['friday_open_hour_2']) . ':' . $__templater->escape($__vars['item']['business_hours']['friday_open_minute_2']) . ' - ' . $__templater->escape($__vars['item']['business_hours']['friday_close_hour_2']) . ':' . $__templater->escape($__vars['item']['business_hours']['friday_close_minute_2']) . '</dd>
					</dl>
				';
	}
	$__finalCompiled .= '	
				';
	if ($__vars['item']['business_hours']['open_friday'] AND ((!$__templater->method($__vars['item'], 'isBusinessOpen24Hours', array('fri', ))) AND ($__vars['item']['business_hours']['friday_open_hour_3'] > 0))) {
		$__finalCompiled .= '
					<dl class="pairs pairs--justified additionalHours">	
						<dt></dt>
						<dd>' . $__templater->escape($__vars['item']['business_hours']['friday_open_hour_3']) . ':' . $__templater->escape($__vars['item']['business_hours']['friday_open_minute_3']) . ' - ' . $__templater->escape($__vars['item']['business_hours']['friday_close_hour_3']) . ':' . $__templater->escape($__vars['item']['business_hours']['friday_close_minute_3']) . '</dd>
					</dl>
				';
	}
	$__finalCompiled .= '


				<dl class="pairs pairs--justified" style="padding-top: 3px;">
					<dt>' . 'Saturday' . '</dt>
					';
	if ($__vars['item']['business_hours']['open_saturday']) {
		$__finalCompiled .= '	
						';
		if ($__templater->method($__vars['item'], 'isBusinessOpen24Hours', array('sat', ))) {
			$__finalCompiled .= '
							<dd>' . 'Open 24 hours' . '</dd>
						';
		} else {
			$__finalCompiled .= '
							<dd>' . $__templater->escape($__vars['item']['business_hours']['saturday_open_hour']) . ':' . $__templater->escape($__vars['item']['business_hours']['saturday_open_minute']) . ' - ' . $__templater->escape($__vars['item']['business_hours']['saturday_close_hour']) . ':' . $__templater->escape($__vars['item']['business_hours']['saturday_close_minute']) . '</dd>
						';
		}
		$__finalCompiled .= '
					';
	} else {
		$__finalCompiled .= '
						<dd>' . 'Closed' . '</dd>
					';
	}
	$__finalCompiled .= '
				</dl>
				';
	if ($__vars['item']['business_hours']['open_saturday'] AND ((!$__templater->method($__vars['item'], 'isBusinessOpen24Hours', array('sat', ))) AND ($__vars['item']['business_hours']['saturday_open_hour_2'] > 0))) {
		$__finalCompiled .= '
					<dl class="pairs pairs--justified additionalHours">
						<dt></dt>
						<dd>' . $__templater->escape($__vars['item']['business_hours']['saturday_open_hour_2']) . ':' . $__templater->escape($__vars['item']['business_hours']['saturday_open_minute_2']) . ' - ' . $__templater->escape($__vars['item']['business_hours']['saturday_close_hour_2']) . ':' . $__templater->escape($__vars['item']['business_hours']['saturday_close_minute_2']) . '</dd>
					</dl>
				';
	}
	$__finalCompiled .= '	
				';
	if ($__vars['item']['business_hours']['open_saturday'] AND ((!$__templater->method($__vars['item'], 'isBusinessOpen24Hours', array('sat', ))) AND ($__vars['item']['business_hours']['saturday_open_hour_3'] > 0))) {
		$__finalCompiled .= '
					<dl class="pairs pairs--justified additionalHours">	
						<dt></dt>
						<dd>' . $__templater->escape($__vars['item']['business_hours']['saturday_open_hour_3']) . ':' . $__templater->escape($__vars['item']['business_hours']['saturday_open_minute_3']) . ' - ' . $__templater->escape($__vars['item']['business_hours']['saturday_close_hour_3']) . ':' . $__templater->escape($__vars['item']['business_hours']['saturday_close_minute_3']) . '</dd>
					</dl>
				';
	}
	$__finalCompiled .= '

				
				<dl class="pairs pairs--justified" style="padding-top: 3px;">
					<dt>' . 'Sunday' . '</dt>
					';
	if ($__vars['item']['business_hours']['open_sunday']) {
		$__finalCompiled .= '
						';
		if ($__templater->method($__vars['item'], 'isBusinessOpen24Hours', array('sun', ))) {
			$__finalCompiled .= '
							<dd>' . 'Open 24 hours' . '</dd>
						';
		} else {
			$__finalCompiled .= '		
							<dd>' . $__templater->escape($__vars['item']['business_hours']['sunday_open_hour']) . ':' . $__templater->escape($__vars['item']['business_hours']['sunday_open_minute']) . ' - ' . $__templater->escape($__vars['item']['business_hours']['sunday_close_hour']) . ':' . $__templater->escape($__vars['item']['business_hours']['sunday_close_minute']) . '</dd>
						';
		}
		$__finalCompiled .= '
					';
	} else {
		$__finalCompiled .= '
						<dd>' . 'Closed' . '</dd>
					';
	}
	$__finalCompiled .= '
				</dl>
				';
	if ($__vars['item']['business_hours']['open_sunday'] AND ((!$__templater->method($__vars['item'], 'isBusinessOpen24Hours', array('sun', ))) AND ($__vars['item']['business_hours']['sunday_open_hour_2'] > 0))) {
		$__finalCompiled .= '
					<dl class="pairs pairs--justified additionalHours">
						<dt></dt>
						<dd>' . $__templater->escape($__vars['item']['business_hours']['sunday_open_hour_2']) . ':' . $__templater->escape($__vars['item']['business_hours']['sunday_open_minute_2']) . ' - ' . $__templater->escape($__vars['item']['business_hours']['sunday_close_hour_2']) . ':' . $__templater->escape($__vars['item']['business_hours']['sunday_close_minute_2']) . '</dd>
					</dl>
				';
	}
	$__finalCompiled .= '	
				';
	if ($__vars['item']['business_hours']['open_sunday'] AND ((!$__templater->method($__vars['item'], 'isBusinessOpen24Hours', array('sun', ))) AND ($__vars['item']['business_hours']['sunday_open_hour_3'] > 0))) {
		$__finalCompiled .= '
					<dl class="pairs pairs--justified additionalHours">	
						<dt></dt>
						<dd>' . $__templater->escape($__vars['item']['business_hours']['sunday_open_hour_3']) . ':' . $__templater->escape($__vars['item']['business_hours']['sunday_open_minute_3']) . ' - ' . $__templater->escape($__vars['item']['business_hours']['sunday_close_hour_3']) . ':' . $__templater->escape($__vars['item']['business_hours']['sunday_close_minute_3']) . '</dd>
					</dl>
				';
	}
	$__finalCompiled .= '	
			</div>	
		</div>
	</div>
';
	return $__finalCompiled;
}
),
'reviews_images_attachment_list_item' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'attachment' => '!',
		'canView' => '!',
		'hideInBlock' => false,
		'hideInBlockWide' => false,
		'hideInBlockMedium' => false,
		'hideInBlockNarrow' => false,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<li class="file file--linked ' . ($__vars['hideInBlock'] ? 'hideInBlock' : '') . ' ' . ($__vars['hideInBlockWide'] ? 'hideInBlockWide' : '') . ' ' . ($__vars['hideInBlockMedium'] ? 'hideInBlockMedium' : '') . ' ' . ($__vars['hideInBlockNarrow'] ? 'hideInBlockNarrow' : '') . '">
		';
	if ($__vars['attachment']['has_thumbnail']) {
		$__finalCompiled .= '
			' . $__templater->callMacro('lightbox_macros', 'setup', array(
			'canViewAttachments' => $__vars['canView'],
		), $__vars) . '

			<a class="file-preview ' . ($__vars['canView'] ? 'js-lbImage' : '') . '" href="' . $__templater->escape($__vars['attachment']['direct_url']) . '" target="_blank">
				<img src="' . $__templater->escape($__vars['attachment']['thumbnail_url']) . '" alt="' . $__templater->escape($__vars['attachment']['filename']) . '"
					width="' . $__templater->escape($__vars['attachment']['thumbnail_width']) . '" height="' . $__templater->escape($__vars['attachment']['thumbnail_height']) . '" loading="lazy" />
			</a>
		';
	} else if ($__vars['attachment']['is_video']) {
		$__finalCompiled .= '
			<a class="file-preview" href="' . $__templater->escape($__vars['attachment']['direct_url']) . '" target="_blank">
				<video data-xf-init="video-init">
					<source src="' . $__templater->escape($__vars['attachment']['direct_url']) . '" />
				</video>
			</a>
		';
	} else {
		$__finalCompiled .= '
			<a class="file-preview" href="' . $__templater->escape($__vars['attachment']['direct_url']) . '" target="_blank">
				<span class="file-typeIcon">
					' . $__templater->fontAwesome($__templater->escape($__vars['attachment']['icon']), array(
		)) . '
				</span>
			</a>
		';
	}
	$__finalCompiled .= '

		<div class="file-content">
			<div class="file-info">
				<span class="file-name" title="' . $__templater->escape($__vars['attachment']['filename']) . '">' . $__templater->escape($__vars['attachment']['filename']) . '</span>
				<div class="file-meta">
					' . $__templater->filter($__vars['attachment']['file_size'], array(array('file_size', array()),), true) . ' &middot; ' . 'Views' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->filter($__vars['attachment']['view_count'], array(array('number', array()),), true) . '
				</div>
			</div>
		</div>
	</li>
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
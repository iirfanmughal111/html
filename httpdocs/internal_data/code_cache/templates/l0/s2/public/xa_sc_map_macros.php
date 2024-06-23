<?php
// FROM HASH: 34d52701d00c6813c8339305b985b1d9
return array(
'macros' => array('items_map' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'mapItems' => '!',
		'mapId' => '!',
		'containerHeight' => null,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	
	';
	$__templater->includeCss('xa_sc.less');
	$__finalCompiled .= '

	<script src="https://cdnjs.cloudflare.com/ajax/libs/OverlappingMarkerSpiderfier/1.0.3/oms.min.js"></script>			
	';
	if ($__vars['xf']['options']['xaScMarkerClustering']) {
		$__finalCompiled .= '
		<script src="https://unpkg.com/@googlemaps/markerclustererplus/dist/index.min.js"></script>
	';
	}
	$__finalCompiled .= '
	
	<div id="map-' . $__templater->escape($__vars['mapId']) . '" style="height:' . ($__templater->escape($__vars['containerHeight']) ?: 400) . 'px; width:100%;"></div>

	<script type="text/javascript">
		function initMap(){
			var options = {
				zoom: 2,
				center: {lat:42.877742, lng:-97.380979}
			};

			var map = new google.maps.Map(document.getElementById(\'map-' . $__templater->escape($__vars['mapId']) . '\'), options);

			';
	if (!$__vars['xf']['options']['xaScShowPointsOfInterest']) {
		$__finalCompiled .= '
				var noPoi = [
				{
					featureType: "poi",
					stylers: [
				  		{ visibility: "off" }
					]  
				}
				];

				map.setOptions({styles: noPoi});
			';
	}
	$__finalCompiled .= '

			var data = [
				';
	$__vars['i'] = 0;
	if ($__templater->isTraversable($__vars['mapItems'])) {
		foreach ($__vars['mapItems'] AS $__vars['mapItem']) {
			$__vars['i']++;
			$__finalCompiled .= '
					';
			if ($__vars['mapItem']['location_data'] AND ($__vars['mapItem']['location_data']['latitude'] AND $__vars['mapItem']['location_data']['longitude'])) {
				$__finalCompiled .= '	
						{
							coords:{lat:' . $__templater->escape($__vars['mapItem']['location_data']['latitude']) . ',lng:' . $__templater->escape($__vars['mapItem']['location_data']['longitude']) . '},
							';
				if ($__vars['mapItem']['Featured']) {
					$__finalCompiled .= '
								';
					if ($__vars['mapItem']['Category']['map_options']['custom_featured_map_marker_url']) {
						$__finalCompiled .= '	
									iconUrl:{url: "' . $__templater->func('base_url', array($__vars['mapItem']['Category']['map_options']['custom_featured_map_marker_url'], ), true) . '"},
								';
					} else {
						$__finalCompiled .= '
									iconUrl:{url: "' . $__templater->func('base_url', array($__vars['xf']['options']['xaScDefaultFeaturedMapMarkerIconUrl'], ), true) . '"},
								';
					}
					$__finalCompiled .= '
								title: \'' . 'Featured' . '\',
							';
				} else {
					$__finalCompiled .= '
								';
					if ($__vars['mapItem']['Category']['map_options']['custom_map_marker_url']) {
						$__finalCompiled .= '	
									iconUrl:{url: "' . $__templater->func('base_url', array($__vars['mapItem']['Category']['map_options']['custom_map_marker_url'], ), true) . '"},
								';
					} else {
						$__finalCompiled .= '
									iconUrl:{url: "' . $__templater->func('base_url', array($__vars['xf']['options']['xaScDefaultMapMarkerIconUrl'], ), true) . '"},
								';
					}
					$__finalCompiled .= '
								title: \'\',
							';
				}
				$__finalCompiled .= '	
							content:\'<div class="scMapInfoWindow">\'+
								\'<div class="scItem scMapInfoWindowItem">\'+
								';
				if ($__vars['mapItem']['CoverImage'] AND $__vars['mapItem']['CoverImage']['thumbnail_url']) {
					$__finalCompiled .= '
									\'<div class="listBlock itemCoverImage left">\'+
									\'<div class="listBlockInnerImage"><a href="' . $__templater->filter($__templater->func('link', array('showcase', $__vars['mapItem'], ), false), array(array('escape', array('js', )),), true) . '"> <img src="' . $__templater->escape($__vars['mapItem']['CoverImage']['thumbnail_url']) . '" style="max-width:100px;" class="thumbImage" /> </a></div>\'+
									\'</div>\'+
								';
				}
				$__finalCompiled .= '
								\'<div class="title"><a href="' . $__templater->filter($__templater->func('link', array('showcase', $__vars['mapItem'], ), false), array(array('escape', array('js', )),), true) . '" title="">' . $__templater->filter($__vars['mapItem']['title'], array(array('escape', array('js', )),), true) . '</a></div>\'+
								\'<div class="address sc-muted">' . $__templater->escape($__vars['mapItem']['location_data']['formatted_address']) . '</div>\'+
								';
				if ($__vars['mapItem']['author_rating']) {
					$__finalCompiled .= '
									';
					if ($__vars['mapItem']['author_rating'] >= 5) {
						$__finalCompiled .= '
										\'<div class="authorRating"><span style="color: #176093"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></span> <span class="sc-muted">' . 'Author rating' . '</span></div>\'+
									';
					} else if ($__vars['mapItem']['author_rating'] >= 4.5) {
						$__finalCompiled .= '
										\'<div class="authorRating"><span style="color: #176093"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i></span> <span class="sc-muted">' . 'Author rating' . '</span></div>\'+
									';
					} else if ($__vars['mapItem']['author_rating'] >= 4) {
						$__finalCompiled .= '
										\'<div class="authorRating"><span style="color: #176093"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i></span> <span class="sc-muted">' . 'Author rating' . '</span></div>\'+
									';
					} else if ($__vars['mapItem']['author_rating'] >= 3.5) {
						$__finalCompiled .= '
										\'<div class="authorRating"><span style="color: #176093"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i><i class="far fa-star"></i></span> <span class="sc-muted">' . 'Author rating' . '</span></div>\'+
									';
					} else if ($__vars['mapItem']['author_rating'] >= 3) {
						$__finalCompiled .= '
										\'<div class="authorRating"><span style="color: #176093"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i></span> <span class="sc-muted">' . 'Author rating' . '</span></div>\'+
									';
					} else if ($__vars['mapItem']['author_rating'] >= 2.5) {
						$__finalCompiled .= '
										\'<div class="authorRating"><span style="color: #176093"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i><i class="far fa-star"></i><i class="far fa-star"></i></span> <span class="sc-muted">' . 'Author rating' . '</span></div>\'+
									';
					} else if ($__vars['mapItem']['author_rating'] >= 2) {
						$__finalCompiled .= '
										\'<div class="authorRating"><span style="color: #176093"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i></span> <span class="sc-muted">' . 'Author rating' . '</span></div>\'+
									';
					} else if ($__vars['mapItem']['author_rating'] >= 1.5) {
						$__finalCompiled .= '
										\'<div class="authorRating"><span style="color: #176093"><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i></span> <span class="sc-muted">' . 'Author rating' . '</span></div>\'+
									';
					} else if ($__vars['mapItem']['author_rating'] >= 1) {
						$__finalCompiled .= '
										\'<div class="authorRating"><span style="color: #176093"><i class="fas fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i></span> <span class="sc-muted">' . 'Author rating' . '</span></div>\'+
									';
					}
					$__finalCompiled .= '
								';
				}
				$__finalCompiled .= '	
								';
				if ($__vars['mapItem']['rating_avg'] AND $__vars['mapItem']['review_count']) {
					$__finalCompiled .= '
									';
					if ($__vars['mapItem']['rating_avg'] >= '5') {
						$__finalCompiled .= '
										\'<div class="userRating"><span style="color: #f9c479"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></span> <a href="' . $__templater->filter($__templater->func('link', array('showcase/reviews', $__vars['mapItem'], ), false), array(array('escape', array('js', )),), true) . '">' . $__templater->filter($__vars['mapItem']['rating_avg'], array(array('number', array(2, )),), true) . ' - ' . $__templater->filter($__vars['mapItem']['review_count'], array(array('number', array()),), true) . ' ' . 'Reviews' . '</a> </div>\'+
									';
					} else if ($__vars['mapItem']['rating_avg'] >= 4.5) {
						$__finalCompiled .= '
										\'<div class="userRating"><span style="color: #f9c479"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i></span> <a href="' . $__templater->filter($__templater->func('link', array('showcase/reviews', $__vars['mapItem'], ), false), array(array('escape', array('js', )),), true) . '">' . $__templater->filter($__vars['mapItem']['rating_avg'], array(array('number', array(2, )),), true) . ' - ' . $__templater->filter($__vars['mapItem']['review_count'], array(array('number', array()),), true) . ' ' . 'Reviews' . '</a> </div>\'+
									';
					} else if ($__vars['mapItem']['rating_avg'] >= 4) {
						$__finalCompiled .= '
										\'<div class="userRating"><span style="color: #f9c479"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i></span> <a href="' . $__templater->filter($__templater->func('link', array('showcase/reviews', $__vars['mapItem'], ), false), array(array('escape', array('js', )),), true) . '">' . $__templater->filter($__vars['mapItem']['rating_avg'], array(array('number', array(2, )),), true) . ' - ' . $__templater->filter($__vars['mapItem']['review_count'], array(array('number', array()),), true) . ' ' . 'Reviews' . '</a> </div>\'+
									';
					} else if ($__vars['mapItem']['rating_avg'] >= 3.5) {
						$__finalCompiled .= '
										\'<div class="userRating"><span style="color: #f9c479"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i><i class="far fa-star"></i></span> <a href="' . $__templater->filter($__templater->func('link', array('showcase/reviews', $__vars['mapItem'], ), false), array(array('escape', array('js', )),), true) . '">' . $__templater->filter($__vars['mapItem']['rating_avg'], array(array('number', array(2, )),), true) . ' - ' . $__templater->filter($__vars['mapItem']['review_count'], array(array('number', array()),), true) . ' ' . 'Reviews' . '</a> </div>\'+
									';
					} else if ($__vars['mapItem']['rating_avg'] >= 3) {
						$__finalCompiled .= '
										\'<div class="userRating"><span style="color: #f9c479"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i></span> <a href="' . $__templater->filter($__templater->func('link', array('showcase/reviews', $__vars['mapItem'], ), false), array(array('escape', array('js', )),), true) . '">' . $__templater->filter($__vars['mapItem']['rating_avg'], array(array('number', array(2, )),), true) . ' - ' . $__templater->filter($__vars['mapItem']['review_count'], array(array('number', array()),), true) . ' ' . 'Reviews' . '</a> </div>\'+
									';
					} else if ($__vars['mapItem']['rating_avg'] >= 2.5) {
						$__finalCompiled .= '
										\'<div class="userRating"><span style="color: #f9c479"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i><i class="far fa-star"></i><i class="far fa-star"></i></span> <a href="' . $__templater->filter($__templater->func('link', array('showcase/reviews', $__vars['mapItem'], ), false), array(array('escape', array('js', )),), true) . '">' . $__templater->filter($__vars['mapItem']['rating_avg'], array(array('number', array(2, )),), true) . ' - ' . $__templater->filter($__vars['mapItem']['review_count'], array(array('number', array()),), true) . ' ' . 'Reviews' . '</a> </div>\'+
									';
					} else if ($__vars['mapItem']['rating_avg'] >= 2) {
						$__finalCompiled .= '
										\'<div class="userRating"><span style="color: #f9c479"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i></span> <a href="' . $__templater->filter($__templater->func('link', array('showcase/reviews', $__vars['mapItem'], ), false), array(array('escape', array('js', )),), true) . '">' . $__templater->filter($__vars['mapItem']['rating_avg'], array(array('number', array(2, )),), true) . ' - ' . $__templater->filter($__vars['mapItem']['review_count'], array(array('number', array()),), true) . ' ' . 'Reviews' . '</a> </div>\'+
									';
					} else if ($__vars['mapItem']['rating_avg'] >= 1.5) {
						$__finalCompiled .= '
										\'<div class="userRating"><span style="color: #f9c479"><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i></span> <a href="' . $__templater->filter($__templater->func('link', array('showcase/reviews', $__vars['mapItem'], ), false), array(array('escape', array('js', )),), true) . '">' . $__templater->filter($__vars['mapItem']['rating_avg'], array(array('number', array(2, )),), true) . ' - ' . $__templater->filter($__vars['mapItem']['review_count'], array(array('number', array()),), true) . ' ' . 'Reviews' . '</a> </div>\'+
									';
					} else if ($__vars['mapItem']['rating_avg'] >= 1) {
						$__finalCompiled .= '
										\'<div class="userRating"><span style="color: #f9c479"><i class="fas fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i></span> <a href="' . $__templater->filter($__templater->func('link', array('showcase/reviews', $__vars['mapItem'], ), false), array(array('escape', array('js', )),), true) . '">' . $__templater->filter($__vars['mapItem']['rating_avg'], array(array('number', array(2, )),), true) . ' - ' . $__templater->filter($__vars['mapItem']['review_count'], array(array('number', array()),), true) . ' ' . 'Reviews' . '</a> </div>\'+
									';
					}
					$__finalCompiled .= '
								';
				}
				$__finalCompiled .= '
								\'</div>\'+	
								\'</div>\'	
						},
					';
			}
			$__finalCompiled .= ' 
				';
		}
	}
	$__finalCompiled .= '
			];

			var markers = [];

			// Initialize the spiderfier library
			var oms = new OverlappingMarkerSpiderfier(map, {markersWontMove: true, markersWontHide: true, keepSpiderfied: true});
		
			var bounds = new google.maps.LatLngBounds();

			var infoWindow = new google.maps.InfoWindow({
				maxWidth: 450
			});

			for(var i = 0;i < data.length;i++){
				addMarker(data[i]);
			}

			function addMarker(props){
				var marker = new google.maps.Marker({
					position: props.coords,
					map: map,
					icon: props.iconUrl,
					title: props.title,
				});

				// add the marker to the spiderfier _before_ adding it to the map
				oms.addMarker(marker);
				
				var loc = new google.maps.LatLng(props.coords);
				bounds.extend(loc);

				(function (marker, props) {
					google.maps.event.addListener(marker, "click", function (e) {
						map.panTo(marker.getPosition())
						infoWindow.setContent(props.content);
						infoWindow.open(map, marker);
					});
				})(marker, props);

				markers.push(marker);
			};

			';
	if ($__vars['xf']['options']['xaScMarkerClustering']) {
		$__finalCompiled .= '
				new MarkerClusterer(map, markers, {
					imagePath:
					"' . $__templater->func('base_url', array('data/assets/xa_sc_map_markers/m', ), true) . '",
					// Prevent clustering in zoom levels
					// lower than 14 so spiderfier can work
                    maxZoom: 14,				
				});
			';
	}
	$__finalCompiled .= '

			if (bounds.getNorthEast().equals(bounds.getSouthWest())) {
				var extendPoint1 = new google.maps.LatLng(bounds.getNorthEast().lat() + 0.01, bounds.getNorthEast().lng() + 0.01);
				var extendPoint2 = new google.maps.LatLng(bounds.getNorthEast().lat() - 0.01, bounds.getNorthEast().lng() - 0.01);
				bounds.extend(extendPoint1);
				bounds.extend(extendPoint2);
			}

			map.fitBounds(bounds);       
			map.panToBounds(bounds); 
		}
	</script>

	<script async defer
		src="https://maps.googleapis.com/maps/api/js?key=' . $__templater->escape($__vars['xf']['options']['xaScGoogleMapsJavaScriptApiKey']) . '&callback=initMap' . ($__vars['xf']['options']['xaScLocalizeGoogleMaps'] ? ('&language=' . $__templater->filter($__vars['xf']['language']['language_code'], array(array('substr', array()),), true)) : '') . '&libraries=">
	</script>
	
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';

	return $__finalCompiled;
}
);
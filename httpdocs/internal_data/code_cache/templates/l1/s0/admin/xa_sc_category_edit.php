<?php
// FROM HASH: 38eee60d8413f6ce6ca11ce41b7c1958
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__templater->method($__vars['category'], 'isInsert', array())) {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Add category');
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit category' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['category']['title']));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	if ($__templater->method($__vars['category'], 'isUpdate', array())) {
		$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('', array(
			'href' => $__templater->func('link', array('xa-sc/categories/delete', $__vars['category'], ), false),
			'icon' => 'delete',
			'overlay' => 'true',
		), '', array(
		)) . '
');
	}
	$__finalCompiled .= '

';
	$__compilerTemp1 = array(array(
		'value' => '0',
		'label' => $__vars['xf']['language']['parenthesis_open'] . 'None' . $__vars['xf']['language']['parenthesis_close'],
		'_type' => 'option',
	));
	if ($__templater->isTraversable($__vars['forumOptions'])) {
		foreach ($__vars['forumOptions'] AS $__vars['forum']) {
			$__compilerTemp1[] = array(
				'value' => $__vars['forum']['value'],
				'disabled' => $__vars['forum']['disabled'],
				'label' => $__templater->escape($__vars['forum']['label']),
				'_type' => 'option',
			);
		}
	}
	$__templater->includeJs(array(
		'src' => 'xf/prefix_menu.js',
		'min' => '1',
	));
	$__compilerTemp2 = array();
	$__compilerTemp3 = $__templater->method($__vars['styleTree'], 'getFlattened', array(0, ));
	if ($__templater->isTraversable($__compilerTemp3)) {
		foreach ($__compilerTemp3 AS $__vars['treeEntry']) {
			$__compilerTemp2[] = array(
				'value' => $__vars['treeEntry']['record']['style_id'],
				'label' => $__templater->func('repeat', array('--', $__vars['treeEntry']['depth'], ), true) . ' ' . $__templater->escape($__vars['treeEntry']['record']['title']),
				'_type' => 'option',
			);
		}
	}
	$__compilerTemp4 = '';
	if (!$__templater->test($__vars['availableFields'], 'empty', array())) {
		$__compilerTemp4 .= '
				<hr class="formRowSep" />

				';
		$__compilerTemp5 = $__templater->mergeChoiceOptions(array(), $__vars['availableFields']);
		$__compilerTemp4 .= $__templater->formCheckBoxRow(array(
			'name' => 'available_fields',
			'value' => $__vars['category']['field_cache'],
			'listclass' => 'field listColumns',
		), $__compilerTemp5, array(
			'label' => 'Available fields',
			'hint' => '
						' . $__templater->formCheckBox(array(
			'standalone' => 'true',
		), array(array(
			'check-all' => '.field.listColumns',
			'label' => 'Select all',
			'_type' => 'option',
		))) . '
					',
		)) . '
			';
	} else {
		$__compilerTemp4 .= '
				<hr class="formRowSep" />

				' . $__templater->formRow('
					' . $__templater->filter('None', array(array('parens', array()),), true) . ' <a href="' . $__templater->func('link', array('xa-sc/item-fields', ), true) . '" target="_blank">' . 'Add field' . '</a>
				', array(
			'label' => 'Available fields',
		)) . '
			';
	}
	$__compilerTemp6 = '';
	if (!$__templater->test($__vars['availableUpdateFields'], 'empty', array())) {
		$__compilerTemp6 .= '
				<hr class="formRowSep" />

				';
		$__compilerTemp7 = $__templater->mergeChoiceOptions(array(), $__vars['availableUpdateFields']);
		$__compilerTemp6 .= $__templater->formCheckBoxRow(array(
			'name' => 'available_update_fields',
			'value' => $__vars['category']['update_field_cache'],
			'listclass' => 'field listColumns',
		), $__compilerTemp7, array(
			'label' => 'Available fields',
			'hint' => '
						' . $__templater->formCheckBox(array(
			'standalone' => 'true',
		), array(array(
			'check-all' => '.field.listColumns',
			'label' => 'Select all',
			'_type' => 'option',
		))) . '
					',
		)) . '
			';
	} else {
		$__compilerTemp6 .= '
				<hr class="formRowSep" />

				' . $__templater->formRow('
					' . $__templater->filter('None', array(array('parens', array()),), true) . ' <a href="' . $__templater->func('link', array('xa-sc/update-fields', ), true) . '" target="_blank">' . 'Add field' . '</a>
				', array(
			'label' => 'Available fields',
		)) . '
			';
	}
	$__compilerTemp8 = '';
	if (!$__templater->test($__vars['availableReviewFields'], 'empty', array())) {
		$__compilerTemp8 .= '
				<hr class="formRowSep" />

				';
		$__compilerTemp9 = $__templater->mergeChoiceOptions(array(), $__vars['availableReviewFields']);
		$__compilerTemp8 .= $__templater->formCheckBoxRow(array(
			'name' => 'available_review_fields',
			'value' => $__vars['category']['review_field_cache'],
			'listclass' => 'field listColumns',
		), $__compilerTemp9, array(
			'label' => 'Available fields',
			'hint' => '
						' . $__templater->formCheckBox(array(
			'standalone' => 'true',
		), array(array(
			'check-all' => '.field.listColumns',
			'label' => 'Select all',
			'_type' => 'option',
		))) . '
					',
		)) . '
			';
	} else {
		$__compilerTemp8 .= '
				<hr class="formRowSep" />

				' . $__templater->formRow('
					' . $__templater->filter('None', array(array('parens', array()),), true) . ' <a href="' . $__templater->func('link', array('xa-sc/review-fields', ), true) . '" target="_blank">' . 'Add field' . '</a>
				', array(
			'label' => 'Available fields',
		)) . '
			';
	}
	$__compilerTemp10 = '';
	if (!$__templater->test($__vars['availablePrefixes'], 'empty', array())) {
		$__compilerTemp10 .= '
				<hr class="formRowSep" />

				';
		$__compilerTemp11 = $__templater->mergeChoiceOptions(array(), $__vars['availablePrefixes']);
		$__compilerTemp10 .= $__templater->formCheckBoxRow(array(
			'name' => 'available_prefixes',
			'value' => $__vars['category']['prefix_cache'],
			'listclass' => 'prefix listColumns',
		), $__compilerTemp11, array(
			'label' => 'Available prefixes',
			'hint' => '
						' . $__templater->formCheckBox(array(
			'standalone' => 'true',
		), array(array(
			'check-all' => '.prefix.listColumns',
			'label' => 'Select all',
			'_type' => 'option',
		))) . '
					',
		)) . '

				';
		$__compilerTemp12 = array(array(
			'value' => '-1',
			'label' => 'None',
			'_type' => 'option',
		));
		if ($__templater->isTraversable($__vars['prefixGroups'])) {
			foreach ($__vars['prefixGroups'] AS $__vars['prefixGroupId'] => $__vars['prefixGroup']) {
				if (($__templater->func('count', array($__vars['prefixesGrouped'][$__vars['prefixGroupId']], ), false) > 0)) {
					$__compilerTemp12[] = array(
						'label' => (($__vars['prefixGroupId'] > 0) ? $__vars['prefixGroup']['title'] : 'Ungrouped'),
						'_type' => 'optgroup',
						'options' => array(),
					);
					end($__compilerTemp12); $__compilerTemp13 = key($__compilerTemp12);
					if ($__templater->isTraversable($__vars['prefixesGrouped'][$__vars['prefixGroupId']])) {
						foreach ($__vars['prefixesGrouped'][$__vars['prefixGroupId']] AS $__vars['prefixId'] => $__vars['prefix']) {
							$__compilerTemp12[$__compilerTemp13]['options'][] = array(
								'value' => $__vars['prefixId'],
								'disabled' => (!$__templater->func('in_array', array($__vars['prefixId'], $__vars['category']['prefix_cache'], ), false)),
								'label' => $__templater->escape($__vars['prefix']['title']),
								'_type' => 'option',
							);
						}
					}
				}
			}
		}
		$__compilerTemp10 .= $__templater->formSelectRow(array(
			'name' => 'default_prefix_id',
			'value' => $__vars['category']['default_prefix_id'],
		), $__compilerTemp12, array(
			'label' => 'Default item prefix',
			'explain' => 'You may specify an item prefix to be automatically selected when visitors create new items in this category. The selected prefix must also be selected in the \'Available prefixes\' list above.',
		)) . '

				' . $__templater->formCheckBoxRow(array(
			'name' => 'require_prefix',
			'value' => $__vars['category']['require_prefix'],
		), array(array(
			'value' => '1',
			'label' => 'Require users to select a prefix',
			'hint' => 'If selected, users will be required to select a prefix when creating or editing an item. This will not be enforced for moderators.',
			'_type' => 'option',
		)), array(
		)) . '
			';
	} else {
		$__compilerTemp10 .= '

				<hr class="formRowSep" />

				' . $__templater->formRow('
					' . $__templater->filter('None', array(array('parens', array()),), true) . ' <a href="' . $__templater->func('link', array('xa-sc/prefixes', ), true) . '" target="_blank">' . 'Add prefix' . '</a>
				', array(
			'label' => 'Available prefixes',
		)) . '

			';
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formTextBoxRow(array(
		'name' => 'title',
		'value' => $__vars['category']['title'],
	), array(
		'label' => 'Title',
	)) . '

			' . $__templater->formTextBoxRow(array(
		'name' => 'og_title',
		'value' => $__vars['category']['og_title'],
		'maxlength' => $__templater->func('max_length', array($__vars['category'], 'og_title', ), false),
	), array(
		'label' => 'OG title',
		'hint' => 'Optional',
		'explain' => 'The Open Graph / Twitter title used on social media (Facebook, Twitter etc).',
	)) . '

			' . $__templater->formTextBoxRow(array(
		'name' => 'meta_title',
		'value' => $__vars['category']['meta_title'],
		'maxlength' => $__templater->func('max_length', array($__vars['category'], 'meta_title', ), false),
	), array(
		'label' => 'Meta title',
		'hint' => 'Optional',
		'explain' => 'The title used in the title tag. A meta title, also known as a title tag, refers to the text that is displayed on search engine result pages and browser tabs to indicate the topic of a webpage. ',
	)) . '

			' . $__templater->formTextAreaRow(array(
		'name' => 'description',
		'value' => $__vars['category']['description'],
		'autosize' => 'true',
	), array(
		'label' => 'Description',
		'explain' => 'You may use HTML',
	)) . '

			' . $__templater->formTextAreaRow(array(
		'name' => 'meta_description',
		'value' => $__vars['category']['meta_description_'],
		'maxlength' => $__templater->func('max_length', array($__vars['category'], 'meta_description', ), false),
	), array(
		'label' => 'Meta description',
		'hint' => 'Optional',
		'explain' => 'Provide a brief summary of your category for search engines.
<br><br>
A meta description can influence the decision of the searcher as to whether they want to click through on your category from search results or not. The more descriptive, attractive and relevant the description, the more likely someone will click through.',
	)) . '

			' . $__templater->formTextBoxRow(array(
		'name' => 'content_term',
		'value' => $__vars['category']['content_term'],
	), array(
		'label' => 'Category content term',
		'explain' => 'The type of content being reviewed for this category, eg Product, Vehicle, Movie, Game, Guide, How to etc etc etc. 

<b>Note</b>: If you leave this blank, the generic term "Item" will be used.',
	)) . '
			
			' . $__templater->callMacro('category_tree_macros', 'parent_category_select_row', array(
		'category' => $__vars['category'],
		'categoryTree' => $__vars['categoryTree'],
		'idKey' => 'category_id',
	), $__vars) . '

			' . $__templater->callMacro('display_order_macros', 'row', array(
		'value' => $__vars['category']['display_order'],
	), $__vars) . '

			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'display_items_on_index',
		'selected' => $__vars['category']['display_items_on_index'],
		'label' => 'Display items on the showcase index page',
		'hint' => 'When enabled, items from this category will be displayed on the Showcase Index Page (if viewable by the viewing user). 
<br><br>
<b>Note</b>: When disabled, items from this category will still be displayed on Category Pages and other various Showcase pages, Widgets etc that display items.',
		'_type' => 'option',
	)), array(
	)) . '			
			
			<hr class="formRowSep" />

			' . $__templater->formRadioRow(array(
		'name' => 'allow_index',
		'value' => $__vars['category']['allow_index'],
	), array(array(
		'value' => 'allow',
		'label' => 'Index all items',
		'_type' => 'option',
	),
	array(
		'value' => 'deny',
		'label' => 'Do not index any items',
		'_type' => 'option',
	),
	array(
		'value' => 'criteria',
		'data-xf-init' => 'disabler',
		'data-container' => '.js-indexCriteria',
		'data-hide' => 'true',
		'label' => '

					' . 'Index items based on criteria' . '
				',
		'_type' => 'option',
	)), array(
		'label' => 'Allow search engine indexing',
		'explain' => 'You can choose whether or not to allow external search engines to index all items in this category, or only items meeting certain criteria.',
	)) . '

			<div class="js-indexCriteria">
				' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'index_criteria[max_days_create][enabled]',
		'selected' => $__vars['category']['index_criteria']['max_days_create'],
		'label' => 'Item was created no more than X days ago' . $__vars['xf']['language']['label_separator'],
		'_dependent' => array($__templater->formNumberBox(array(
		'name' => 'index_criteria[max_days_create][value]',
		'value' => ($__vars['category']['index_criteria']['max_days_create'] ?: 1),
		'min' => '1',
	))),
		'_type' => 'option',
	),
	array(
		'name' => 'index_criteria[max_days_last_update][enabled]',
		'selected' => $__vars['category']['index_criteria']['max_days_last_update'],
		'label' => 'Item was updated no more than X days ago' . $__vars['xf']['language']['label_separator'],
		'_dependent' => array($__templater->formNumberBox(array(
		'name' => 'index_criteria[max_days_last_update][value]',
		'value' => ($__vars['category']['index_criteria']['max_days_last_update'] ?: 1),
		'min' => '1',
	))),
		'_type' => 'option',
	),
	array(
		'name' => 'index_criteria[min_views][enabled]',
		'selected' => ($__vars['category']['index_criteria']['min_views'] !== null),
		'label' => 'Item has at least X views' . $__vars['xf']['language']['label_separator'],
		'_dependent' => array($__templater->formNumberBox(array(
		'name' => 'index_criteria[min_views][value]',
		'value' => ($__vars['category']['index_criteria']['min_views'] ?: 0),
	))),
		'_type' => 'option',
	),
	array(
		'name' => 'index_criteria[min_reviews][enabled]',
		'selected' => ($__vars['category']['index_criteria']['min_reviews'] !== null),
		'label' => 'Item has at least X reviews' . $__vars['xf']['language']['label_separator'],
		'_dependent' => array($__templater->formNumberBox(array(
		'name' => 'index_criteria[min_reviews][value]',
		'value' => ($__vars['category']['index_criteria']['min_reviews'] ?: 0),
	))),
		'_type' => 'option',
	),
	array(
		'name' => 'index_criteria[min_rating_avg][enabled]',
		'selected' => ($__vars['category']['index_criteria']['min_rating_avg'] !== null),
		'label' => 'Item has at least X rating average' . $__vars['xf']['language']['label_separator'],
		'_dependent' => array($__templater->formNumberBox(array(
		'name' => 'index_criteria[min_rating_avg][value]',
		'value' => ($__vars['category']['index_criteria']['min_rating_avg'] ?: 0),
	))),
		'_type' => 'option',
	),
	array(
		'name' => 'index_criteria[min_reaction_score][enabled]',
		'selected' => ($__vars['category']['index_criteria']['min_reaction_score'] !== null),
		'label' => 'Item has at least X reaction score' . $__vars['xf']['language']['label_separator'],
		'_dependent' => array($__templater->formNumberBox(array(
		'name' => 'index_criteria[min_reaction_score][value]',
		'value' => ($__vars['category']['index_criteria']['min_reaction_score'] ?: 0),
	))),
		'_type' => 'option',
	)), array(
		'label' => 'Search engine index criteria',
	)) . '
			</div>

			<hr class="formRowSep" />

			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'expand_category_nav',
		'selected' => $__vars['category']['expand_category_nav'],
		'label' => 'Expand category navigation',
		'hint' => 'When enabled, this category will be expanded on the Category Navigation Block (<b>one sub level only</b>).  
<br><br>
<b>Note:</b> If you want the entire category tree for this specific category to be exapanded, you will need to edit each sub category in the trees and set this option. ',
		'_type' => 'option',
	)), array(
	)) . '

			<hr class="formRowSep" />
			
			' . $__templater->formNumberBoxRow(array(
		'name' => 'min_tags',
		'value' => $__vars['category']['min_tags'],
		'min' => '0',
		'max' => '100',
	), array(
		'label' => 'Minimum required tags',
		'explain' => 'This allows you to require users to enter at least this many tags when adding items or editing tags on existing items.',
	)) . '

			' . $__templater->formTokenInputRow(array(
		'name' => 'default_tags',
		'value' => $__vars['category']['default_tags'],
		'href' => $__templater->func('link_type', array('public', 'misc/tag-auto-complete', ), false),
		'min-length' => $__vars['xf']['options']['tagLength']['min'],
		'max-length' => $__vars['xf']['options']['tagLength']['max'],
		'max-tokens' => $__vars['xf']['options']['maxContentTags'],
	), array(
		'label' => 'Default tags',
		'explain' => '
					' . 'This allows you to preset one or more tags on the add item form for item created within this category.  <b>Note</b>:  These tags are not force added, the member creating the item can remove then prior to creating the new item. ' . '
					<br><br>
					' . 'Multiple tags may be separated by commas.' . '
				',
	)) . '
			
			<hr class="formRowSep" />
			
			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'allow_items',
		'selected' => $__vars['category']['allow_items'],
		'label' => 'Allow items to be created',
		'hint' => 'This option allows items to be created in this category. Disabling this option does not prevent existing items from being moved into this category.',
		'_type' => 'option',
	)), array(
		'label' => 'Options',
	)) . '

			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'require_item_image',
		'selected' => $__vars['category']['require_item_image'],
		'label' => 'Require item image',
		'hint' => 'When this option is enabled, an image attachment must be uploaded in order for the item to be created.',
		'_type' => 'option',
	)), array(
	)) . '			
			
			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'allow_contributors',
		'selected' => $__vars['category']['allow_contributors'],
		'label' => 'Allow contributors and co-owners',
		'hint' => 'This option allows item owners with appropriate permissions, to add contributors and or co-owners. ',
		'_dependent' => array('
						<div class="inputGroup" style="margin-top: 5px;">
							<span class="inputGroup-text">
								' . 'Max allowed' . $__vars['xf']['language']['label_separator'] . '
							</span>
							' . $__templater->formNumberBox(array(
		'name' => 'max_allowed_contributors',
		'value' => $__vars['category']['max_allowed_contributors'],
		'min' => '1',
		'max' => '100',
	)) . '
						</div>
						<div class="inputGroup" style="margin-top: 5px; margin-bottom: 10px;">
							<span class="inputGroup-text  inputChoices-explain">
								' . 'The maximum allowed number of contributors/co-owners that can be set for a given item within this category.' . '
							</span>
						</div>

						' . $__templater->formCheckBox(array(
	), array(array(
		'name' => 'allow_self_join_contributors',
		'selected' => $__vars['category']['allow_self_join_contributors'],
		'label' => 'Allow self join contributors team',
		'hint' => 'Allows members with the appropriate permissions to self join the contributor team for a given item in this category. ',
		'_type' => 'option',
	))) . '
					'),
		'_type' => 'option',
	)), array(
	)) . '			
			
			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'allow_poll',
		'selected' => $__vars['category']['allow_poll'],
		'label' => 'Allow poll',
		'hint' => 'When this option is enabled, a poll can be added to items within this category.',
		'_type' => 'option',
	)), array(
	)) . '

			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'allow_location',
		'selected' => $__vars['category']['allow_location'],
		'label' => 'Allow location',
		'hint' => 'When this option is enabled, a location field will be available on the item create/edit form. This location field will be used to display a link in the header or Information sidebar block that when clicked on will open up a Google map to the location.',
		'_dependent' => array('	
						' . $__templater->formCheckBox(array(
	), array(array(
		'value' => '1',
		'label' => 'Require location',
		'name' => 'require_location',
		'selected' => $__vars['category']['require_location'],
		'hint' => 'When this option is enabled, a location must be included when posting or editing items in this category.',
		'_type' => 'option',
	))) . '
						
						' . $__templater->formCheckBox(array(
	), array(array(
		'name' => 'display_location_on_list',
		'selected' => $__vars['category']['display_location_on_list'],
		'label' => 'Display location on list',
		'hint' => 'When this option is enabled, some location data (City, State, Country) will be displayed on listing pages using List View, Grid View, Tile View, Item View and Carousel layouts. You can choose a specific display type below.',
		'_dependent' => array('
									' . $__templater->formRadio(array(
		'name' => 'location_on_list_display_type',
		'value' => $__vars['category']['location_on_list_display_type'],
	), array(array(
		'value' => 'city_state',
		'label' => 'Display type: City, State (Mountain View, California)',
		'_type' => 'option',
	),
	array(
		'value' => 'city_state_short',
		'label' => 'Display type: City, State short (Mountain View, CA)',
		'_type' => 'option',
	),
	array(
		'value' => 'city_state_country',
		'label' => 'Display type: City, State, Country (Mountain View, California, United States)',
		'_type' => 'option',
	),
	array(
		'value' => 'city_state_country_short',
		'label' => 'Display type: City, State, Country short (Mountain View, California, US)',
		'_type' => 'option',
	),
	array(
		'value' => 'city_state_short_country_short',
		'label' => 'Display type: City, State short, Country short (Mountain View, CA, US)',
		'_type' => 'option',
	),
	array(
		'value' => 'formatted_address',
		'label' => 'Display type: Formatted address (1600 Amphitheatre Parkway, Mountain View, CA 94043)',
		'_type' => 'option',
	))) . '
								'),
		'_type' => 'option',
	))) . '				
					'),
		'_type' => 'option',
	)), array(
	)) . '
			
			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'allow_business_hours',
		'selected' => $__vars['category']['allow_business_hours'],
		'label' => 'Allow business hours',
		'hint' => 'These are the regular customer-facing hours of operation for a typical week. ',
		'_type' => 'option',
	)), array(
	)) . '			

			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'allow_comments',
		'selected' => $__vars['category']['allow_comments'],
		'label' => 'Allow comments',
		'hint' => 'This option allows comments for items in this category. This option can be enabled/disabled at any time and only effects items within this specific category. <b>Important note</b>: Comment Permissions determine whether or not the viewing user can view/post comments. ',
		'_type' => 'option',
	)), array(
	)) . '
			
			<hr class="formRowSep" />
			
			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'allow_ratings',
		'selected' => $__vars['category']['allow_ratings'],
		'label' => 'Allow ratings',
		'hint' => 'When enabled, users with the appropriate permissions will be able to rate and or review items within this category. ',
		'_dependent' => array('
						' . $__templater->formCheckBox(array(
	), array(array(
		'value' => '1',
		'label' => 'Require review',
		'name' => 'require_review',
		'selected' => $__vars['category']['require_review'],
		'hint' => 'When enabled, users must submit a review when rating an item.',
		'_type' => 'option',
	))) . '

						' . $__templater->formCheckBox(array(
	), array(array(
		'value' => '1',
		'label' => 'Allow pros and cons',
		'name' => 'allow_pros_cons',
		'selected' => $__vars['category']['allow_pros_cons'],
		'hint' => 'When enabled, users will be able to input pros and cons when submitting a review for an item.',
		'_type' => 'option',
	))) . '

						' . $__templater->formCheckBox(array(
	), array(array(
		'value' => '1',
		'label' => 'Allow anonymous reviews',
		'name' => 'allow_anon_reviews',
		'selected' => $__vars['category']['allow_anon_reviews'],
		'hint' => 'When enabled, users will have the option to leave an anonymous review.',
		'_type' => 'option',
	))) . '
						
						' . $__templater->formCheckBox(array(
	), array(array(
		'value' => '1',
		'label' => 'Allow review voting',
		'name' => 'allow_review_voting',
		'selected' => $__vars['category']['review_voting'],
		'hint' => 'When enabled, members with the appropriate permissions can vote on reviews.',
		'_dependent' => array('
									' . $__templater->formRadio(array(
		'name' => 'review_voting',
		'value' => $__vars['category']['review_voting'],
	), array(array(
		'value' => 'yes',
		'label' => 'Upvoting and downvoting',
		'_type' => 'option',
	),
	array(
		'value' => 'upvote',
		'label' => 'Upvoting only',
		'_type' => 'option',
	))) . '
								'),
		'_type' => 'option',
	))) . '						
					'),
		'_type' => 'option',
	)), array(
		'label' => 'Rating options',
	)) . '

			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'allow_author_rating',
		'selected' => $__vars['category']['allow_author_rating'],
		'label' => 'Allow author rating',
		'hint' => 'When enabled, this option allows the item author to set an author rating when creating or editing the item. This rating is separate from user ratings.',
		'_type' => 'option',
	)), array(
	)) . '
			
			<hr class="formRowSep" />
			
			' . $__templater->formSelectRow(array(
		'name' => 'thread_node_id',
		'value' => $__vars['category']['thread_node_id'],
		'id' => 'js-rmThreadNodeList',
	), $__compilerTemp1, array(
		'label' => 'Automatically create thread in forum',
		'explain' => 'If selected, whenever an item in this category is created, a thread will be posted in this forum. <b>Only "general discussion" type forums may be selected</b>.',
	)) . '
		
			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'thread_set_item_tags',
		'selected' => $__vars['category']['thread_set_item_tags'],
		'label' => 'Set item tags for automatically created thread',
		'hint' => 'If enabled, when creating a new item that has tags, those tags will also be applied to the newly created associated discussion thread.',
		'_type' => 'option',
	),
	array(
		'name' => 'autopost_review',
		'selected' => $__vars['category']['autopost_review'],
		'label' => 'Automatically post new review notice in thread',
		'hint' => 'If selected, whenever a new review is posted on an item in this category, a post will be made in the discussion thread associated with the item. ',
		'_type' => 'option',
	),
	array(
		'name' => 'autopost_update',
		'selected' => $__vars['category']['autopost_update'],
		'label' => 'Automatically post new item update notice in thread',
		'hint' => 'If selected, whenever a new item update is added to an item in this category, a post will be made in the discussion thread associated with the item. ',
		'_type' => 'option',
	)), array(
	)) . '			

			' . $__templater->formRow('
				' . '' . '
				' . $__templater->callMacro('public:prefix_macros', 'select', array(
		'type' => 'thread',
		'prefixes' => $__vars['threadPrefixes'],
		'selected' => $__vars['category']['thread_prefix_id'],
		'name' => 'thread_prefix_id',
		'href' => $__templater->func('link', array('forums/prefixes', ), false),
		'listenTo' => '#js-rmThreadNodeList',
	), $__vars) . '
			', array(
		'label' => 'Automatically created thread prefix',
		'rowtype' => 'input',
	)) . '
		</div>

		<h3 class="block-formSectionHeader">
			<span class="collapseTrigger collapseTrigger--block" data-xf-click="toggle" data-target="< :up:next">
				<span class="block-formSectionHeader-aligner">' . 'Item overview sections 1-6 options' . '</span>
			</span>
		</h3>
		<div class="block-body block-body--collapsible">
			' . $__templater->formTextBoxRow(array(
		'name' => 'title_s1',
		'value' => $__vars['category']['title_s1'],
	), array(
		'label' => 'Section ' . 1 . ' title',
		'hint' => 'Required',
		'explain' => 'This title is displayed above the section content on the item overview page.',
	)) . '

			' . $__templater->formTextAreaRow(array(
		'name' => 'description_s1',
		'value' => $__vars['category']['description_s1'],
		'autosize' => 'true',
	), array(
		'label' => 'Section ' . 1 . ' description',
		'explain' => 'This optional description is displayed on the item create and edit forms so that you can explain any details about any of the section inputs. ',
	)) . '

			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'editor_s1',
		'selected' => $__vars['category']['editor_s1'],
		'label' => 'Enable editor',
		'hint' => 'If enabled, will display the editor to allow users to enter custom WYSIWYG content for the specific section. ',
		'_dependent' => array('
						<div class="inputGroup" style="margin-top: 10px;">
							<span class="inputGroup-text">
								' . 'Minimum message length' . $__vars['xf']['language']['label_separator'] . '
							</span>
							' . $__templater->formNumberBox(array(
		'name' => 'min_message_length_s1',
		'value' => $__vars['category']['min_message_length_s1'],
		'min' => '0',
		'step' => '10',
		'size' => '5',
	)) . '
						</div>
						<div class="inputGroup formRow-explain">
							' . 'This option allows you to set a minimum required message length for this section. 
<br>
<b>Note:</b> Leave set to 0 to disable this option.' . '
						</div>
					'),
		'_type' => 'option',
	)), array(
	)) . '

			<hr class="formRowSep" />

			' . $__templater->formRow('
				' . 'Sections 2-6 allow you to create additional sections that will display on the item overview page.  These sections can contain text via editor input (must enable the editor) and or custom fields set to display in a specific section.   <b>Note</b> You must enter a TITLE to enable the section.' . '
			', array(
		'label' => 'Optional sections 2-6',
	)) . '

			' . $__templater->formTextBoxRow(array(
		'name' => 'title_s2',
		'value' => $__vars['category']['title_s2'],
	), array(
		'label' => 'Section ' . 2 . ' title',
		'hint' => 'Required to enable this section',
		'explain' => 'This title is displayed above the section content on the item overview page.',
	)) . '

			' . $__templater->formTextAreaRow(array(
		'name' => 'description_s2',
		'value' => $__vars['category']['description_s2'],
		'autosize' => 'true',
	), array(
		'label' => 'Section ' . 2 . ' description',
		'explain' => 'This optional description is displayed on the item create and edit forms so that you can explain any details about any of the section inputs. ',
	)) . '

			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'editor_s2',
		'selected' => $__vars['category']['editor_s2'],
		'label' => 'Enable editor',
		'hint' => 'If enabled, will display the editor to allow users to enter custom WYSIWYG content for the specific section. ',
		'_dependent' => array('
						<div class="inputGroup" style="margin-top: 10px;">
							<span class="inputGroup-text">
								' . 'Minimum message length' . $__vars['xf']['language']['label_separator'] . '
							</span>
							' . $__templater->formNumberBox(array(
		'name' => 'min_message_length_s2',
		'value' => $__vars['category']['min_message_length_s2'],
		'min' => '0',
		'step' => '10',
		'size' => '5',
	)) . '
						</div>
						<div class="inputGroup formRow-explain">
							' . 'This option allows you to set a minimum required message length for this section. 
<br>
<b>Note:</b> Leave set to 0 to disable this option.' . '
						</div>
					'),
		'_type' => 'option',
	)), array(
	)) . '

			<hr class="formRowSep" />

			' . $__templater->formTextBoxRow(array(
		'name' => 'title_s3',
		'value' => $__vars['category']['title_s3'],
	), array(
		'label' => 'Section ' . 3 . ' title',
		'hint' => 'Required to enable this section',
		'explain' => 'This title is displayed above the section content on the item overview page.',
	)) . '

			' . $__templater->formTextAreaRow(array(
		'name' => 'description_s3',
		'value' => $__vars['category']['description_s3'],
		'autosize' => 'true',
	), array(
		'label' => 'Section ' . 3 . ' description',
		'explain' => 'This optional description is displayed on the item create and edit forms so that you can explain any details about any of the section inputs. ',
	)) . '

			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'editor_s3',
		'selected' => $__vars['category']['editor_s3'],
		'label' => 'Enable editor',
		'hint' => 'If enabled, will display the editor to allow users to enter custom WYSIWYG content for the specific section. ',
		'_dependent' => array('
						<div class="inputGroup" style="margin-top: 10px;">
							<span class="inputGroup-text">
								' . 'Minimum message length' . $__vars['xf']['language']['label_separator'] . '
							</span>
							' . $__templater->formNumberBox(array(
		'name' => 'min_message_length_s3',
		'value' => $__vars['category']['min_message_length_s3'],
		'min' => '0',
		'step' => '10',
		'size' => '5',
	)) . '
						</div>
						<div class="inputGroup formRow-explain">
							' . 'This option allows you to set a minimum required message length for this section. 
<br>
<b>Note:</b> Leave set to 0 to disable this option.' . '
						</div>
					'),
		'_type' => 'option',
	)), array(
	)) . '

			<hr class="formRowSep" />

			' . $__templater->formTextBoxRow(array(
		'name' => 'title_s4',
		'value' => $__vars['category']['title_s4'],
	), array(
		'label' => 'Section ' . 4 . ' title',
		'hint' => 'Required to enable this section',
		'explain' => 'This title is displayed above the section content on the item overview page.',
	)) . '

			' . $__templater->formTextAreaRow(array(
		'name' => 'description_s4',
		'value' => $__vars['category']['description_s4'],
		'autosize' => 'true',
	), array(
		'label' => 'Section ' . 4 . ' description',
		'explain' => 'This optional description is displayed on the item create and edit forms so that you can explain any details about any of the section inputs. ',
	)) . '

			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'editor_s4',
		'selected' => $__vars['category']['editor_s4'],
		'label' => 'Enable editor',
		'hint' => 'If enabled, will display the editor to allow users to enter custom WYSIWYG content for the specific section. ',
		'_dependent' => array('
						<div class="inputGroup" style="margin-top: 10px;">
							<span class="inputGroup-text">
								' . 'Minimum message length' . $__vars['xf']['language']['label_separator'] . '
							</span>
							' . $__templater->formNumberBox(array(
		'name' => 'min_message_length_s4',
		'value' => $__vars['category']['min_message_length_s4'],
		'min' => '0',
		'step' => '10',
		'size' => '5',
	)) . '
						</div>
						<div class="inputGroup formRow-explain">
							' . 'This option allows you to set a minimum required message length for this section. 
<br>
<b>Note:</b> Leave set to 0 to disable this option.' . '
						</div>
					'),
		'_type' => 'option',
	)), array(
	)) . '

			<hr class="formRowSep" />

			' . $__templater->formTextBoxRow(array(
		'name' => 'title_s5',
		'value' => $__vars['category']['title_s5'],
	), array(
		'label' => 'Section ' . 5 . ' title',
		'hint' => 'Required to enable this section',
		'explain' => 'This title is displayed above the section content on the item overview page.',
	)) . '

			' . $__templater->formTextAreaRow(array(
		'name' => 'description_s5',
		'value' => $__vars['category']['description_s5'],
		'autosize' => 'true',
	), array(
		'label' => 'Section ' . 5 . ' description',
		'explain' => 'This optional description is displayed on the item create and edit forms so that you can explain any details about any of the section inputs. ',
	)) . '

			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'editor_s5',
		'selected' => $__vars['category']['editor_s5'],
		'label' => 'Enable editor',
		'hint' => 'If enabled, will display the editor to allow users to enter custom WYSIWYG content for the specific section. ',
		'_dependent' => array('
						<div class="inputGroup" style="margin-top: 10px;">
							<span class="inputGroup-text">
								' . 'Minimum message length' . $__vars['xf']['language']['label_separator'] . '
							</span>
							' . $__templater->formNumberBox(array(
		'name' => 'min_message_length_s5',
		'value' => $__vars['category']['min_message_length_s5'],
		'min' => '0',
		'step' => '10',
		'size' => '5',
	)) . '
						</div>
						<div class="inputGroup formRow-explain">
							' . 'This option allows you to set a minimum required message length for this section. 
<br>
<b>Note:</b> Leave set to 0 to disable this option.' . '
						</div>
					'),
		'_type' => 'option',
	)), array(
	)) . '

			<hr class="formRowSep" />

			' . $__templater->formTextBoxRow(array(
		'name' => 'title_s6',
		'value' => $__vars['category']['title_s6'],
	), array(
		'label' => 'Section ' . 6 . ' title',
		'hint' => 'Required to enable this section',
		'explain' => 'This title is displayed above the section content on the item overview page.',
	)) . '

			' . $__templater->formTextAreaRow(array(
		'name' => 'description_s6',
		'value' => $__vars['category']['description_s6'],
		'autosize' => 'true',
	), array(
		'label' => 'Section ' . 6 . ' description',
		'explain' => 'This optional description is displayed on the item create and edit forms so that you can explain any details about any of the section inputs. ',
	)) . '

			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'editor_s6',
		'selected' => $__vars['category']['editor_s6'],
		'label' => 'Enable editor',
		'hint' => 'If enabled, will display the editor to allow users to enter custom WYSIWYG content for the specific section. ',
		'_dependent' => array('
						<div class="inputGroup" style="margin-top: 10px;">
							<span class="inputGroup-text">
								' . 'Minimum message length' . $__vars['xf']['language']['label_separator'] . '
							</span>
							' . $__templater->formNumberBox(array(
		'name' => 'min_message_length_s6',
		'value' => $__vars['category']['min_message_length_s6'],
		'min' => '0',
		'step' => '10',
		'size' => '5',
	)) . '
						</div>
						<div class="inputGroup formRow-explain">
							' . 'This option allows you to set a minimum required message length for this section. 
<br>
<b>Note:</b> Leave set to 0 to disable this option.' . '
						</div>
					'),
		'_type' => 'option',
	)), array(
	)) . '
		</div>

		<h3 class="block-formSectionHeader">
			<span class="collapseTrigger collapseTrigger--block" data-xf-click="toggle" data-target="< :up:next">
				<span class="block-formSectionHeader-aligner">' . 'Category extra options' . '</span>
			</span>
		</h3>
		<div class="block-body block-body--collapsible">
			' . $__templater->formAssetUploadRow(array(
		'name' => 'content_image_url',
		'value' => $__vars['category']['content_image_url'],
		'asset' => 'xa_sc_cat_images',
	), array(
		'label' => 'Category content image url',
	)) . '

			' . $__templater->formTextBoxRow(array(
		'name' => 'content_title',
		'value' => $__vars['category']['content_title'],
	), array(
		'label' => 'Category content title',
		'explain' => 'This title is optional and will be used to generate a Title bar for the category content. If you leave the title blank, the category content will not have a title bar.',
	)) . '

			' . $__templater->formTextAreaRow(array(
		'name' => 'content_message',
		'value' => $__vars['category']['content_message'],
		'autosize' => 'true',
	), array(
		'label' => 'Category content',
		'explain' => 'This content is optional and is used to display custom content on the first page of a category before the listing of items. 
<br /><br />
You may use HTML. The text (or HTML) you insert here must be valid within a &lt;p&gt; tag.',
	)) . '

			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'set_item_list_order',
		'selected' => $__vars['category']['item_list_order'],
		'label' => 'Override the sc option \'Item list default sort order\'' . ' (' . $__templater->escape($__vars['xf']['options']['xaScListDefaultOrder']) . ')',
		'hint' => 'This option allows you to override the showcase option \'Item list default sort order\' and set a different item list default sort order for this category.',
		'_dependent' => array('
						' . $__templater->formSelect(array(
		'name' => 'item_list_order',
		'value' => $__vars['category']['item_list_order'],
	), array(array(
		'value' => 'create_date',
		'label' => 'Create date',
		'_type' => 'option',
	),
	array(
		'value' => 'last_update',
		'label' => 'Last update',
		'_type' => 'option',
	),
	array(
		'value' => 'rating_weighted',
		'label' => 'Rating',
		'_type' => 'option',
	),
	array(
		'value' => 'reaction_score',
		'label' => 'Reaction score',
		'_type' => 'option',
	),
	array(
		'value' => 'view_count',
		'label' => 'Views',
		'_type' => 'option',
	),
	array(
		'value' => 'title',
		'label' => 'Title',
		'_type' => 'option',
	))) . '
					'),
		'_type' => 'option',
	)), array(
		'label' => 'Item list default sort order',
	)) . '

			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'set_layout_type',
		'selected' => $__vars['category']['layout_type'],
		'label' => 'Override default item list layout type' . ' (default - ' . $__templater->escape($__vars['xf']['options']['xaScItemListLayoutType']) . ')',
		'hint' => 'This option allows you to override the default item list layout type and set a different item list layout type for this category',
		'_dependent' => array('
						' . $__templater->formRadio(array(
		'name' => 'layout_type',
		'value' => $__vars['category']['layout_type'],
	), array(array(
		'value' => 'list_view',
		'label' => 'List view',
		'_type' => 'option',
	),
	array(
		'value' => 'grid_view',
		'label' => 'Grid view',
		'_type' => 'option',
	),
	array(
		'value' => 'tile_view',
		'label' => 'Tile view',
		'_type' => 'option',
	),
	array(
		'value' => 'item_view',
		'label' => 'Item view',
		'_type' => 'option',
	))) . '
					'),
		'_type' => 'option',
	)), array(
		'label' => 'Item list layout type',
	)) . '

			<hr class="formRowSep" />

			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'map_options[enable_map]',
		'selected' => $__vars['category']['map_options']['enable_map'],
		'label' => 'Enable category map',
		'hint' => 'This option requires a Google Maps Geocoding API Key and a Google Maps JavaScript API Key. These API Keys must be set in the Showcase Map Options! ',
		'_dependent' => array('
						' . $__templater->formRadio(array(
		'name' => 'map_options[map_display_location]',
		'value' => $__vars['category']['map_options']['map_display_location'],
	), array(array(
		'value' => 'above_listing',
		'label' => 'Location: Above listing',
		'_type' => 'option',
	),
	array(
		'value' => 'below_listing',
		'label' => 'Location: Below listing',
		'_type' => 'option',
	))) . '

						<div class="inputGroup" style="margin-top: 10px;">
							<span class="inputGroup-text">
								' . 'Container height' . $__vars['xf']['language']['label_separator'] . '
							</span>
							' . $__templater->formNumberBox(array(
		'name' => 'map_options[container_height]',
		'value' => $__vars['category']['map_options']['container_height'],
		'min' => '200',
		'max' => '800',
		'step' => '10',
		'size' => '5',
	)) . '
						</div>
					'),
		'_type' => 'option',
	)), array(
		'label' => 'Category map options',
	)) . '

			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'map_options[enable_full_page_map]',
		'selected' => $__vars['category']['map_options']['enable_full_page_map'],
		'label' => 'Enable full page category map',
		'hint' => 'This option requires a Google Maps Geocoding API Key and a Google Maps JavaScript API Key. These API Keys must be set in the Showcase Map Options!',
		'_dependent' => array('
						<div class="inputGroup" style="margin-top: 10px;">
							<span class="inputGroup-text">
								' . 'Sort by' . $__vars['xf']['language']['label_separator'] . '
							</span>
							' . $__templater->formSelect(array(
		'name' => 'map_options[marker_fetch_order]',
		'value' => $__vars['category']['map_options']['marker_fetch_order'],
	), array(array(
		'value' => 'rating_weighted',
		'label' => 'Rating',
		'_type' => 'option',
	),
	array(
		'value' => 'reaction_score',
		'label' => 'Reaction score',
		'_type' => 'option',
	),
	array(
		'value' => 'view_count',
		'label' => 'Views',
		'_type' => 'option',
	),
	array(
		'value' => 'create_date',
		'label' => 'Create date',
		'_type' => 'option',
	),
	array(
		'value' => 'last_update',
		'label' => 'Last update',
		'_type' => 'option',
	))) . '
						</div>

						<div class="inputGroup" style="margin-top: 15px;">
							<span class="inputGroup-text">
								' . 'Marker limit' . $__vars['xf']['language']['label_separator'] . '
							</span>
							' . $__templater->formNumberBox(array(
		'name' => 'map_options[marker_limit]',
		'value' => $__vars['category']['map_options']['marker_limit'],
		'min' => '1',
		'size' => '5',
	)) . '
						</div>
					'),
		'_type' => 'option',
	)), array(
		'label' => 'Full page category map options',
	)) . '

			<hr class="formRowSep" />

			' . $__templater->formAssetUploadRow(array(
		'name' => 'map_options[custom_map_marker_url]',
		'value' => $__vars['category']['map_options']['custom_map_marker_url'],
		'asset' => 'xa_sc_map_markers',
	), array(
		'label' => 'Custom map marker icon url',
		'explain' => 'This allows you to override the default map marker and display a custom map marker for items in this category. ',
	)) . '

			' . $__templater->formAssetUploadRow(array(
		'name' => 'map_options[custom_featured_map_marker_url]',
		'value' => $__vars['category']['map_options']['custom_featured_map_marker_url'],
		'asset' => 'xa_sc_map_markers',
	), array(
		'label' => 'Custom featured map marker icon url',
		'explain' => 'This allows you to override the default featured map marker and display a custom featured map marker for featured items in this category. ',
	)) . '

			<hr class="formRowSep" />

			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'style_override',
		'selected' => $__vars['category']['style_id'],
		'label' => 'Override user style choice',
		'explain' => 'If specified, all visitors will view this item and its contents using the selected style, regardless of their personal style preference.',
		'_dependent' => array($__templater->formSelect(array(
		'name' => 'style_id',
		'value' => $__vars['category']['style_id'],
	), $__compilerTemp2)),
		'_type' => 'option',
	)), array(
	)) . '
		</div>

		<h3 class="block-formSectionHeader">
			<span class="collapseTrigger collapseTrigger--block" data-xf-click="toggle" data-target="< :up:next">
				<span class="block-formSectionHeader-aligner">' . 'Item field options' . '</span>
			</span>
		</h3>
		<div class="block-body block-body--collapsible">
			' . $__compilerTemp4 . '
		</div>

		<h3 class="block-formSectionHeader">
			<span class="collapseTrigger collapseTrigger--block" data-xf-click="toggle" data-target="< :up:next">
				<span class="block-formSectionHeader-aligner">' . 'Update field options' . '</span>
			</span>
		</h3>
		<div class="block-body block-body--collapsible">
			' . $__compilerTemp6 . '
		</div>

		<h3 class="block-formSectionHeader">
			<span class="collapseTrigger collapseTrigger--block" data-xf-click="toggle" data-target="< :up:next">
				<span class="block-formSectionHeader-aligner">' . 'Review field options' . '</span>
			</span>
		</h3>
		<div class="block-body block-body--collapsible">
			' . $__compilerTemp8 . '
		</div>

		<h3 class="block-formSectionHeader">
			<span class="collapseTrigger collapseTrigger--block" data-xf-click="toggle" data-target="< :up:next">
				<span class="block-formSectionHeader-aligner">' . 'Prefix options' . '</span>
			</span>
		</h3>
		<div class="block-body block-body--collapsible">
			' . $__compilerTemp10 . '
		</div>

		' . $__templater->formSubmitRow(array(
		'icon' => 'save',
		'sticky' => 'true',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('xa-sc/categories/save', $__vars['category'], ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);
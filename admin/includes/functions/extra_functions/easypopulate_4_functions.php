<?php

function ep_4_get_languages() {
	$langcode = array();
	$languages_query = ep_4_query("SELECT languages_id, name, code FROM " . TABLE_LANGUAGES . " ORDER BY sort_order");
	$i = 1;
	while ($ep_languages = mysql_fetch_array($languages_query)) {
		$ep_languages_array[$i++] = array(
			'id' => $ep_languages['languages_id'],
			'name' => $ep_languages['name'],
			'code' => $ep_languages['code']
			);
	}
	return $ep_languages_array;
}

function ep_4_set_filelayout($ep_dltype, &$filelayout_sql, $sql_filter, $langcode, $ep_supported_mods) {
	$filelayout = array();
	switch($ep_dltype) {
	case 'full': // FULL products download
		// The file layout is dynamically made depending on the number of languages
		$filelayout[] = 'v_products_model';
		$filelayout[] = 'v_products_image';
		foreach ($langcode as $key => $lang) { // create variables for each language id
			$l_id = $lang['id'];
			$filelayout[] = 'v_products_name_' . $l_id;
			$filelayout[] = 'v_products_description_' . $l_id;
			if ($ep_supported_mods['psd'] == true) { // products short description mod
				$filelayout[] = 'v_products_short_desc_' . $l_id;
			}
			$filelayout[] = 'v_products_url_' . $l_id;
		} 
		$filelayout[] = 'v_specials_price';
		$filelayout[] = 'v_specials_date_avail';
		$filelayout[] = 'v_specials_expires_date';
		$filelayout[] = 'v_products_price';
		if ($ep_supported_mods['uom'] == true) { // price UOM mod
			$filelayout[] = 'v_products_price_uom';
		} 
		if ($ep_supported_mods['upc'] == true) { // UPC Mod
			$filelayout[] = 'v_products_upc'; 
		} 
		$filelayout[] = 'v_products_weight';
		$filelayout[] = 'v_product_is_call';
		$filelayout[] = 'v_products_sort_order';
		$filelayout[] = 'v_products_quantity_order_min';
		$filelayout[] = 'v_products_quantity_order_units';
		$filelayout[] = 'v_date_avail'; // should be changed to v_products_date_available for clarity
		$filelayout[] = 'v_date_added'; // should be changed to v_products_date_added for clarity
		$filelayout[] = 'v_products_quantity';
		$filelayout[] = 'v_manufacturers_name';

		// NEW code for 'unlimited' category depth - 1 Category Column for each installed Language
		foreach ($langcode as $key => $lang) { // create categories variables for each language id
			$l_id = $lang['id'];
			$filelayout[] = 'v_categories_name_' . $l_id;
		} 
		$filelayout[] = 'v_tax_class_title';
		$filelayout[] = 'v_status'; // this should be v_products_status for clarity
		// metatags
		$filelayout[] = 'v_metatags_products_name_status';
		$filelayout[] = 'v_metatags_title_status';
		$filelayout[] = 'v_metatags_model_status';
		$filelayout[] = 'v_metatags_price_status';
		$filelayout[] = 'v_metatags_title_tagline_status';
		foreach ($langcode as $key => $lang) { // create variables for each language id
			$l_id = $lang['id'];
			$filelayout[] = 'v_metatags_title_' . $l_id;
			$filelayout[] = 'v_metatags_keywords_' . $l_id;
			$filelayout[] = 'v_metatags_description_' . $l_id;
		} 
		
		$filelayout_sql = 'SELECT
			p.products_id					as v_products_id,
			p.products_model				as v_products_model,
			p.products_image				as v_products_image,
			p.products_price				as v_products_price,';
		if ($ep_supported_mods['uom'] == true) { // price UOM mod
			$filelayout_sql .=  'p.products_price_uom as v_products_price_uom,'; // to soon be changed to v_products_price_uom
		} 
		if ($ep_supported_mods['upc'] == true) { // UPC Code mod
			$filelayout_sql .=  'p.products_upc as v_products_upc,'; 
		} 
		$filelayout_sql .= 'p.products_weight as v_products_weight,
			p.product_is_call				as v_product_is_call,
			p.products_sort_order			as v_products_sort_order, 
			p.products_quantity_order_min	as v_products_quantity_order_min,
			p.products_quantity_order_units	as v_products_quantity_order_units,
			p.products_date_available		as v_date_avail,
			p.products_date_added			as v_date_added,
			p.products_tax_class_id			as v_tax_class_id,
			p.products_quantity				as v_products_quantity,
			p.manufacturers_id				as v_manufacturers_id,
			subc.categories_id				as v_categories_id,
			p.products_status				as v_status,
			p.metatags_title_status         as v_metatags_title_status,
			p.metatags_products_name_status as v_metatags_products_name_status,
			p.metatags_model_status         as v_metatags_model_status,
			p.metatags_price_status         as v_metatags_price_status,
			p.metatags_title_tagline_status as v_metatags_title_tagline_status 
			FROM '
			.TABLE_PRODUCTS.' as p,'
			.TABLE_CATEGORIES.' as subc,'
			.TABLE_PRODUCTS_TO_CATEGORIES.' as ptoc
			WHERE
			p.products_id = ptoc.products_id AND
			ptoc.categories_id = subc.categories_id'.$sql_filter;
		break;
		
	case 'priceqty':
		$filelayout[] = 'v_products_model';
		$filelayout[] = 'v_status'; // 11-23-2010 added product status to price quantity option
		$filelayout[] = 'v_specials_price';
		$filelayout[] = 'v_specials_date_avail';
		$filelayout[] = 'v_specials_expires_date';
		$filelayout[] = 'v_products_price';
		if ($ep_supported_mods['uom'] == true) { // price UOM mod
			$filelayout[] = 'v_products_price_uom'; // to soon be changed to v_products_price_uom
		} 
		$filelayout[] = 'v_products_quantity';
		$filelayout_sql = 'SELECT
			p.products_id     as v_products_id,
			p.products_status as v_status,
			p.products_model  as v_products_model,
			p.products_price  as v_products_price,';

		if ($ep_supported_mods['uom'] == true) { // price UOM mod
			$filelayout_sql .=  'p.products_price_uom as v_products_price_uom,'; // to soon be changed to v_products_price_uom
		} 
		$filelayout_sql .= 'p.products_tax_class_id as v_tax_class_id,
			p.products_quantity as v_products_quantity
			FROM '
			.TABLE_PRODUCTS.' as p';
		break;
	
	// Chadd: quantity price breaks file layout
	// 09-30-09 Need a configuration variable to set the MAX discounts level
	//          then I will be able to generate $filelayout() dynamically
	case 'pricebreaks':
		$filelayout[] =	'v_products_model';
		$filelayout[] = 'v_status'; // 11-23-2010 added product status to price quantity option
		$filelayout[] =	'v_products_price';
		if ($ep_supported_mods['uom'] == true) { // price UOM mod
			$filelayout[] = 'v_products_price_uom'; // to soon be changed to v_products_price_uom
		} 
		$filelayout[] =	'v_products_discount_type';
		$filelayout[] =	'v_products_discount_type_from';
		// discount quantities base on $max_qty_discounts	
		// must be a better way to get the maximum discounts used at any given time
		for ($i=1;$i<EASYPOPULATE_4_CONFIG_MAX_QTY_DISCOUNTS+1;$i++) {
			// $filelayout[] = 'v_discount_id_' . $i; // chadd - no longer needed
			$filelayout[] = 'v_discount_qty_' . $i;
			$filelayout[] = 'v_discount_price_' . $i;
		}
		$filelayout_sql = 'SELECT
			p.products_id     as v_products_id,
			p.products_status as v_status,
			p.products_model  as v_products_model,
			p.products_price  as v_products_price,';
			
		if ($ep_supported_mods['uom'] == true) { // price UOM mod
			$filelayout_sql .=  'p.products_price_uom as v_products_price_uom,'; // to soon be changed to v_products_price_uom
		} 
		$filelayout_sql .= 'p.products_discount_type as v_products_discount_type,
			p.products_discount_type_from as v_products_discount_type_from
			FROM '
			.TABLE_PRODUCTS.' as p';
	break;	

	case 'category': 
		// The file layout is dynamically made depending on the number of languages
		$filelayout[] = 'v_products_model';
		// NEW code for unlimited category depth - 1 Category Column for each installed Language
		foreach ($langcode as $key => $lang) { // create categories variables for each language id
			$l_id = $lang['id'];
			$filelayout[] = 'v_categories_name_' . $l_id;
		} 
		$filelayout_sql = 'SELECT
			p.products_id      as v_products_id,
			p.products_model   as v_products_model,
			subc.categories_id as v_categories_id
			FROM '
			.TABLE_PRODUCTS.'   as p,'
			.TABLE_CATEGORIES.' as subc,'
			.TABLE_PRODUCTS_TO_CATEGORIES.' as ptoc      
			WHERE
			p.products_id = ptoc.products_id AND
			ptoc.categories_id = subc.categories_id';
		break;

    // Categories Meta Data - added 12-02-2010
	// 12-10-2010 removed array_merge() for better performance
	case 'categorymeta':
		$fileMeta = array();
		$filelayout = array();
		$filelayout[] = 'v_categories_id';
		$filelayout[] = 'v_categories_image';
		foreach ($langcode as $key => $lang) { // create categories variables for each language id
			$l_id = $lang['id'];
			$filelayout[] = 'v_categories_name_' . $l_id;
			$filelayout[] = 'v_categories_description_' . $l_id;
		} 
		foreach ($langcode as $key => $lang) { // create metatags variables for each language id
			$l_id = $lang['id'];
			$filelayout[]   = 'v_metatags_title_' . $l_id;
			$filelayout[]   = 'v_metatags_keywords_' . $l_id;
			$filelayout[]   = 'v_metatags_description_' . $l_id;
		} 
		$filelayout_sql = 'SELECT
			c.categories_id          AS v_categories_id,
			c.categories_image       AS v_categories_image
			FROM '
			.TABLE_CATEGORIES.' AS c';
		break;
		
	case 'attrib':
		$filelayout[] =	'v_products_attributes_id';
		$filelayout[] =	'v_products_id';
		$filelayout[] =	'v_products_model'; // product model from table PRODUCTS
		$filelayout[] =	'v_options_id';
		$filelayout[] =	'v_products_options_name'; // options name from table PRODUCTS_OPTIONS
		$filelayout[] =	'v_products_options_type'; // 0-drop down, 1=text , 2=radio , 3=checkbox, 4=file, 5=read only 
		$filelayout[] =	'v_options_values_id';
		$filelayout[] =	'v_products_options_values_name'; // options values name from table PRODUCTS_OPTIONS_VALUES
		$filelayout[] =	'v_options_values_price';
		$filelayout[] =	'v_price_prefix';
		$filelayout[] =	'v_products_options_sort_order';
		$filelayout[] =	'v_product_attribute_is_free';
		$filelayout[] =	'v_products_attributes_weight';
		$filelayout[] =	'v_products_attributes_weight_prefix';
		$filelayout[] =	'v_attributes_display_only';
		$filelayout[] =	'v_attributes_default';
		$filelayout[] =	'v_attributes_discounted';
		$filelayout[] =	'v_attributes_image';
		$filelayout[] =	'v_attributes_price_base_included';
		$filelayout[] =	'v_attributes_price_onetime';
		$filelayout[] =	'v_attributes_price_factor';
		$filelayout[] =	'v_attributes_price_factor_offset';
		$filelayout[] =	'v_attributes_price_factor_onetime';
		$filelayout[] =	'v_attributes_price_factor_onetime_offset';
		$filelayout[] =	'v_attributes_qty_prices';
		$filelayout[] =	'v_attributes_qty_prices_onetime';
		$filelayout[] =	'v_attributes_price_words';
		$filelayout[] =	'v_attributes_price_words_free';
		$filelayout[] =	'v_attributes_price_letters';
		$filelayout[] =	'v_attributes_price_letters_free';
		$filelayout[] =	'v_attributes_required';
		// a = table PRODUCTS_ATTRIBUTES
		// p = table PRODUCTS
		// o = table PRODUCTS_OPTIONS
		// v = table PRODUCTS_OPTIONS_VALUES
		$filelayout_sql = 'SELECT
			a.products_attributes_id            as v_products_attributes_id,
			a.products_id                       as v_products_id,
			p.products_model				    as v_products_model,
			a.options_id                        as v_options_id,
			o.products_options_id               as v_products_options_id,
			o.products_options_name             as v_products_options_name,
			o.products_options_type             as v_products_options_type,
			a.options_values_id                 as v_options_values_id,
			v.products_options_values_id        as v_products_options_values_id,
			v.products_options_values_name      as v_products_options_values_name,
			a.options_values_price              as v_options_values_price,
			a.price_prefix                      as v_price_prefix,
			a.products_options_sort_order       as v_products_options_sort_order,
			a.product_attribute_is_free         as v_product_attribute_is_free,
			a.products_attributes_weight        as v_products_attributes_weight,
			a.products_attributes_weight_prefix as v_products_attributes_weight_prefix,
			a.attributes_display_only           as v_attributes_display_only,
			a.attributes_default                as v_attributes_default,
			a.attributes_discounted             as v_attributes_discounted,
			a.attributes_image                  as v_attributes_image,
			a.attributes_price_base_included    as v_attributes_price_base_included,
			a.attributes_price_onetime          as v_attributes_price_onetime,
			a.attributes_price_factor           as v_attributes_price_factor,
			a.attributes_price_factor_offset    as v_attributes_price_factor_offset,
			a.attributes_price_factor_onetime   as v_attributes_price_factor_onetime,
			a.attributes_price_factor_onetime_offset      as v_attributes_price_factor_onetime_offset,
			a.attributes_qty_prices             as v_attributes_qty_prices,
			a.attributes_qty_prices_onetime     as v_attributes_qty_prices_onetime,
			a.attributes_price_words            as v_attributes_price_words,
			a.attributes_price_words_free       as v_attributes_price_words_free,
			a.attributes_price_letters          as v_attributes_price_letters,
			a.attributes_price_letters_free     as v_attributes_price_letters_free,
			a.attributes_required               as v_attributes_required
			FROM '
			.TABLE_PRODUCTS_ATTRIBUTES.     ' as a,'
			.TABLE_PRODUCTS.                ' as p,'
			.TABLE_PRODUCTS_OPTIONS.        ' as o,'
			.TABLE_PRODUCTS_OPTIONS_VALUES. ' as v
			WHERE
			a.products_id       = p.products_id AND
			a.options_id        = o.products_options_id AND
			a.options_values_id = v.products_options_values_id';
		break;

	case 'attrib_basic_simple': // simplified sinlge-line attributes
		// $filelayout[] =	'v_products_attributes_id';
		// $filelayout[] =	'v_products_id';
		$filelayout[] =	'v_products_model'; // product model from table PRODUCTS
		// $filelayout[] =	'v_options_id';
		$filelayout[] =	'v_products_options_name'; // options name from table PRODUCTS_OPTIONS
		$filelayout[] =	'v_products_options_type'; // 0-drop down, 1=text , 2=radio , 3=checkbox, 4=file, 5=read only 
		// $filelayout[] =	'v_options_values_id';
		$filelayout[] =	'v_products_options_values_name'; // options values name from table PRODUCTS_OPTIONS_VALUES
		// a = table PRODUCTS_ATTRIBUTES
		// p = table PRODUCTS
		// o = table PRODUCTS_OPTIONS
		// v = table PRODUCTS_OPTIONS_VALUES
		$filelayout_sql = 'SELECT
			a.products_attributes_id            as v_products_attributes_id,
			a.products_id                       as v_products_id,
			p.products_model				    as v_products_model,
			a.options_id                        as v_options_id,
			o.products_options_id               as v_products_options_id,
			o.products_options_name             as v_products_options_name,
			o.products_options_type             as v_products_options_type,
			a.options_values_id                 as v_options_values_id,
			v.products_options_values_id        as v_products_options_values_id,
			v.products_options_values_name      as v_products_options_values_name
			FROM '
			.TABLE_PRODUCTS_ATTRIBUTES.     ' as a,'
			.TABLE_PRODUCTS.                ' as p,'
			.TABLE_PRODUCTS_OPTIONS.        ' as o,'
			.TABLE_PRODUCTS_OPTIONS_VALUES. ' as v
			WHERE
			a.products_id       = p.products_id AND
			a.options_id        = o.products_options_id AND
			a.options_values_id = v.products_options_values_id';
		break;

	case 'attrib_basic_detailed': // detailed multi-line attributes
		$filelayout[] =	'v_products_attributes_id';
		$filelayout[] =	'v_products_id';
		$filelayout[] =	'v_products_model'; // product model from table PRODUCTS
		$filelayout[] =	'v_options_id';
		$filelayout[] =	'v_products_options_name'; // options name from table PRODUCTS_OPTIONS
		$filelayout[] =	'v_products_options_type'; // 0-drop down, 1=text , 2=radio , 3=checkbox, 4=file, 5=read only 
		$filelayout[] =	'v_options_values_id';
		$filelayout[] =	'v_products_options_values_name'; // options values name from table PRODUCTS_OPTIONS_VALUES
		// a = table PRODUCTS_ATTRIBUTES
		// p = table PRODUCTS
		// o = table PRODUCTS_OPTIONS
		// v = table PRODUCTS_OPTIONS_VALUES
		$filelayout_sql = 'SELECT
			a.products_attributes_id            as v_products_attributes_id,
			a.products_id                       as v_products_id,
			p.products_model				    as v_products_model,
			a.options_id                        as v_options_id,
			o.products_options_id               as v_products_options_id,
			o.products_options_name             as v_products_options_name,
			o.products_options_type             as v_products_options_type,
			a.options_values_id                 as v_options_values_id,
			v.products_options_values_id        as v_products_options_values_id,
			v.products_options_values_name      as v_products_options_values_name
			FROM '
			.TABLE_PRODUCTS_ATTRIBUTES.     ' as a,'
			.TABLE_PRODUCTS.                ' as p,'
			.TABLE_PRODUCTS_OPTIONS.        ' as o,'
			.TABLE_PRODUCTS_OPTIONS_VALUES. ' as v
			WHERE
			a.products_id       = p.products_id AND
			a.options_id        = o.products_options_id AND
			a.options_values_id = v.products_options_values_id';
		break;
		
	case 'options':
		$filelayout[] =	'v_products_options_id';
		$filelayout[] =	'v_language_id';
		$filelayout[] =	'v_products_options_name';
		$filelayout[] =	'v_products_options_sort_order';
		$filelayout[] =	'v_products_options_type';
		$filelayout[] =	'v_products_options_length';
		$filelayout[] =	'v_products_options_comment';
		$filelayout[] =	'v_products_options_size';
		$filelayout[] =	'v_products_options_images_per_row';
		$filelayout[] =	'v_products_options_images_style';
		$filelayout[] =	'v_products_options_rows';
		// o = table PRODUCTS_OPTIONS
		$filelayout_sql = 'SELECT
			o.products_options_id             AS v_products_options_id,
			o.language_id                     AS v_language_id,
			o.products_options_name           AS v_products_options_name,
			o.products_options_sort_order     AS v_products_options_sort_order,
			o.products_options_type           AS v_products_options_type,
			o.products_options_length         AS v_products_options_length,
			o.products_options_comment        AS v_products_options_comment,
			o.products_options_size           AS v_products_options_size,
			o.products_options_images_per_row AS v_products_options_images_per_row,
			o.products_options_images_style   AS v_products_options_images_style,
			o.products_options_rows           AS v_products_options_rows '
			.' FROM '
			.TABLE_PRODUCTS_OPTIONS. ' AS o';
		break;
	
	case 'values':
		$filelayout[] =	'v_products_options_values_id';
		$filelayout[] =	'v_language_id';
		$filelayout[] =	'v_products_options_values_name';
		$filelayout[] =	'v_products_options_values_sort_order';
		// v = table PRODUCTS_OPTIONS_VALUES
		$filelayout_sql = 'SELECT
			v.products_options_values_id         AS v_products_options_values_id,
			v.language_id                        AS v_language_id,
			v.products_options_values_name       AS v_products_options_values_name,
			v.products_options_values_sort_order AS v_products_options_values_sort_order '
			.' FROM '
			.TABLE_PRODUCTS_OPTIONS_VALUES. ' AS v'; 
		break;

	case 'optionvalues':
		$filelayout[] =	'v_products_options_values_to_products_options_id';
		$filelayout[] =	'v_products_options_id';
		$filelayout[] =	'v_products_options_name';
		$filelayout[] =	'v_products_options_values_id';
		$filelayout[] =	'v_products_options_values_name';
		// o = table PRODUCTS_OPTIONS
		// v = table PRODUCTS_OPTIONS_VALUES
		// otv = table PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS
		$filelayout_sql = 'SELECT
			otv.products_options_values_to_products_options_id AS v_products_options_values_to_products_options_id,   	    	 
			otv.products_options_id           AS v_products_options_id,
			o.products_options_name           AS v_products_options_name,
			otv.products_options_values_id    AS v_products_options_values_id,
			v.products_options_values_name    AS v_products_options_values_name '
			.' FROM '
			.TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS. ' AS otv, '
			.TABLE_PRODUCTS_OPTIONS.        ' AS o, '
			.TABLE_PRODUCTS_OPTIONS_VALUES. ' AS v 
			WHERE 
			otv.products_options_id        = o.products_options_id AND
			otv.products_options_values_id = v.products_options_values_id'; 
		break;
	}
return $filelayout;;
}


if (!function_exists(zen_get_sub_categories)) {
  function zen_get_sub_categories(&$categories, $categories_id) {
    $sub_categories_query = mysql_query("select categories_id from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$categories_id . "'");
    while ($sub_categories = mysql_fetch_array($sub_categories_query)) {
      if ($sub_categories['categories_id'] == 0) return true;
      $categories[sizeof($categories)] = $sub_categories['categories_id'];
      if ($sub_categories['categories_id'] != $categories_id) {
        zen_get_sub_categories($categories, $sub_categories['categories_id']);
      }
    }
  }
}


function ep_4_get_uploaded_file($filename) {
	if (isset($_FILES[$filename])) {
		//global $_FILES;
		$uploaded_file = array('name' => $_FILES[$filename]['name'],
		'type' => $_FILES[$filename]['type'],
		'size' => $_FILES[$filename]['size'],
		'tmp_name' => $_FILES[$filename]['tmp_name']);
	} elseif (isset($_POST[$filename])) {
		$uploaded_file = array('name' => $_POST[$filename],
		);
	} elseif (isset($GLOBALS['HTTP_POST_FILES'][$filename])) {
		global $HTTP_POST_FILES;
		$uploaded_file = array('name' => $HTTP_POST_FILES[$filename]['name'],
		'type' => $HTTP_POST_FILES[$filename]['type'],
		'size' => $HTTP_POST_FILES[$filename]['size'],
		'tmp_name' => $HTTP_POST_FILES[$filename]['tmp_name']);
	} elseif (isset($GLOBALS['HTTP_POST_VARS'][$filename])) {
		global $HTTP_POST_VARS;
		$uploaded_file = array('name' => $HTTP_POST_VARS[$filename],
		);
	} else {
		$uploaded_file = array('name' => $GLOBALS[$filename . '_name'],
		'type' => $GLOBALS[$filename . '_type'],
		'size' => $GLOBALS[$filename . '_size'],
		'tmp_name' => $GLOBALS[$filename]);
	}
return $uploaded_file;
}

// the $filename parameter is an array with the following elements: name, type, size, tmp_name
function ep_4_copy_uploaded_file($filename, $target) {
	if (substr($target, -1) != '/') $target .= '/';
	$target .= $filename['name'];
	move_uploaded_file($filename['tmp_name'], $target);
}

function ep_4_get_tax_class_rate($tax_class_id) {
	$tax_multiplier = 0;
	$tax_query = mysql_query("select SUM(tax_rate) as tax_rate from " . TABLE_TAX_RATES . " WHERE  tax_class_id = '" . zen_db_input($tax_class_id) . "' GROUP BY tax_priority");
	if (mysql_num_rows($tax_query)) {
		while ($tax = mysql_fetch_array($tax_query)) {
			$tax_multiplier += $tax['tax_rate'];
		}
	}
	return $tax_multiplier;
}

function ep_4_get_tax_title_class_id($tax_class_title) {
	$classes_query = mysql_query("select tax_class_id from " . TABLE_TAX_CLASS . " WHERE tax_class_title = '" . zen_db_input($tax_class_title) . "'" );
	$tax_class_array = mysql_fetch_array($classes_query);
	$tax_class_id = $tax_class_array['tax_class_id'];
	return $tax_class_id ;
}

function print_el_4($item2) {
	$output_display = substr(strip_tags($item2), 0, 10) . " | ";
	return $output_display;
}

function print_el1_4($item2) {
	$output_display = sprintf("| %'.4s ", substr(strip_tags($item2), 0, 80));
	return $output_display;
}

function smart_tags_4($string,$tags,$crsub,$doit) {
	if ($doit == true) {
		foreach ($tags as $tag => $new) {
			$tag = '/('.$tag.')/';
			$string = preg_replace($tag,$new,$string);
		}
	}
	// we remove problem characters here anyway as they are not wanted..
	$string = preg_replace("/(\r\n|\n|\r)/", "", $string);
	// $crsub is redundant - may add it again later though..
	return $string;
}

function ep_4_field_name_exists($table_name, $field_name) {
    global $db;
    $sql = "show fields from " . $table_name;
    $result = $db->Execute($sql);
    while (!$result->EOF) {
      // echo 'fields found='.$result->fields['Field'].'<br />';
      if  ($result->fields['Field'] == $field_name) {
        return true; // exists, so return with no error
      }
      $result->MoveNext();
    }
    return false;
}

function ep_4_remove_product($product_model) {
  global $db, $ep_debug_logging, $ep_debug_logging_all, $ep_stack_sql_error;
  $sql = "select products_id from " . TABLE_PRODUCTS . " where products_model = '" . zen_db_input($product_model) . "'";
  $products = $db->Execute($sql);
  if (mysql_errno()) {
	$ep_stack_sql_error = true;
	if ($ep_debug_logging == true) {
		$string = "MySQL error ".mysql_errno().": ".mysql_error()."\nWhen executing:\n$sql\n";
		write_debug_log($string);
 	  }
	} elseif ($ep_debug_logging_all == true) {
		$string = "MySQL PASSED\nWhen executing:\n$sql\n";
		write_debug_log($string);
	}
  while (!$products->EOF) {
    zen_remove_product($products->fields['products_id']);
    $products->MoveNext();
  }
  return;
}

function ep_4_update_cat_ids() { // reset products master categories ID - I do not believe this works correctly - chadd
  global $db;
  $sql = "select products_id from " . TABLE_PRODUCTS;
  $check_products = $db->Execute($sql);
  while (!$check_products->EOF) {
    $sql = "select products_id, categories_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id='" . $check_products->fields['products_id'] . "'";
    $check_category = $db->Execute($sql);
    $sql = "update " . TABLE_PRODUCTS . " set master_categories_id='" . $check_category->fields['categories_id'] . "' where products_id='" . $check_products->fields['products_id'] . "'";
    $update_viewed = $db->Execute($sql);
    $check_products->MoveNext();
  }
}

function ep_4_update_prices() { // reset products_price_sorter for searches etc. - does this run correctly? Why not use standard zencart call?
  global $db;
  $sql = "select products_id from " . TABLE_PRODUCTS;
  $update_prices = $db->Execute($sql);
  while (!$update_prices->EOF) {
    zen_update_products_price_sorter($update_prices->fields['products_id']);
    $update_prices->MoveNext();
  }
}

function ep_4_update_attributes_sort_order() {
	global $db;
	$all_products_attributes= $db->Execute("select p.products_id, pa.products_attributes_id from " .
	TABLE_PRODUCTS . " p, " .
	TABLE_PRODUCTS_ATTRIBUTES . " pa " . "
	where p.products_id= pa.products_id"
	);
	while (!$all_products_attributes->EOF) {
	  $count++;
	  //$product_id_updated .= ' - ' . $all_products_attributes->fields['products_id'] . ':' . $all_products_attributes->fields['products_attributes_id'];
	  zen_update_attributes_products_option_values_sort_order($all_products_attributes->fields['products_id']);
	  $all_products_attributes->MoveNext();
	}
}


function write_debug_log_4($string) {
	global $ep_debug_log_path;
	$logFile = $ep_debug_log_path . 'ep_debug_log.txt';
  $fp = fopen($logFile,'ab');
  fwrite($fp, $string);
  fclose($fp);
  return;
}

function ep_4_query($query) {
	global $ep_debug_logging, $ep_debug_logging_all, $ep_stack_sql_error;
	$result = mysql_query($query);
	if (mysql_errno()) {
		$ep_stack_sql_error = true;
		if ($ep_debug_logging == true) {
			$string = "MySQL error ".mysql_errno().": ".mysql_error()."\nWhen executing:\n$query\n";
			write_debug_log_4($string);
		}
	} elseif ($ep_debug_logging_all == true) {
		$string = "MySQL PASSED\nWhen executing:\n$query\n";
		write_debug_log_4($string);
	}
	return $result;
}

function ep_4_setkeys() { // Easypopulate_4 Configuration Keys
	$ep_keys = array(
		'EASYPOPULATE_4_CONFIG_TEMP_DIR',
		'EASYPOPULATE_4_CONFIG_FILE_DATE_FORMAT',
		'EASYPOPULATE_4_CONFIG_DEFAULT_RAW_TIME',
		'EASYPOPULATE_4_CONFIG_PRICE_INC_TAX',
		'EASYPOPULATE_4_CONFIG_ZERO_QTY_INACTIVE',
		'EASYPOPULATE_4_CONFIG_SMART_TAGS',
		'EASYPOPULATE_4_CONFIG_ADV_SMART_TAGS',
		'EASYPOPULATE_4_CONFIG_DEBUG_LOGGING',
		'EASYPOPULATE_4_CONFIG_MAX_QTY_DISCOUNTS'
		);
return $ep_keys;
}

function install_easypopulate_4() {
	global $db;
	$db->Execute("INSERT INTO " . TABLE_CONFIGURATION_GROUP . " VALUES ('', 'Easy Populate 4', 'Configuration Options for Easy Populate 4', '1', '1')");
	$group_id = mysql_insert_id();
	$db->Execute("UPDATE " . TABLE_CONFIGURATION_GROUP . " SET sort_order = " . $group_id . " WHERE configuration_group_id = " . $group_id);
	$db->Execute("INSERT INTO " . TABLE_CONFIGURATION . " VALUES 
		('', 'Uploads Directory',                  'EASYPOPULATE_4_CONFIG_TEMP_DIR', 'temp/', 'Name of directory for your uploads (default: temp/).', " . $group_id . ", '0', NULL, now(), NULL, NULL),
		('', 'Upload File Date Format',            'EASYPOPULATE_4_CONFIG_FILE_DATE_FORMAT', 'm-d-y', 'Choose order of date values that corresponds to your uploads file, usually generated by MS Excel. Raw dates in your uploads file (Eg 2005-09-26 09:00:00) are not affected, and will upload as they are.', " . $group_id . ", '1', NULL, now(), NULL, 'zen_cfg_select_option(array(\"m-d-y\", \"d-m-y\", \"y-m-d\"),'),
		('', 'Default Raw Time',                   'EASYPOPULATE_4_CONFIG_DEFAULT_RAW_TIME', '09:00:00', 'If no time value stipulated in upload file, use this value. Useful for ensuring specials begin after a specific time of the day (default: 09:00:00)', " . $group_id . ", '2', NULL, now(), NULL, NULL),
		('', 'Upload/Download Prices Include Tax', 'EASYPOPULATE_4_CONFIG_PRICE_INC_TAX', 'false', 'Choose to include or exclude tax, depending on how you manage prices outside of Zen Cart.', " . $group_id . ", '5', NULL, now(), NULL, 'zen_cfg_select_option(array(\"true\", \"false\"),'),
		('', 'Make Zero Qty Products Inactive',    'EASYPOPULATE_4_CONFIG_ZERO_QTY_INACTIVE', 'false', 'When uploading, make the status Inactive for products with zero qty (default: false).', " . $group_id . ", '6', NULL, now(), NULL, 'zen_cfg_select_option(array(\"true\", \"false\"),'),
		('', 'Smart Tags Replacement of Newlines', 'EASYPOPULATE_4_CONFIG_SMART_TAGS', 'true', 'Allows your description fields in your uploads file to have carriage returns and/or new-lines converted to HTML line-breaks on uploading, thus preserving some rudimentary formatting (default: true).', " . $group_id . ", '7', NULL, now(), NULL, 'zen_cfg_select_option(array(\"true\", \"false\"),'),
		('', 'Advanced Smart Tags',                'EASYPOPULATE_4_CONFIG_ADV_SMART_TAGS', 'false', 'Allow the use of complex regular expressions to format descriptions, making headings bold, add bullets, etc. Configuration is in ADMIN/easypopulate_TAB.php (default: false).', " . $group_id . ", '8', NULL, now(), NULL, 'zen_cfg_select_option(array(\"true\", \"false\"),'),
		('', 'Debug Logging',                      'EASYPOPULATE_4_CONFIG_DEBUG_LOGGING', 'true', 'Allow Easy Populate to generate an error log on errors only (default: true)', " . $group_id . ", '9', NULL, now(), NULL, 'zen_cfg_select_option(array(\"true\", \"false\"),'),
		('', 'Maximum Quantity Discounts',         'EASYPOPULATE_4_CONFIG_MAX_QTY_DISCOUNTS', '3', 'Maximum number of quantity discounts (price breaks). Is the number of discount columns in downloaded file (default: 3).', " . $group_id . ", '10', NULL, now(), NULL, NULL)
		");
}

function remove_easypopulate_4() {
	global $db, $ep_keys;
	$sql = "SELECT configuration_group_id FROM " . TABLE_CONFIGURATION_GROUP . " WHERE configuration_group_title = 'Easy Populate 4'";
	$result = ep_4_query($sql);
	if (mysql_num_rows($result)) { // we have at least 1 EP group
		$ep_groups =  mysql_fetch_array($result);
		foreach ($ep_groups as $ep_group) {
		    $db->Execute("DELETE FROM " . TABLE_CONFIGURATION_GROUP . " WHERE configuration_group_id = '" . (int)$ep_group . "'");
		}
	}
	// delete any EP keys found in config
	foreach ($ep_keys as $ep_key) {
		@$db->Execute("DELETE FROM " . TABLE_CONFIGURATION . " WHERE configuration_key = '" . $ep_key . "'");
	}
}

function ep_4_chmod_check($tempdir) {
	global $messageStack;
	if (!@file_exists(DIR_FS_CATALOG . $tempdir . ".")) { // directory does not exist
		$messageStack->add(sprintf(EASYPOPULATE_4_MSGSTACK_TEMP_FOLDER_MISSING, $tempdir, DIR_FS_CATALOG), 'warning');
		$chmod_check = false;
	} else { // directory exists, test is writeable
		if (!@is_writable(DIR_FS_CATALOG . $tempdir . ".")) { // directory does not exist
			$messageStack->add(sprintf(EASYPOPULATE_4_MSGSTACK_TEMP_FOLDER_NOT_WRITABLE, $tempdir, DIR_FS_CATALOG), 'warning');
			$chmod_check = false;
		} else { 
			$chmod_check = true;
		}
	}
	return $chmod_check;
}

/**
* The following functions are for testing purposes only
*/
// available zen functions of use..
/*
function zen_get_category_name($category_id, $language_id)
function zen_get_category_description($category_id, $language_id)
function zen_get_products_name($product_id, $language_id = 0)
function zen_get_products_description($product_id, $language_id)
function zen_get_products_model($products_id)
*/

function register_globals_vars_check_4 () {
	echo phpversion();
	echo '<br>register_globals = ', ini_get('register_globals'), '<br>';
	print "_GET: "; print_r($_GET); echo '<br />';
	print "_POST: "; print_r($_POST); echo '<br />';
	print "_FILES: "; print_r($_FILES); echo '<br />';
	print "_COOKIE: "; print_r($_COOKIE); echo '<br />';
	print "GLOBALS: "; print_r($GLOBALS); echo '<br />';
	print "_REQUEST: "; print_r($_REQUEST); echo '<br /><br />';
	global $HTTP_POST_FILES;
	print "HTTP_POST_FILES: "; print_r($HTTP_POST_FILES); echo '<br />';
}
?>
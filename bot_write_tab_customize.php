<?php

// This is a PLUGIN TEMPLATE.

// Copy this file to a new name like abc_myplugin.php.  Edit the code, then
// run this file at the command line to produce a plugin for distribution:
// $ php abc_myplugin.php > abc_myplugin-0.1.txt

// Plugin name is optional.  If unset, it will be extracted from the current
// file name. Plugin names should start with a three letter prefix which is
// unique and reserved for each plugin author ("abc" is just an example).
// Uncomment and edit this line to override:
$plugin['name'] = 'bot_write_tab_customize';

// Allow raw HTML help, as opposed to Textile.
// 0 = Plugin help is in Textile format, no raw HTML allowed (default).
// 1 = Plugin help is in raw HTML.  Not recommended.
# $plugin['allow_html_help'] = 1;

$plugin['version'] = '0.5.2beta';
$plugin['author'] = 'redbot';
$plugin['author_uri'] = 'http://www.redbot.it/txp';
$plugin['description'] = 'Rearrange and style items in the write tab';

// Plugin load order:
// The default value of 5 would fit most plugins, while for instance comment
// spam evaluators or URL redirectors would probably want to run earlier
// (1...4) to prepare the environment for everything else that follows.
// Values 6...9 should be considered for plugins which would work late.
// This order is user-overrideable.
$plugin['order'] = '5';

// Plugin 'type' defines where the plugin is loaded
// 0 = public       : only on the public side of the website (default)
// 1 = public+admin : on both the public and admin side
// 2 = library      : only when include_plugin() or require_plugin() is called
// 3 = admin        : only on the admin side
$plugin['type'] = '3';

// Plugin "flags" signal the presence of optional capabilities to the core plugin loader.
// Use an appropriately OR-ed combination of these flags.
// The four high-order bits 0xf000 are available for this plugin's private use
if (!defined('PLUGIN_HAS_PREFS')) define('PLUGIN_HAS_PREFS', 0x0001); // This plugin wants to receive "plugin_prefs.{$plugin['name']}" events
if (!defined('PLUGIN_LIFECYCLE_NOTIFY')) define('PLUGIN_LIFECYCLE_NOTIFY', 0x0002); // This plugin wants to receive "plugin_lifecycle.{$plugin['name']}" events

$plugin['flags'] = '0';

if (!defined('txpinterface'))
        @include_once('zem_tpl.php');

# --- BEGIN PLUGIN CODE ---
//<?
if(@txpinterface == 'admin') {
	add_privs('bot_wtc_tab', '1,2');
	register_tab('extensions', 'bot_wtc_tab', 'bot_wtc');
	register_callback('bot_wtc_tab', 'bot_wtc_tab');
	register_callback('bot_wtc', 'article');
	register_callback('bot_hide_per_section', 'article');
	register_callback('bot_hidden_sections', 'article');
}



//===========================================



global $bot_items;

bot_wtc_insert_in_main_array ('textile_help!bot! (!bot!label!bot!)', '$("h3:has(a[href=#textile_help])")');
bot_wtc_insert_in_main_array ('textile_help', '$("#textile_help")');
bot_wtc_insert_in_main_array ('advanced_options!bot! (!bot!label!bot!)', '$("h3:has(a[href=#advanced])")');
bot_wtc_insert_in_main_array ('advanced_options', '$("#advanced")');
bot_wtc_insert_in_main_array ('article_markup', '$("p:has(select[id*=markup-body])")');
bot_wtc_insert_in_main_array ('excerpt_markup', '$("p:has(select[id*=markup-excerpt])")');
bot_wtc_insert_in_main_array ('override_default_form', '$("p:has(select[id*=override-form])")');
bot_wtc_insert_in_main_array ('keywords', '$("p:has(textarea[id=keywords])")');
bot_wtc_insert_in_main_array ('article_image', '$("p:has(input[id=article-image])")');
bot_wtc_insert_in_main_array ('url_title', '$("p:has(input[id=url-title])")');
bot_wtc_insert_in_main_array ('recent_articles!bot! (!bot!label!bot!)', '$("h3:has(a[href=#recent])")');
bot_wtc_insert_in_main_array ('recent_articles', '$("#recent")');

bot_wtc_insert_in_main_array ('title', '$("p:has(input[id=title])")');
bot_wtc_insert_in_main_array ('body', '$("p:has(textarea[id=body])")');
bot_wtc_insert_in_main_array ('excerpt', '$("p:has(textarea[id=excerpt])")');

bot_wtc_insert_in_main_array ('create_new', '$("p:has(a[href=index.php?event=article])")');
bot_wtc_insert_in_main_array ('prev!bot! | !bot!next', '$("p:has(a.navlink)")');
bot_wtc_insert_in_main_array ('status', '$("#write-status")');
bot_wtc_insert_in_main_array ('sort_display', '$("#write-sort")');
bot_wtc_insert_in_main_array ('category1', '$("p:has(label[for=category-1])")');
bot_wtc_insert_in_main_array ('category2', '$("p:has(label[for=category-2])")');
bot_wtc_insert_in_main_array ('section', '$("p:has(select[id=section])")');
bot_wtc_insert_in_main_array ('more!bot! (!bot!label!bot!)', '$("h3:has(a[href=#more])")');
bot_wtc_insert_in_main_array ('more', '$("#more")');
bot_wtc_insert_in_main_array ('comments', '$("#write-comments")');
bot_wtc_insert_in_main_array ('timestamp', '$("#write-timestamp")');
bot_wtc_insert_in_main_array ('expires', '$("#write-expires")');
bot_wtc_insert_in_main_array ('publish', '$("input[name=publish]")');
bot_wtc_insert_in_main_array ('save', '$("input[name=save]")');

bot_wtc_insert_in_main_array ('logged_in_as', '$("#moniker")');

bot_wtc_insert_in_main_array ('TD Column 1', '$("#article-col-1")');
bot_wtc_insert_in_main_array ('TD Column 2', '$("#article-col-2")');
bot_wtc_insert_in_main_array ('TD Main column', '$("#article-main")');
bot_wtc_insert_in_main_array ('TD !bot!preview!bot! etc.', '$("#article-tabs")');




// ===========================================================
// Helper functions
// ===========================================================



function bot_wtc_gTxt($what) {

	global $language;

	$en_us = array(
		'install_message' => 'bot_wtc is not yet properly initialized.  Use the button below to create the preferences table.',
		'upgrade_message' => 'bot_wtc must be upgraded. Use the button below to add the new fields to the preferences table.',
		'uninstall' => 'Uninstall',
		'uninstall_message' => 'Using the button below will remove the bot_wtc preferences table. <br />Use before a complete uninstall or to reset all preferences. ',
		'uninstall_confirm' => 'Are you sure you want to delete the preferences table?',
		'td_warning' => 'Columns cannot be moved relative to single items and vice-versa',
		'same_item_warning' => 'You are trying to move an item relative to itself',
		'combo_warning' => 'You tried to insert an incomplete rule',
		);

	$lang = array(
		'en-us' => $en_us
		);

		$language = (isset($lang[$language])) ? $language : 'en-us';
		$msg = (isset($lang[$language][$what])) ? $lang[$language][$what] : $what;
		return $msg;
}



//===========================================



function bot_wtc_insert_in_main_array ($title, $selector) // helps build the main array
{
	global $bot_items;
	if (strpos($title, '!bot!'))
	{
		$split_titles = explode("!bot!", $title);
		$title = '';
		for ($i = 0; $i < count($split_titles); $i++)
		{
			$title .= gTxt($split_titles[$i]); // split and build tramslated title
		}
	}
	else
	{
		$title = gTxt($title);// gets the title to allow translation
	}
	$bot_items [$selector] = gTxt($title);
	return $bot_items;
}



//===========================================



function bot_wtc_fetch_db() // creates an array of values extracted from the database
{
	if(bot_wtc_check_install()){
		$out = safe_rows('id, item, position, destination, sections, class', 'bot_wtc ','1=1');
		return $out;
	}
}



// ===========================================================



function bot_get_cfs() // creates an array of all cfs for selectInput
{
	$r = safe_rows_start('name, val, html', 'txp_prefs','event = "custom" AND val != ""');
	if ($r) {
		global $arr_custom_fields;
		while ($a = nextRow($r)) {
			$name = str_replace('_set', '', $a['name']);
			$html = $a['html'];
			if ($html == 'checkbox' || $html == 'multi-select') {
				$selector = '$("p:has(*[name='.$name.'[]])")';
			}
			else
			{
				$selector = '$("p:has(*[name='.$name.'])")';
			}
			$val = $a['val'];
			$arr_custom_fields[$selector] = $val;
		}
	}
	natcasesort($arr_custom_fields); // sort cfs - used instead of asort because is case-insensitive
	return $arr_custom_fields;
};



// ===========================================================



function bot_get_sections() // creates an array of all sections for selectInput
{
	$r = safe_rows_start('name, title', 'txp_section','1=1');
	if ($r) {
		while ($a = nextRow($r)) {
			$name = $a['name'];
			$title = $a['title'];
			$sections[$name] = $title;
		}
	}
    natcasesort($sections);
    return $sections;
}



// ===========================================================



function bot_update_button()
{
	return n.'<div style= "padding:0 0 20px;">' // update button
		.n.eInput('bot_wtc_tab')
		.n.sInput('update')
		.n.fInput('submit', 'update', 'Update', 'publish')
		.'</div>';
}



// ===========================================================



function bot_wtc_is_td($item) // checks if item is a table td
{
    $item = get_magic_quotes_gpc() ? $item : mysql_real_escape_string($item) ;

	if($item == '$(\"#article-col-1\")'
	|| $item == '$(\"#article-col-2\")'
	|| $item == '$(\"#article-main\")'
	|| $item == '$(\"#article-tabs\")'
	)
	{
		return 1;
	}
	return 0;
}



// ===========================================================



function bot_warning($warning) // outputs html for warnings
{
	return graf(hed(bot_wtc_gTxt($warning),'3', ' style="text-align:center; background:#990000; color:#fff; margin: 20px auto; padding:5px; "'));
};



//===========================================



function bot_wtc_install()
{
	// figure out what MySQL version we are using (from _update.php)
	$mysqlversion = mysql_get_server_info();
	$tabletype = (intval($mysqlversion[0]) >= 5 || preg_match('#^4\.(0\.[2-9]|(1[89]))|(1\.[2-9])#',$mysqlversion))
		? " ENGINE=MyISAM "
		: " TYPE=MyISAM ";
	if (isset($txpcfg['dbcharset']) && (intval($mysqlversion[0]) >= 5 || preg_match('#^4\.[1-9]#',$mysqlversion))) {
		$tabletype .= " CHARACTER SET = ". $txpcfg['dbcharset'] ." ";
	}

	// Create the bot_wtc table
	$bot_wtc = safe_query("CREATE TABLE `".PFX."bot_wtc` (
		`id` INT NOT NULL AUTO_INCREMENT,
		`item` VARCHAR(255) NOT NULL,
		`position` VARCHAR(255)  NOT NULL,
		`destination` VARCHAR(255)  NOT NULL,
		`sections` VARCHAR(255)  NOT NULL,
		`class` VARCHAR(255)  NOT NULL,
		PRIMARY KEY (`id`)
		) $tabletype");

	set_pref ('bot_wtc_script','', 'bot_wtc_','2'); // entry in txp_prefs table
	set_pref ('bot_wtc_static_sections','', 'bot_wtc_', '2'); // entry in txp_prefs table
}



// ===========================================================



function bot_wtc_check_install()
{
	// Check if the bot_wtc table exists
	if (getThings("Show tables like '".PFX."bot_wtc'")) {
		return true;
	}
	return false;
}



//===========================================



function bot_all_items_selectinput() // outputs all items for selectInput() (used for destination dropdown)
{
	global $bot_items;
// 	natcasesort($bot_items); // sort items - used instead of asort because is case-insensitive
	$cfs = bot_get_cfs(); // get cfs array in the form: cf_selector => cf_name
	// final values for the txp function selectInput (including cfs if any)
	if (is_array($cfs)) { // if there is at least one custom field set adds cfs to $bot_items array
		$all_items_select = array_merge($cfs, $bot_items);
	}
	else {
		$all_items_select = $bot_items;
	}
	return $all_items_select;
}


//===========================================



function bot_contextual_selectinput($current = "") // outputs only yet-not-used items for selectInput() (used for items dropdown)
{
	global $bot_items;
	$data_from_db = bot_wtc_fetch_db(); // array of values from the db
	$all_items = bot_all_items_selectinput();
	if (bot_wtc_check_install()) {
		$used_items = safe_column('item', 'bot_wtc', '1=1'); // numeric array of item values from the db
		foreach ($all_items as $item => $title) {
	   		if (!in_array($item, $used_items)) {
	 			$items_selectInput[$item] = $title;
	 		}
		}
	}
	else {
		$items_selectInput = $all_items;
	}
    if ($current) { // if the parameter is given adds current value to array
    	$items_selectInput[$current] = $all_items[$current];
    }
	return  $items_selectInput;
}



// ===========================================================
// bot_wtc tab
// ===========================================================



function bot_wtc_output_rows() // outputs the rows for the html table in the bot_wtc_tab
{
	global $bot_items;

	$selectInput_for_position = array('insertBefore'=>'before','insertAfter'=>'after'); // position values for the txp function selectInput
	$data_from_db = bot_wtc_fetch_db(); // array of values from the db

    $destination_selectInput = bot_all_items_selectinput();
	$items_selectInput = bot_contextual_selectinput();

	// builds rows for new item sections list
	$sections= bot_get_sections(); // get sections array
	$new_item_sections_rows = '';
	foreach ($sections as $key => $value) {
		$new_item_sections_row = '<label>'.checkbox('new_item_sections[]', $key, '0').$value.'</label><br />';
		$new_item_sections_rows .= $new_item_sections_row;
    }
    $new_item_sections_rows .= '<p style="margin-top:5px;"><a href="#" class="bot_all">'.gTxt("all").'</a> | <a href="#" class="bot_none">'.gTxt("none").'</a></p>'; // hide all/none

	// new item insertion
	$rows = "";
	$input_row = tr(
		td(selectInput('new_item',bot_contextual_selectinput(), '', '1'), '', 'bot_hilight')
		.td(selectInput('new_item_position', $selectInput_for_position, '', '1'))
		.td(selectInput('new_item_destination',bot_all_items_selectinput(), '', '1'))
		.td('<p><a href="#" class="bot_push">'.gTxt("tag_section_list").'</a></p><div class="bot_collapse" style="padding-top:10px">'.$new_item_sections_rows.'</div>')
		.td(finput('text','new_item_class', ''))
		.td()
		);
		$rows .= $input_row;

	// other rows - output if at least one record was already set
	if ($data_from_db){
		for ($i = 0; $i < count( $data_from_db ); $i++){
			// data for "sections to show" selectinput - decides wether a section is checked or not
			$bot_hide_in_this_sections_array = explode('|', $data_from_db[$i]['sections']);
			$item_sections_rows = '';
			foreach ($sections as $key => $value) { // if section is in db mark as checked
			    $checked = in_array($key, $bot_hide_in_this_sections_array) ? '1': '0';
				$item_sections_row =  '<label>'.checkbox('bot_wtc_sections_for_id_'.$data_from_db[$i]['id'].'[]', $key, $checked).$value.'</label><br />';
				$item_sections_rows .= $item_sections_row;
		    }
		    $item_sections_rows .= '<p style="margin-top:5px;"><a href="#" class="bot_all">'.gTxt("all").'</a> | <a href="#" class="bot_none">'.gTxt("none").'</a></p>'; // hide all/none
			$single_row = tr(
			td(selectInput('item[]',bot_contextual_selectinput($data_from_db[$i]['item']), $data_from_db[$i]['item'],'0'), '', 'bot_hilight')
			.td(selectInput('item_position[]', $selectInput_for_position, $data_from_db[$i]['position'], '1'))
			.td(selectInput('item_destination[]',bot_all_items_selectinput(), $data_from_db[$i]['destination'],'1'))
 			.td('<p><a href="#" class="bot_push">'.gTxt("tag_section_list").'</a></p><div class="bot_collapse" style="padding-top:10px">'.$item_sections_rows.'</div>')
			.td(finput('text', 'item_class[]', $data_from_db[$i]['class']))
			.td(checkbox('bot_delete_id[]', $data_from_db[$i]['id'], '0').'<label for="bot_delete_id"> '.gTxt('delete').'</label>'))
			.hInput('bot_wtc_id[]', $data_from_db[$i]['id']);

			$rows .= $single_row;
		}
	};
	return $rows;
}



//===========================================



function bot_wtc_static_sections_select()
{
	// builds rows for sections list
	$sections= bot_get_sections(); // get sections array
	$static_sections = safe_field('val', 'txp_prefs', 'name = "bot_wtc_static_sections"'); //  fetch prefs value for bot_wtc_static_sections
	$static_sections = explode('|', $static_sections); // creates an array of statica sections from the string in txp_prefs
    $static_sections_rows = '';
	foreach ($sections as $key => $value) {
	    // if section is in db mark as checked
	    $checked = in_array($key, $static_sections) ? '1': '0';
		$static_sections_row = '<label>'.checkbox('static_sections[]', $key, $checked).$value.'</label><br />';
		$static_sections_rows .= $static_sections_row;
    }
    return $static_sections_rows;
}



//===========================================



function bot_wtc_tab($event, $step)
{
	global $bot_items;
	$cfs = bot_get_cfs();

	pagetop('bot_wtc'.gTxt('preferences'), ($step == 'update' ? gTxt('preferences_saved') : ''));
	echo hed('bot | write tab customize','2', ' style="text-align: center; margin:20px auto;   padding-bottom:10px;"');

	if ($step == 'install'){
		// Install the preferences table.
		bot_wtc_install();
	}

	if ($step == 'uninstall'){
		//remove table
		safe_query("DROP TABLE ".PFX."bot_wtc");
		safe_delete('txp_prefs', 'event = "bot_wtc_"' );
	}

	if ($step == 'update'){
	    // set function variables
		$new_item = isset($_POST['new_item']) ? $_POST['new_item'] : ''; //variable
		$new_item_position = isset($_POST['new_item_position']) ? $_POST['new_item_position'] : ''; //variable
		$new_item_destination = isset($_POST['new_item_destination']) ? $_POST['new_item_destination'] : ''; //variable
		$new_item_sections = isset($_POST['new_item_sections']) ? $_POST['new_item_sections'] : ''; //array
		$new_item_class = isset($_POST['new_item_class']) ? $_POST['new_item_class'] : ''; //variable
		$bot_wtc_script = isset($_POST['bot_wtc_script']) ? $_POST['bot_wtc_script'] : ''; //variable
		$static_sections = isset($_POST['static_sections']) ? $_POST['static_sections'] : ''; //variable
		$item = isset($_POST['item']) ? $_POST['item'] : ''; //array
		$item_position = isset($_POST['item_position']) ? $_POST['item_position'] : ''; //array
		$item_destination = isset($_POST['item_destination']) ? $_POST['item_destination'] : ''; //array
		$item_class = isset($_POST['item_class']) ? $_POST['item_class'] : ''; //array
		$bot_wtc_id = isset($_POST['bot_wtc_id']) ? $_POST['bot_wtc_id'] : ''; //array
		$delete_id = isset($_POST['bot_delete_id']) ? $_POST['bot_delete_id'] : '';	 //array

		// db update for existing items
		if ($item){ // if at least a saved item exists

           	$data_from_db = bot_wtc_fetch_db(); // array of values from the db
			for ($i = 0; $i < count($item); $i++){
			    // builds the posted variable name for current item sections
			    $item_posted_sections_name = 'bot_wtc_sections_for_id_'.$data_from_db[$i]['id'];
			    $item_sections = isset($_POST[$item_posted_sections_name]) ? $_POST[$item_posted_sections_name] : ''; //array
                // builds sections string for current item
				$item_sections_string = $item_sections ? implode('|', $item_sections): '';
				// allowed input data combinations
				if (($item[$i] && $item_destination[$i] && $item_position[$i])
				|| ($item[$i] && $item_class[$i] && !$item_destination[$i] && !$item_position[$i])
				|| ($item[$i] && $item_sections_string && !$item_destination[$i] && !$item_position[$i])) {
					// check if a column is linked with a non-column item BUT ONLY IF both items are set (otherwise couldn't apply i.e. class to a single td)
					if (!((bot_wtc_is_td($item[$i]) XOR bot_wtc_is_td($item_destination[$i])) && $item_destination[$i])){
  					    // check if item is different from destination
						if($item[$i] != $item_destination[$i]){
       						safe_update("bot_wtc",
							"position = '"
							.$item_position[$i]
							."', destination = '"
							.$item_destination[$i]
							."', item = '"
							.$item[$i]
							."', sections = '"
							.$item_sections_string
							."', class = '".$item_class[$i]
							."'", "id = '".$bot_wtc_id[$i]."'");
						}
						else {
							echo bot_warning('same_item_warning');
						}
					}
					else {
						echo bot_warning('td_warning');
					}
				}
				else {
					echo bot_warning('combo_warning');
				}
			}
		}

		// db insert for new item
		// allowed input combinations
		if (($new_item && $new_item_destination && $new_item_position)
		|| ($new_item && $new_item_class && !$new_item_destination && !$new_item_position)
		|| ($new_item && $new_item_sections && !$new_item_destination && !$new_item_position)){
			// check if a column is linked with a non-column item
			if (!((bot_wtc_is_td($new_item) XOR bot_wtc_is_td($new_item_destination)) &&  $new_item_destination)){
				// check items are not the same
				if($new_item != $new_item_destination){
                    // transforms the sections array in a string
                    $new_item_sections_string = $new_item_sections ? implode('|', $new_item_sections) : '';
					safe_insert("bot_wtc",
					"position = '"
					.$new_item_position
					."', destination = '"
					.$new_item_destination
					."', class = '"
					.$new_item_class
					."', sections = '"
					.$new_item_sections_string
					."', item = '"
					.$new_item
					."'");
				}
				else {
					echo bot_warning('same_item_warning');
				}
			}
			else {
				echo bot_warning('td_warning');
			}
		}
		
		elseif ($new_item || $new_item_destination || $new_item_position || $new_item_class || $new_item_sections){
			echo bot_warning('combo_warning');
		}

		if ($delete_id){ // checks if there is something to delete
			foreach ($delete_id as $id) {
				safe_delete('bot_wtc', 'id ="'.$id.'"' );
			}
		}

		// updates static sections prefs
        $static_sections_string = $static_sections ? implode('|', $static_sections) : '';
		safe_update('txp_prefs', 'val= "'.$static_sections_string.'", html="text_input" ', 'name = "bot_wtc_static_sections"' );

        // updates script prefs
        $bot_wtc_script = get_magic_quotes_gpc() ? $bot_wtc_script : mysql_real_escape_string($bot_wtc_script) ;
		safe_update('txp_prefs', 'val= \''.$bot_wtc_script.'\', html=\'textarea\' ', 'name = \'bot_wtc_script\'' );
 	}

	if (bot_wtc_check_install()) { // what to show when accessing tab

		$bot_wtc_script = safe_field('val', 'txp_prefs', 'name = "bot_wtc_script"'); // fetch prefs value for bot_wtc_script

		echo n.t.'<div style="margin:  auto; width:800px;">' // main div
		.'<a id="bot_expand_all" href="#" style="font-size:10px;">Expand all</a> | <a id="bot_collapse_all" href="#" style="font-size:10px;">Collapse all</a>';

		echo form( // beginning of the form
 			'<table id="bot_wtc_table" style="padding:10px 0 20px;">' // beginning of the table
			.tr(td(strong(gTxt('Item')))
			.td(strong(gTxt('Position')))
			.td(strong(gTxt('Destination')))
			.td(strong(gTxt('Hide in:')))
			.td(strong(gTxt('Class')))
			.td() // collapse all/show all)
			)
			.bot_wtc_output_rows() // html rows generated by "bot_wtc_output_rows()"
			.'</table>' // end of the table
			
            .bot_update_button()

			.n.'<div style= "padding:20px 0; border-top:dotted #ccc 1px">'  // static sections
			.n.graf('<a class="bot_push" href="#">Hide sections in sections dropdown</a>')
			.n.'<div class="bot_collapse">'
			.bot_wtc_static_sections_select()
			.n.'</div>'
			.n.'</div>'

			.bot_update_button()

			.n.'<div style="padding:20px 0; border-top:dotted #ccc 1px" id="bot_js_box">' // js code box
			.n.graf('<a class="bot_push" href="#">Additional js code</a>'.' | <a id="bot_js_link" href="#">Add external script</a> | <a id="bot_jq_link" href="#">Add Jquery script</a>')
			.n.'<div class="bot_collapse">'
			.n.'<textarea id="bot_wtc_script" name="bot_wtc_script" cols="60" rows="10" style="width:100%; border:dotted #ccc 1px;">'.$bot_wtc_script.'</textarea>' // script textarea
			.n.'</div>'
			.n.'</div>'

			.n.bot_update_button()
		);

		echo n.t.'<div style= "padding:20px 0; border-top:dotted #ccc 1px">'. // uninstall button
			n.hed(bot_wtc_gTxt('uninstall'), '1').
			n.t.t.graf(bot_wtc_gTxt('uninstall_message')).
			n.n.form(
			n.eInput('bot_wtc_tab').
			n.sInput('uninstall').
			n.n.fInput('submit', 'uninstall', 'Uninstall ', 'smallerbox'),"","confirm('".bot_wtc_gTxt('uninstall_confirm')."')"
			)
			.'</div></div>';
	}

	else { // install button
		echo n.t.'<div  style="margin:  auto; width:800px;">'.
			n.t.t.hed(gTxt('install'), '1').
			n.graf(bot_wtc_gTxt('install_message')).
			n.n.form(
				n.eInput('bot_wtc_tab').
				n.sInput('install').
				n.n.fInput('submit', 'install', 'Install ', 'publish')
				).
			'</div>';
	}

	// snippets to insert in the script box
	$bot_jquery_snippet = '<script type=\"text/javascript\">\n    $(document).ready(function() {\n        //your code here\n    });\n<\/script>\n';
	$bot_js_snippet = '<script type=\"text/javascript\" src=\"path_to_script\"><\/script>\n';

	echo // add some styles
	'<script language="javascript" type="text/javascript">'.n.
	'	$(document).ready(function() {'.n.
			'$(".bot_hilight").css("background","#eaeaea")'.n.
			'$("#bot_wtc_table td").css({padding:"10px", "white-space":"nowrap"})'.n.
			'$("div.bot_collapse").hide()'.n.
			'$("a.bot_push").css({"font-weight":"bold", background:"url(txp_img/arrowupdn.gif) no-repeat right bottom", "padding-right":"13px"}).click(function(){'.n.
			'  $(this).toggleClass("bot_arrow").parent().next().slideToggle();'.n.
			'  return false;'.n.
			'});'.n.
			'$("#bot_collapse_all").click(function(){'.n.
			'  $("div.bot_collapse").slideUp();'.n.
			'  return false;'.n.
  			 '});'.n.
			'$("#bot_expand_all").click(function(){'.n.
			'  $("div.bot_collapse").slideDown();'.n.
			'  return false;'.n.
  			 '});'.n.
			'$("#bot_wtc_table a.bot_all").click(function(){'.n.
			'  $(this).parent().parent().find("input").attr("checked", true);'.n.
			'  return false;'.n.
			'});'.n.
			'$("#bot_wtc_table a.bot_none").click(function(){'.n.
			'  $(this).parent().parent().find("input").attr("checked", false);'.n.
			'  return false;'.n.
			'});'.n.
			'$("#bot_jq_link").click(function(){'.n.
			'  var areaValue = $("#bot_wtc_script").val();'.n.
			'  $("#bot_wtc_script").val(areaValue + "'.$bot_jquery_snippet.'");'.n.
			'  return(false);'.n.
  			'});'.n.
			'$("#bot_js_link").click(function(){'.n.
			'  var areaValue = $("#bot_wtc_script").val();'.n.
			'  $("#bot_wtc_script").val(areaValue + "'.$bot_js_snippet.'");'.n.
			'  return(false);'.n.
  			'});'.n.
	'	});'.n.
	'</script>';
}



// ===========================================================
// plugins output
// ===========================================================



function bot_hide_per_section_array(){ // builds array of sections to hide

	$data_from_db = bot_wtc_fetch_db();  // array of values from the db

	for ($i =0; $i<count($data_from_db); $i++) {
		if ($data_from_db[$i]['sections']) {
		    $sections_to_hide = explode('|', $data_from_db[$i]['sections']);
		    foreach ($sections_to_hide as $section) {
				$bot_hide_per_section[$section][] = $data_from_db[$i]['item'];
			}
	    }
	}
	if (isset($bot_hide_per_section)) { // return array only if values exist
 		return $bot_hide_per_section;
 	}
}



// ===========================================================




function bot_wtc_jquery_hide_sections_rows(){ // js rows dealing with items to hide on section change AND on page load

	$bot_hide_per_section = bot_hide_per_section_array();
	foreach ($bot_hide_per_section as $section => $fields) {
		echo n.'			if (value=="'.$section.'"){'.n;
        for ($i =0; $i<count($fields); $i++) {
			echo '				'.$fields[$i].'.hide();'.n;
        }
		echo '			}'.n;
	}
}



// ===========================================================



function bot_wtc_jquery_restore_rows(){ // js rows to restore every previously hidden item on section change

	$bot_hide_per_section = bot_hide_per_section_array();
	foreach ($bot_hide_per_section as $section => $fields) {
        for ($i =0; $i<count($fields); $i++) {
			$out[] = $fields[$i];
        }
	}
	$out = array_unique($out);
	foreach ($out as $value) {
	echo '			'.$value.'.show();'.n;
	}
}



// ===========================================================



function bot_hide_per_section(){ //  builds the script

    $bot_hide_per_section = bot_hide_per_section_array();
	if ($bot_hide_per_section) { // output js only if values exist
		 	echo
				'<script language="javascript" type="text/javascript">'.n.
				'	$(document).ready(function() {'.n.
				'		$("#advanced").show();'.n.
				'		var value = $("select#section.list option:selected").val();';
						bot_wtc_jquery_hide_sections_rows();
			echo
				'		$("select#section.list").change(function(){'.n;
							bot_wtc_jquery_restore_rows();
			echo
				'			var value = $("select#section.list").val();';
							bot_wtc_jquery_hide_sections_rows();
			echo
				'		});'.n.
				'	});'.n.
				'</script>';
		}
	}



// ===========================================================



function bot_hidden_sections(){ // invisible sections in section list

	$bot_hidden_sections = safe_field('val', 'txp_prefs', 'name = "bot_wtc_static_sections"'); // fetch prefs value for bot_wtc_static_sections
	if ($bot_hidden_sections) { // output js only if values exist
		$sections = explode("|", $bot_hidden_sections);
		echo
		'<script language="javascript" type="text/javascript">'.n.
		'	$(document).ready(function() {'.n;
		foreach ($sections as $value) {
		  echo    '           $("select#section.list option:not(:selected)").filter(function() {'.n.
                '               return $(this).text() == "'.$value.'";'.n.
                '               }).remove();'.n;
		}
		echo
		'	});'.n.
		'</script>';
	}
}



// ===========================================================



function bot_wtc_jquery_rows()
{
	global $bot_items;
	$data_from_db = bot_wtc_fetch_db();  // array of values from the db

	$rows = '';
	for ($i = 0; $i < count($data_from_db); $i++)
	{
		$item = ($data_from_db[$i]['item'] != '') ? $data_from_db[$i]['item'] : '';
		$position = ($data_from_db[$i]['position'] != '') ? '.'.$data_from_db[$i]['position'] : '';
		$destination = ($data_from_db[$i]['destination'] != '') ? '('.$data_from_db[$i]['destination'].')' : '';
		$class = ($data_from_db[$i]['class'] != '') ? '.addClass("'.$data_from_db[$i]['class'].'");' : '';
  		$row = $item.$position.$destination.$class.n;
		$rows .= $row;
	}
	return $rows;
};



// ===========================================================



function bot_wtc()
{

	$bot_wtc_script = safe_field('val', 'txp_prefs', 'name = "bot_wtc_script"'); // fetch prefs value for bot_wtc_script
 	$position = safe_column('position', 'bot_wtc', '1=1'); // fetch 'position' from db to check if a move is saved
 	$class = safe_column('class', 'bot_wtc', '1=1'); // fetch 'class' from db to check if a class is saved

	if(isset($position) || isset($class)){ // output code only if a preference is saved
		echo
		'<script language="javascript" type="text/javascript">'.n.
		'	$(document).ready(function() {'.n.
				bot_wtc_jquery_rows().n.
		'	});'.n.
		'</script>';
	}
	if ($bot_wtc_script) {
		echo n.$bot_wtc_script.n;
	};

}

# --- END PLUGIN CODE ---
if (0) {
?>
<!--
# --- BEGIN PLUGIN HELP ---
<h2>bot | write_tab_customize help </h2>
<p>This plugin allows to  rearrange and style items in the write tab, hide them on a per section basis and remove sections from the &quot;write&quot; tab sections dropdown. Used alone or togheter with other plugins (ied_hide_in_admin, glz_custom_fields etc.) can really ease txp admin customization. </p>
<p>Note that the rearrange capability    is mainly intended to be  an intermediate step in the &quot;write&quot; tab customization process. It takes care of page elements that cannot be  moved or targeted   with simple css. You are so encouraged to fine-tune your customization  by modifying  the &quot;textpattern.css&quot; file.</p>
<h3>Features</h3>
<ul>
  <li>Single items (custom fields, body, excerpt etc.) can be moved around relative to other single items</li>
  <li>Columns can be moved around relative to other columns </li>
  <li>Columns and single items cannot be moved relative to each other to avoid messing with the underlying html structure</li>
  <li>Items can be hidden on a per section basis</li>
  <li>A custom css class can be set for each item. This let's you define  classes for items that normally couldn't be targeted with simple css (i.e. a p surrounding a specific custom field and his label)</li>
  <li>Sections can be removed from the &quot;write&quot; tab sections dropdown (for static sections like &quot;about us&quot; or &quot;search&quot;) </li>
  <li>Javascript code can be set directly throught the plugin interface. Particularly useful  for use with an external jquery plugin and in conjunction with the ability to add a css class to any item on the page. The script will be executed only in the &quot;write&quot; tab </li>
  <li>glz_custom_fields compatible</li>
</ul>
<h3>A simple example</h3>
<p>Suppose you want a giant  coloured round-cornered section drop-down as the first item at the top left corner.<br />
Quite easy: first set the rule &quot;section dropdown before textilehelp&quot;, then add one (or more)  custom class to the item, let's say &quot;big rounded&quot; (note multiple classes can be set). Now  write some rules in your textpattern.css like:</p>
<p><code>.big{background:#c00 url(images/my_background.gif); color:#fff; font-size:14px; font-weight:bold;}</code></p>
<p>and finally, if you want to use a jquery plugin for the rounded corners, write something like this in the js box:</p>
<p><code>&lt;script type=&quot;text/javascript&quot; src= &quot;../js/your_jquery_rounded_corners_plugin.js&quot;&gt;&lt;/script&gt;<br />
&lt;script language=&quot;javascript&quot; type=&quot;text/javascript&quot;&gt;<br />
$(document).ready(function() {<br />
$(&quot;rounded&quot;).your_jquery_rounded_corners_plugin();<br />
});<br />
&lt;/script&gt; </code></p>
<p>and you are done. <br />
The same principle applies if you want to apply other js behavieur (i.e. collapsible menu): first set a class for the elements you want to target and then write the appropriate Jquery (or plain js) code. </p>
<h3>Notes</h3>
<ul>
  <li>The order in which the rules for moving items are inserted does matter. Rules execution goes from top to bottom so in case the sequence gets garbled it's advisable to delete all and start over </li>
  <li>Class names must be inserted without the dot</li>
  <li>In a standard txp installation it isn't  necessary to call the Jquery library (it's loaded by default by txp) </li>
</ul>
<h3>Instructions</h3>
<p>Once inatalled and activated visit: &quot;extensions&quot; &gt; &quot;bot_wtc&quot;. Choose an item, choose &quot;before&quot; or &quot;after&quot; and choose another item. </p>
# --- END PLUGIN HELP ---
-->
<?php
}
?>
<?php
/*******************************************************************************
* Cumulus Congestus © 5771, Bugo											   *
********************************************************************************
* Subs-Cumulus.php															   *
********************************************************************************
* License http://creativecommons.org/licenses/by-nc-nd/3.0/deed.ru CC BY-NC-ND *
* Support and updates for this software can be found at	http://dragomano.ru    *
*******************************************************************************/

if (!defined('SMF'))
	die('Hacking attempt...');
	
// Main function
function cumulus()
{
	global $modSettings, $boarddir, $smcFunc, $context, $txt, $settings;
	
	loadLanguage('Cumulus');

	$modSettings['cumulus_count'] = !empty($modSettings['cumulus_count']) ? $modSettings['cumulus_count'] : 15;
	$modSettings['cumulus_width'] = !empty($modSettings['cumulus_width']) ? $modSettings['cumulus_width'] : 400;
	$modSettings['cumulus_height'] = !empty($modSettings['cumulus_height']) ? $modSettings['cumulus_height'] : 150;
	$modSettings['cumulus_bgcolor'] = !empty($modSettings['cumulus_bgcolor']) ? $modSettings['cumulus_bgcolor'] : 'E9EBEC';
	$modSettings['cumulus_bgtrans'] = !empty($modSettings['cumulus_bgtrans']) ? true : false;
	$modSettings['cumulus_tcolor'] = !empty($modSettings['cumulus_tcolor']) ? $modSettings['cumulus_tcolor'] : '000000';
	$modSettings['cumulus_tcolor2'] = !empty($modSettings['cumulus_tcolor2']) ? $modSettings['cumulus_tcolor2'] : '000099';
	$modSettings['cumulus_hicolor'] = !empty($modSettings['cumulus_hicolor']) ? $modSettings['cumulus_hicolor'] : 'ff0000';
	$modSettings['cumulus_maxfont'] = !empty($modSettings['cumulus_maxfont']) ? $modSettings['cumulus_maxfont'] : 22;
	$modSettings['cumulus_minfont'] = !empty($modSettings['cumulus_minfont']) ? $modSettings['cumulus_minfont'] : 8;
	$modSettings['cumulus_tspeed'] = !empty($modSettings['cumulus_tspeed']) ? $modSettings['cumulus_tspeed'] : 60;
	$modSettings['cumulus_distr'] = !empty($modSettings['cumulus_distr']) ? true : false;
	$modSettings['cumulus_cyrillic'] = !empty($modSettings['cumulus_cyrillic']) ? true : false;

	if (file_exists($boarddir . '/SSI.php'))
		require_once($boarddir . '/SSI.php');
	else return '<div class="information error">' . $txt['cumulus_ssi_not_found'] . '</div>';
	
	// Number of topics to display
	$num = $modSettings['cumulus_count'];
	
	if (!empty($modSettings['cumulus_boards']))
	{
		global $scripturl;
		
		$include_boards = explode(",", preg_replace("/[^0-9,]/", "", $modSettings['cumulus_boards']));
		foreach ($include_boards as $key => $value)
			if ($value == "")
				unset($include_boards[$key]);
	
		$request = $smcFunc['db_query']('', '
			SELECT t.id_topic
			FROM {db_prefix}boards AS b
				INNER JOIN {db_prefix}topics AS t ON (b.id_board = t.id_board)
			WHERE t.id_board IN ({array_int:include_boards})
			ORDER BY t.id_topic DESC
			LIMIT {int:limit}',
			array(
				'include_boards' => $include_boards,
				'limit' => $num,
			)
		);
		$id_topics = array();
		while ($row = $smcFunc['db_fetch_assoc']($request)) {
			$id_topics[] = $row['id_topic'];
		}
		$smcFunc['db_free_result']($request);

		$request = $smcFunc['db_query']('', '
			SELECT m.subject, t.num_views, t.num_replies, t.id_topic
			FROM {db_prefix}topics AS t
				INNER JOIN {db_prefix}messages AS m ON (m.id_msg = t.id_first_msg)
			WHERE t.id_topic IN ({array_int:topic_list})
			LIMIT {int:limit}',
			array(
				'topic_list' => $id_topics,
				'limit' => $num,
			)
		);
		$topics = array();
		while ($row = $smcFunc['db_fetch_assoc']($request))
		{
			censorText($row['subject']);

			$topics[] = array(
				'id' => $row['id_topic'],
				'subject' => $row['subject'],
				'num_replies' => $row['num_replies'],
				'num_views' => $row['num_views'],
				'href' => $scripturl . '?topic=' . $row['id_topic'] . '.0',
			);
		}
		$smcFunc['db_free_result']($request);
	}
	else
	{
		$topics = !empty($modSettings['cumulus_variant']) ? ssi_topTopicsReplies($num, 'array') : ssi_topTopicsViews($num, 'array');
	}
	
	$urls = array();

	foreach ($topics as $topic)
	{
		$size = $topic['num_views'];
		if ($size < $modSettings['cumulus_minfont']) $size = $modSettings['cumulus_minfont'];
		if ($size > $modSettings['cumulus_maxfont']) $size = $modSettings['cumulus_maxfont'];
		$urls[] = '<a href="' . $topic['href'] . '" style="' . $size . '">' . $topic['subject'] . '</a>';
	}

	$tags = implode('', $urls);

	if (!empty($modSettings['cumulus_cyrillic']) && function_exists('iconv'))
		$tags = iconv("CP1251","UTF-8", $tags);
		
	$context['cumulus'] = '';

	// Top area
	switch ($modSettings['cumulus_style'])
	{

		case 1: // Curve
			$context['cumulus'] = '
			<div class="title_barIC">
				<h4 class="titlebg">
					<span class="ie6_header floatleft">
						<img class="icon" src="' . $settings['images_url'] . '/stats_replies.gif" alt="' . $txt['cumulus_title'] . '" />
						' . ($num == 1 ? $txt['cumulus_single'] : $txt['cumulus_title']) . '
					</span>
				</h4>
			</div>
			<div id="cumulus">';
		break;
		
		case 2: // BlocWeb
			$context['cumulus'] = '
			<div class="title_bar">
				<h4 class="titlebg">
					' . ($num == 1 ? $txt['cumulus_single'] : $txt['cumulus_title']) . '
				</h4>
			</div>
		<div class="widgetbox">';
		break;
		
		case 3: // Core
			$context['cumulus'] = '
			<div class="infocenter_section">
				<h4 class="titlebg">' . ($num == 1 ? $txt['cumulus_single'] : $txt['cumulus_title']) . '</h4>
				<div class="windowbg">
					<p class="section">
						<img src="' . $settings['images_url'] . '/stats_replies.gif" alt="' . $txt['cumulus_title'] . '" />
					</p>
					<div class="sectionbody windowbg2 smalltext">';
		break;
		
		case 4: // Statistics in jQuery (mod)
			$modSettings['cumulus_bgcolor'] = 'E7EAEF';
			$modSettings['cumulus_bgtrans'] = false;
			$context['cumulus'] = '
			<div id="tab5" class="tab_content">
				<div id="cumulus">';
		break;
		
	}
	
	// Main area
	$context['cumulus'] .= '
				<script type="text/javascript" src="' . $settings['default_theme_url'] . '/scripts/swfobject.js"></script>
				<div id="flashcontent" style="padding: 1px; margin: 0 auto;">' . $txt['cumulus_alert'] . '</div>
				<script type="text/javascript"><!-- // --><![CDATA[
					var cl = new SWFObject("' . $settings['default_theme_url'] . '/scripts/tagcloud.swf", "tagcloud", "100%", "' . $modSettings['cumulus_height'] . '", "9", "#' . $modSettings['cumulus_bgcolor'] . '");';

	if (!empty($modSettings['cumulus_bgtrans']))
		$context['cumulus'] .= '
					cl.addParam("wmode", "transparent");';
		
	$context['cumulus'] .= '
					cl.addVariable("tcolor", "0x' . $modSettings['cumulus_tcolor'] . '");
					cl.addVariable("tcolor2", "0x' . $modSettings['cumulus_tcolor2'] . '");
					cl.addVariable("hicolor", "0x' . $modSettings['cumulus_hicolor'] . '");
					cl.addVariable("mode", "tags");
					cl.addVariable("distr", "' . $modSettings['cumulus_distr'] . '");
					cl.addVariable("tspeed", "' . $modSettings['cumulus_tspeed'] . '");
					cl.addVariable("tagcloud", "<tags>' . urlencode($tags) . '</tags>");
					cl.write("flashcontent");
				// ]]></script>';
	
	// Bottom area
	switch ($modSettings['cumulus_style'])
	{
		case 1: // Curve
			$context['cumulus'] .= '
			</div>';
		break;
		
		case 2: // BlocWeb
			$context['cumulus'] .= '
		</div>';
		break;
		
		case 3: // Core
			$context['cumulus'] .= '
					</div>
				</div>
			</div>';
		break;
		
		case 4: // Statistics in jQuery (mod)
			$context['cumulus'] .= '
				</div>
			</div>';
		break;
	}
	
	return $context['cumulus'];	
}

// Modification admin area
function cumulus_admin_area(&$admin_areas)
{
	global $txt;
	
	loadLanguage('Cumulus');
		
	$admin_areas['config']['areas']['modsettings']['subsections']['cumulus'] = array($txt['cumulus_title']);
}

function cumulus_modification(&$subActions)
{
	$subActions['cumulus'] = 'cumulus_settings';
}

function cumulus_buffer(&$buffer)
{
	global $options, $modSettings, $txt;
	
	$search = '<div id="upshrinkHeaderIC"' . (empty($options['collapse_header_ic']) ? '' : ' style="display: none;"') . '>';
	$default = $search . (!empty($modSettings['cumulus_show']) ? cumulus() : '');
	
	if (!empty($modSettings['cumulus_show']) && $modSettings['cumulus_style'] == 4)
	{
		$search = '</ul>' . "\n\t   " . '<div class="tab_container">';
		$default = "\n\t\t\t" . '<li><a href="#tab5">' . $txt['cumulus_title'] . '</a></li>' . "\n\t\t";
		$default = $default . $search . cumulus();
	}
	
	$replace = $default;
	
	return (isset($_REQUEST['xml']) ? $buffer : str_replace($search, $replace, $buffer));	
}

// Modification page settings
function cumulus_settings()
{
	global $context, $txt, $scripturl, $settings, $modSettings, $db_character_set;
	
	loadLanguage('Cumulus');
	
	$context['page_title'] = $txt['cumulus_title'];
	$context['settings_title'] = $txt['mods_cat_features'];
	$context['post_url'] = $scripturl . '?action=admin;area=modsettings;save;sa=cumulus';
	$context[$context['admin_menu_name']]['tab_data']['tabs']['cumulus'] = array('description' => $txt['cumulus_desc']);
	$watch = '<img src="' . $settings['images_url'] . '/warning_watch.gif" alt="" />';
	$mute = '<img src="' . $settings['images_url'] . '/warning_mute.gif" alt="" />';
	
	$config_vars = array(
		array('check', 'cumulus_show', 'postinput' => !empty($modSettings['cumulus_show']) ? $watch : $mute),
		array('text', 'cumulus_boards', '6" style="width:20%'),
		array('select', 'cumulus_variant', explode("|", $txt['cumulus_variants'])),
		array('int', 'cumulus_count'),
		array('select', 'cumulus_style', explode("|", $txt['cumulus_styles'])),
		//array('int', 'cumulus_width', 'postinput' => 'px'),
		array('int', 'cumulus_height', 'postinput' => 'px'),
		array('text', 'cumulus_bgcolor', 6, 'preinput' => '#', 'postinput' => &$txt['cumulus_number_postfix']),
		array('check', 'cumulus_bgtrans', 'postinput' => !empty($modSettings['cumulus_bgtrans']) ? $watch : $mute),
		array('text', 'cumulus_tcolor', 6, 'preinput' => '#', 'postinput' => &$txt['cumulus_number_postfix']),
		array('text', 'cumulus_tcolor2', 6, 'preinput' => '#', 'postinput' => &$txt['cumulus_number_postfix']),
		array('text', 'cumulus_hicolor', 6, 'preinput' => '#', 'postinput' => &$txt['cumulus_number_postfix']),
		array('int', 'cumulus_maxfont', 'postinput' => 'pt'),
		array('int', 'cumulus_minfont', 'postinput' => 'pt'),
		array('int', 'cumulus_tspeed'),
	);
	
	if (function_exists('iconv') && $db_character_set != 'utf8')
		$config_vars[] = array('check', 'cumulus_cyrillic', 'postinput' => !empty($modSettings['cumulus_cyrillic']) ? $watch : $mute);
	
	// Saving?
	if (isset($_GET['save'])) {
		checkSession();
		saveDBSettings($config_vars);
		redirectexit('action=admin;area=modsettings;sa=cumulus');
	}
	
	prepareDBSettingContext($config_vars);
}

?>
<?php
/**********************************************************************************
* BadBehavior-Admin.php - PHP template for Bad Behavior mod
* Version 1.4.5 by JMiller a/k/a butchs
* (http://www.eastcoastrollingthunder.com) 
*********************************************************************************
* This program is distributed in the hope that it is and will be useful, but
* WITHOUT ANY WARRANTIES; without even any implied warranty of MERCHANTABILITY
* or FITNESS FOR A PARTICULAR PURPOSE.
**********************************************************************************/

if (!defined('SMF'))
	die('Hacking attempt...');

	global $bb2_settings_defaults, $context, $boardurl, $modSettings, $webmaster_email;
	global $db_prefix, $smcFunc, $sourcedir, $settings, $badbehavior_settings;


// Admin initializer
function badbehavior_admin_init($return_config = false)
{
	global $txt, $scripturl, $context, $settings, $sc, $modSettings;
	global $smcFunc, $sourcedir;

	require_once($sourcedir . '/ManageMembers.php');
	require_once($sourcedir . '/Security.php');

	if (empty($_REQUEST['sa']))
		$_REQUEST['sa'] = '';

	$config_vars = array(
	);

	if ($return_config)
		return $config_vars;

	isAllowedTo('admin_forum');

	 loadTemplate('BadBehavior_Admin');
	 loadLanguage('BadBehavior');

	$context['sub_template'] = 'show_settings';
	$context['page_title'] = $txt['badbehavior_config'];
	$context['sub_action'] = $_REQUEST['sa'];

	$context[$context['admin_menu_name']]['tab_data'] = array(
		'title' => &$txt['badbehavior_admin'],
		'description' => $txt['badbehavior_admin_desc'],
		'tabs' => array(
			'badbehavior_settings' => array(
				'description' => $txt['badbehavior_settings_desc'],
				'href' => $scripturl . '?action=admin;area=badbehavior;sa=settings',
				'is_selected' => $_REQUEST['sa'] == 'badbehavior_settings',
			),
			'report_all' => array(
				'description' => $txt['badbehavior_reports_desc'],
				'href' => $scripturl . '?action=admin;area=badbehavior;sa=report_all',
				'is_selected' => $_REQUEST['sa'] == 'report_all',
			),
			'report_permit' => array(
				'description' => $txt['badbehavior_reports_desc'],
				'href' => $scripturl . '?action=admin;area=badbehavior;sa=report_permit',
				'is_selected' => $_REQUEST['sa'] == 'report_permit',
			),
			'report_denied' => array(
				'description' => $txt['badbehavior_reports_desc'],
				'href' => $scripturl . '?action=admin;area=badbehavior;sa=report_denied',
				'is_selected' => $_REQUEST['sa'] == 'report_denied',
			),
			'badbehavior_about' => array(
				'description' => $txt['badbehavior_about_desc'],
				'href' => $scripturl . '?action=admin;area=badbehavior;sa=about',
				'is_selected' => $_REQUEST['sa'] == 'badbehavior_about',
			),
		),
	);

	$subActions = array(
		'badbehavior_settings' => 'badbehavior_admin_settings',
		'report_all' => 'badbehavior_admin_reports',
		'report_permit' => 'badbehavior_admin_reports',
		'report_denied' => 'badbehavior_admin_reports',
		'badbehavior_about' => 'badbehavior_admin_about',
	);

	// By default go to the settings.
	$_REQUEST['sa'] = isset($_REQUEST['sa']) && isset($subActions[$_REQUEST['sa']]) ? $_REQUEST['sa'] : 'badbehavior_settings';

	// Call the function for the sub-acton.
	$subActions[$_REQUEST['sa']]();
}

function badbehavior_admin_settings($return_config = false)
{
	global $smcFunc, $txt, $scripturl, $context, $sourcedir, $modSettings, $db_prefix;
	global $badbehavior_settings;

	require_once($sourcedir.'/ManageServer.php');

	loadLanguage('BadBehavior');

	$config_vars = array(
		array('check', 'enable_badbehavior'),
	    '',
		$txt['badbehavior_stats_title'],
	    '',
		array('check', 'badbehavior_display_stats'),
	    '',
		$txt['badbehavior_logging_title'],
	    '',
		array('check', 'badbehavior_verbose'),
		array('check', 'badbehavior_logging'),
	    '',
		$txt['badbehavior_security'],
	    '',
		array('check', 'badbehavior_strict'),
		array('check', 'badbehavior_offsite_forms'),
	    '',
		$txt['badbehavior_httpbl'],
	    '',
		array('text', 'badbehavior_httpbl_key', '14'),
		array('text', 'badbehavior_httpbl_threat','4'),
		array('text', 'badbehavior_httpbl_maxage','4'),
		array('large_text', 'badbehavior_httpbl_link'),
		array('text', 'badbehavior_httpbl_word'),
 		'',
		$txt['badbehavior_reverse_load'],
	    '',
		array('check', 'badbehavior_reverse_proxy'),
		array('text', 'badbehavior_reverse_proxy_header', '25'),
		array('large_text', 'badbehavior_reverse_proxy_addresses'),
	    '',
		$txt['badbehavior_ooptions'],
	    '',
		array('check', 'badbehavior_email_allow'),
		array('check', 'badbehavior_roundtripdns'),
		array('int', 'badbehavior_cache_duration', '2', 'badbehavior_cache_duration'),
		array('check', 'badbehavior_block_ua'),
		);

	if ($return_config)
		return $config_vars;

	$context['post_url'] = $scripturl .'?action=admin;area=badbehavior;save;sa=settings';
	$context['page_title'] = $txt['badbehavior_settings_sub'];
	loadTemplate('BadBehavior_Admin');
	$context['sub_template'] = 'show_settings';

	if (isset($_GET['save']))
	{
		checkSession();
		saveDBSettings($config_vars);
		if (!function_exists('bb2_read_settings'))
			require_once($sourcedir . '/bad-behavior/BadBehavior-SMF.php');
		$badbehavior_settings = bb2_read_settings();
		redirectexit('action=admin;area=badbehavior');
	}

	prepareDBSettingContext($config_vars);
}

function badbehavior_admin_about()
{
	global $txt, $context;

	isAllowedTo('admin_forum');

	$context['sub_template'] = 'badbehavior_about';
	$context['page_title'] = $txt['badbehavior_about_title'];

}

function badbehavior_admin_reports()
{
	global $smcFunc, $context, $txt, $page_num, $scripturl;

	isAllowedTo('admin_forum');

	if (empty($_REQUEST['bbid']))
		$_REQUEST['bbid'] = '';

	$context['bbid'] = $_REQUEST['bbid'];

	if ($context['bbid'] != '') {
		badbehavior_event();

	} else {
	$sort_columns = array(
		'date' => 'date',
		'request_uri' => 'request_uri',
		'key' => 'key',
	);

	$page_num = 0;
	$per_page = 30;

	if ($context['sub_action'] == 'report_permit')
		$where = "bb.key = {string:selected_key}";
	if ($context['sub_action'] == 'report_denied')
		$where = "bb.key NOT LIKE {string:selected_key}";
	if ($context['sub_action'] == 'report_all')
		$where = "bb.id >= 0";

	$_REQUEST['start'] = empty($_REQUEST['start']) || $_REQUEST['start'] < 0 ? 0 : (int) $_REQUEST['start'];

	if (empty($_REQUEST['sort_by']) || !isset($sort_columns[$_REQUEST['sort_by']])) {
		$_REQUEST['sort_by'] = 'date';
		$_REQUEST['desc'] = true; }

	$context['order_by'] = isset($_REQUEST['desc']) ? 'down' : 'up';
	$context['sort_by'] = $_REQUEST['sort_by'];

	$context['start'] = $_REQUEST['start'];

	$sort_by = $sort_columns[$context['sort_by']];
	$order_by = (isset($_REQUEST['desc']) ? ' desc' : 'asc');

	$result = bb2_db_manage($where, $page_num, $per_page, $sort_by, $order_by);

	$context['page_index'] = constructPageIndex($scripturl . '?action=admin;area=badbehavior;sa='.$context['sub_action'].';sort_by=' . $context['sort_by']. ($context['order_by'] == 'down' ? ';desc' : ''), $_REQUEST['start'], $context['bb2_per_page'], $per_page);

	$page_num = $result[3];

	$context['sub_template'] = 'badbehavior_reports';
	}

	$context['description'] = $txt['badbehavior_reports_desc'];

	if ($context['sub_action'] == 'report_permit')
		$context['settings_title'] = $txt['badbehavior_report_permit_title'];
	if ($context['sub_action'] == 'report_denied')
		$context['settings_title'] = $txt['badbehavior_report_denied_title'];
	if ($context['sub_action'] == 'report_all')
		$context['settings_title'] = $txt['badbehavior_report_all_title'];
}

function bb2_db_manage($where = "bb.id >= 0", $page_num=1, $per_page=5, $sort_by='date', $order_by='desc') {

	global $smcFunc, $context;

	$limit_start = '';
	$context['badbehavior_log'] = array();
	
	if ($page_num < 1) {
		$page_num = 1; }
	if ($per_page < 1) {
		$per_page = 1; }
  if ($sort_by !== 'date' && $sort_by !== 'request_uri' && $sort_by !== 'key') {
		$sort_by='date'; }
	if ($order_by !== 'asc' && $order_by !== 'desc') {
		$order_by='desc'; }

	$total_result = $smcFunc['db_query']('', '
		SELECT COUNT(*)
		FROM {db_prefix}log_badbehavior AS bb
		WHERE ' . $where,
		array(
			'selected_key' => '00000000',
		)
	);

	list ($total_count) = $smcFunc['db_fetch_row']($total_result);
	$smcFunc['db_free_result']($total_result);

	$limit_start = (isset($context['start'])? $context['start'] : ($limit_start = ($page_num * $per_page) - $per_page));
	if ($limit_start > $total_count) {
		$limit_start = $total_count-1; }

	if ($per_page > $total_count) {
		$per_page = $total_count; }
	
	$limit_end = $limit_start + $per_page;
	
	if ($limit_end > $total_count) {
		$limit_end = $total_count; }

	//Make sure the page number requested actually exists
	for ($i=$page_num; $i>1; $i--) {
		if ( ( ($page_num * $per_page) - $per_page) > $total_count) {
			$page_num--; } }

	$qresult = $smcFunc['db_query']('', '
		SELECT *
		FROM {db_prefix}log_badbehavior AS bb
		WHERE ' . $where . '
		ORDER BY {identifier:sort} {raw:order}
		LIMIT {int:offset}, {int:items_per_page}',
		array(
			'selected_key' => '00000000',
			'sort' => $sort_by,
			'order' => $order_by,
			'offset' => $limit_start,
			'items_per_page' => $per_page,
		)
	);

	while($row = $smcFunc['db_fetch_assoc']($qresult)) {

		//This field can be blank alot, so put a space in it.
		if (empty($row['request_entity']))
			$row['request_entity'] = '&nbsp;';

		//  Load DB into array
		$context['badbehavior_log'][] = array(
			'bbid'  => $row['id'],
			'ip'  => $row['ip'],
			'date'  => $row['date'],
			'request_uri'  => $row['request_uri'],
			'key'  => $row['key'],
		);
	}

	$smcFunc['db_free_result']($qresult);

    $limit_start++;	

	$context['bb2_where'] = $limit_start;
	$context['bb2_page_num'] = $limit_end;
	$context['bb2_per_page'] = $total_count;

	$return_array = array($limit_start, $limit_end, $total_count, $page_num);

return $return_array;
}

function badbehavior_event()
{
	global $sourcedir, $context, $smcFunc, $language;

	$type = $where = $httpbl = '';
	$response = array();

	isAllowedTo('admin_forum');

	$context['bbid'] = $_REQUEST['bbid'];

	$where = "bb.id = {int:selected_id}";

	$qresult = $smcFunc['db_query']('', '
		SELECT *
		FROM {db_prefix}log_badbehavior AS bb
		WHERE ' . $where,
		array(
			'selected_id' => $context['bbid'],
		)
	);

	$row = $smcFunc['db_fetch_assoc']($qresult);

		$context['badbehavior_log'][] = array(
			'bbid'  => $row['id'],
			'ip'  => (empty($row['ip']) ? '' : $row['ip']),
			'date'  => $row['date'],
			'request_method'  => rawurlencode($row['request_method']),
			'request_uri'  => $row['request_uri'],
			'server_protocol'  => $row['server_protocol'],
			'http_headers'  => htmlspecialchars($row['http_headers']),
			'user_agent'  => rawurlencode($row['user_agent']),
			'request_entity'  => htmlspecialchars($row['request_entity']),
			'key'  => $row['key'],
		);
	if (!function_exists('bb2_get_response')) {
		if (!defined('BB2_CORE')) define('BB2_CORE', dirname(__FILE__));
		if (file_exists($sourcedir . '/bad-behavior/bad-behavior/responses.inc.' . $language . '.php'))
			require_once($sourcedir . '/bad-behavior/bad-behavior/responses.inc.' . $language . '.php');
		else
			require_once($sourcedir . '/bad-behavior/bad-behavior/responses.inc.php');
	}
	$response = bb2_get_response($row['key']);
	if (!empty($response['log'])) {
		$context['log'] = $response['log'];
		$context['explanation'] = $response['explanation'].(($row['key'] == '2b021b1f') ? "<br />".bb2_httpbl_lookup($row["ip"]) : "");
		$context['response'] = $response['response'];
	} else {
		$context['log'] = $context['explanation'] = $context['response'] = '';
	}

	unset($response);
	$smcFunc['db_free_result']($qresult);

	$context['sub_template'] = 'badbehavior_event';
}

//  Must be forced on via db edit
function bb2_httpbl_lookup($ip) {
	global $txt;
	// NB: Many of these are defunct

	$settings = bb2_read_settings();
	$httpbl_key = $settings['httpbl_key'];
	if (!isset($httpbl_key) || empty($httpbl_key)) return false;

	$engines = array(
		1 => $txt['badbehavior_engines1'],
		2 => $txt['badbehavior_engines2'],
		3 => $txt['badbehavior_engines3'],
		4 => $txt['badbehavior_engines4'],
		5 => $txt['badbehavior_engines5'],
		6 => $txt['badbehavior_engines6'],
		7 => $txt['badbehavior_engines7'],
		8 => $txt['badbehavior_engines8'],
		9 => $txt['badbehavior_engines9'],
		10 => $txt['badbehavior_engines10'],
		11 => $txt['badbehavior_engines11'],
		12 => $txt['badbehavior_engines12'],
	);

	$r = (isset($_SESSION['badbehavior_httpbl'][$ip]) ? $_SESSION['badbehavior_httpbl'][$ip] : "");
	$d = "";
	if (!$r) {	// Lookup
		$find = implode('.', array_reverse(explode('.', $ip)));
		$result = gethostbynamel("${httpbl_key}.${find}.dnsbl.httpbl.org.");
		if (!empty($result)) {
			$r = $result[0];
			$_SESSION['badbehavior_httpbl'][$ip] = $r;
		}
	}
	if ($r) {	// Interpret
		$ip = explode('.', $r);
		if ($ip[0] == 127) {
			if ($ip[3] == 0) {
				if ($engines[$ip[2]]) {
					$d .= $engines[$ip[2]];
				} else {
					$d .= $txt['badbehavior_search_engine']."${ip[2]}<br />";
				}
			}
			if ($ip[3] & 1) {
				$d .= $txt['badbehavior_suspicious']."<br />";
			}
			if ($ip[3] & 2) {
				$d .= $txt['badbehavior_harvester']."<br />";
			}
			if ($ip[3] & 4) {
				$d .= $txt['badbehavior_comment_spammer']."<br />";
			}
			if ($ip[3] & 7) {
				$d .= $txt['badbehavior_threat_level']."${ip[2]}<br />";
			}
			if ($ip[3] > 0) {
				$d .= $txt['badbehavior_age']."${ip[1]}".$txt['badbehavior_days']."<br />";
			}
		}
	}
	return $d;
}
?>
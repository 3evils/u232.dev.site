<?php
/**********************************************************************************
* BadBehavior-SMF.php - PHP template for Bad Behavior mod
* Version 1.4.0 by JMiller a/k/a butchs
* (http://www.eastcoastrollingthunder.com) 
*********************************************************************************
* This program is distributed in the hope that it is and will be useful, but
* WITHOUT ANY WARRANTIES; without even any implied warranty of MERCHANTABILITY
* or FITNESS FOR A PARTICULAR PURPOSE.
**********************************************************************************/

###############################################################################
###############################################################################
if (!defined('SMF')) die('Hacking attempt...');

$bb2_mtime = explode(" ", microtime());
$bb2_timer_start = $bb2_mtime[1] + $bb2_mtime[0];

define('BB2_CWD', dirname(__FILE__));

	global $bb2_settings_defaults, $badbehavior_settings, $context, $boardurl;
	global $db_prefix, $smcFunc, $sourcedir, $settings, $modSettings, $webmaster_email;
	global $user_info, $language, $user_settings;

// Bad Behavior callback functions.
require_once($sourcedir . '/BadBehavior-mysql.php');

// Settings you can adjust for Bad Behavior.
$bb2_settings_defaults = array();
$badbehavior_settings = array();

// Return current timestamp in the format preferred by your database.
function bb2_db_date() {
	return date('Y-m-d H:i:s');	// Example is MySQL format good for SMF
}

// Return affected rows from most recent query.
function bb2_db_affected_rows() {
	global $smcFunc;

	return $smcFunc['db_affected_rows']();
}

// Escape a string for database usage
function bb2_db_escape($string) {
//	Not required for SMF 2.0+
 return $string;
}

// Return the number of rows in a particular query.
function bb2_db_num_rows($link) {
	global $smcFunc;
	return $smcFunc['db_num_rows']($link);
}

function badbehavior_db_errortrap($errno, $string) {
	log_error($errno . ' ' . $string, 'user');
}

// Run a query and return the results, if any.
// Should return FALSE if an error occurred.
// Bad Behavior will use the return value here in other callbacks.
function bb2_db_query($query) {
	global $smcFunc;

	if (empty($query))  //  This really is not a query
		return;

	$link = $smcFunc['db_query']($query);

	if (!$link) { //If it's 0/FALSE then there was some kind of error
		return false; //Return false if there is an error
  	}
	if ($link === TRUE) { //If it's exactly TRUE then it was a succesful WRITE operation
		$affected_rows = bb2_db_affected_rows(); //how many affected rows in a WRITE query?
		if ($affected_rows >= 1) {
			return true; //Something was succesfully written
		} else {
		return false; //Nothing was written
		}  
	} else { //If it's not 0/FALSE and it's not exactly TRUE then it was a READ operation
		$number_of_rows = bb2_db_num_rows($link); //number of rows read the READ query?
		if ($number_of_rows == '0') {
			return false; //No rows were found for query
		}
	}

	$result = bb2_db_rows($link); //Go get all the rows and put them an array

	return $result;
}

function badbehavior_checkCache($ip2) {

	if ($ip2 == '') return false;

	$cache_content = $stamp = $bb_salt = '';
	$stamp = date('Ymd');
	$bb_salt = 'j&9N';

	if (function_exists('hash')) {
		if ($stamp  % 2) {
			$badbehavior_algo = 'tiger160,4';
		} else {
			$badbehavior_algo = 'haval192,4'; }
		$cache_content = badbehavior_cache_get_data('badbehavior-' . substr(hash($badbehavior_algo,$bb_salt.$stamp.$ip2), -8), 0);
	} else {
		$cache_content = badbehavior_cache_get_data('badbehavior-' . substr(md5($bb_salt.$stamp.$ip2), -8), 0); } 

	if (empty($cache_content))  {
		unset($cache_content,$stamp,$bb_salt);
       	return false; }
	unset($cache_content,$stamp,$bb_salt);
return true;
}

function badbehavior_loadCache($ip2, $badbehavior_cache_array) {

	global $modSettings;

	if ($ip2 == '') return;
	if (empty($badbehavior_cache_array)) return;
	if (!is_array($badbehavior_cache_array)) return;

	// default 0 seconds
	$cache_content = $badbehavior_expire = $stamp = $bb_salt = '';
	$badbehavior_expire = 0;
	$stamp = date('Ymd');
	$bb_salt = 'j&9N';

	foreach($badbehavior_cache_array as $key => $value) {
		$cache_content .= $value; }
		unset($value);
	if (is_int((int) $modSettings['badbehavior_cache_duration']))
		$badbehavior_expire = ((int) $modSettings['badbehavior_cache_duration']);
	if (function_exists('hash')) {
		if ($stamp  % 2) {
			$badbehavior_algo = 'tiger160,4';
		} else {
			$badbehavior_algo = 'haval192,4'; }
		badbehavior_cache_put_data('badbehavior-' . substr(hash($badbehavior_algo,$bb_salt.$stamp.$ip2), -8), $cache_content, $badbehavior_expire);
	} else {
		badbehavior_cache_put_data('badbehavior-' . substr(md5($bb_salt.$stamp.$ip2), -8), $cache_content, $badbehavior_expire); } 
	unset($badbehavior_expire, $cache_content, $stamp, $bb_salt);
	return;
}

function badbehavior_cache_put_data($key, $value, $ttl = 0)
{
	global $boardurl, $sourcedir, $modSettings;
	global $cache_hits, $cache_count, $db_show_debug, $cachedir;

	if (((int) $modSettings['badbehavior_cache_duration']) == 0) return;

	$cache_count = isset($cache_count) ? $cache_count + 1 : 1;
	if (isset($db_show_debug) && $db_show_debug === true)
	{
		$cache_hits[$cache_count] = array('k' => $key, 'd' => 'put', 's' => $value === null ? 0 : strlen(serialize($value)));
		$st = microtime();
	}

	$value = empty($value) ? null : serialize($value);

	// Custom cache?
	if (function_exists('fwrite')) {
		if ($value === null)
			@unlink($cachedir . '/data_' . $key . '.php');
		else {
			$cache_data = $cache_bytes = $fh = '';
			$cache_data = '<' . '?' . 'php if (!defined(\'SMF\')) die; if (' . (time() + $ttl) . ' < time()) $expired = true; else{$expired = false; $value = \'' . addcslashes($value, '\\\'') . '\';}' . '?' . '>';
			$fh = @fopen($cachedir . '/data_' . $key . '.php', 'w');
			if ($fh) {
				// Write the file.
				set_file_buffer($fh, 0);
				flock($fh, LOCK_EX);
				$cache_bytes = fwrite($fh, $cache_data);
				flock($fh, LOCK_UN);
				fclose($fh);

				// Check that the cache write was successful; all the data should be written
				// If it fails due to low diskspace, remove the cache file
				if ($cache_bytes != strlen($cache_data))
					@unlink($cachedir . '/data_' . $key . '.php');
	}	}	}

	if (isset($db_show_debug) && $db_show_debug === true)
		$cache_hits[$cache_count]['t'] = array_sum(explode(' ', microtime())) - array_sum(explode(' ', $st));

	return;
}

function badbehavior_cache_get_data($key, $ttl = 0) {
	global $boardurl, $sourcedir, $modSettings;
	global $cache_hits, $cache_count, $db_show_debug, $cachedir;

	if (((int) $modSettings['badbehavior_cache_duration']) == 0) return null;

	$cache_count = isset($cache_count) ? $cache_count + 1 : 1;
	if (isset($db_show_debug) && $db_show_debug === true) {
		$cache_hits[$cache_count] = array('k' => $key, 'd' => 'get');
		$st = microtime();
	}

	// Use SMF data cache!
	if (file_exists($cachedir . '/data_' . $key . '.php') && filesize($cachedir . '/data_' . $key . '.php') > 10) {
		require($cachedir . '/data_' . $key . '.php');
		if (!empty($expired) && isset($value)) {
			@unlink($cachedir . '/data_' . $key . '.php');
			unset($value);
	}	}

	if (isset($db_show_debug) && $db_show_debug === true) {
		$cache_hits[$cache_count]['t'] = array_sum(explode(' ', microtime())) - array_sum(explode(' ', $st));
		$cache_hits[$cache_count]['s'] = isset($value) ? strlen($value) : 0;
	}

	if (empty($value))
		return null;
	// If it's broke, it's broke... so give up on it.
	else
		return @unserialize($value);
}

// Return all rows in a particular query.
// Should contain an array of all rows generated by calling mysql_fetch_assoc()
// or equivalent and appending the result of each call to an array.
function bb2_db_rows($linkid) {
	global $smcFunc;

	if (empty($linkid))
		return false;

	if (!is_array($linkid))
		return false;

	$result = array();
	$i = 0;

	while($row = $smcFunc['db_fetch_assoc']($linkid)) {
		$result[$i] = $row;
    	$i++;
	}

	if (empty($result))
		$result = $linkid; //If there were no rows, then just return the id

	$smcFunc['db_free_result']($linkid);

	return $result;
}

// Return emergency contact email address.
function bb2_email() {
	global $webmaster_email;

	$target_email = '';

	if ((!isset($webmaster_email)) || ($webmaster_email == ''))
		return '';

	return $webmaster_email;
}

function bb2_email_scramble() {
	global $webmaster_email;

	$target_email = '';

	if ((!isset($webmaster_email)) || ($webmaster_email == ''))
		$webmaster_email = "badbots@ioerror.us";

	$target_email = badbehavior_obfuscate(htmlspecialchars($webmaster_email));

	return $target_email;
}

function badbehavior_obfuscate($target_email) {
	global $txt, $sourcedir;

	if (function_exists('loadlanguage')) {
		if(loadlanguage('BadBehavior') === false)
     		 loadLanguage('BadBehavior');
	} else {
		require_once($sourcedir . '/Load.php');
		if(loadlanguage('BadBehavior') === false)
      		loadLanguage('BadBehavior'); }

	$stamp = date('Ymd');
	$webmaster_nospam  = $email = $encoding = $x = '';
	$search = array('@','.', '-');
	$replace = array($txt['badbehavior_nospam'], $txt['badbehavior_dot'], $txt['badbehavior_dash']);
	$characters = array('0000','0','0000','00000','000','00000','00','000', '00', '0');

	$webmaster_nospam = $txt['badbehavior_mailto'].str_replace($search, $replace, htmlspecialchars($target_email));

	list($usec, $sec) = explode(' ', microtime());
	mt_srand((float) $sec + ((float) $usec * 100000));
	$x = mt_rand(0, (count($characters)-1));
	$encoding = $characters[$x];

	if ($stamp  % 2) {
		for ($i = 0, $email_lng = strlen($webmaster_nospam); $i < $email_lng; $i++) {
			$email .= '&#x' .$encoding . dechex(ord($webmaster_nospam[$i])). ';'; }

 	} else {
		for ($i = 0, $email_lng = strlen($webmaster_nospam); $i < $email_lng; $i++) {
			$email .= '&#' . $encoding . ord($webmaster_nospam[$i]). ';'; }
	}
	$webmaster_nospam = $email;

	unset($email_lng, $i, $x, $email);
	unset($search, $replace, $characters);

	return $webmaster_nospam ;
}

function bb2_email_to() {
	global $txt, $sourcedir;

	if (function_exists('loadlanguage')) {
		if(loadlanguage('BadBehavior') === false)
     		 loadLanguage('BadBehavior');
	} else {
		require_once($sourcedir . '/Load.php');
		if(loadlanguage('BadBehavior') === false)
      		loadLanguage('BadBehavior'); }

	return $txt['badbehavior_theadmin'];
}

// retrieve settings from database
// Settings are hard-coded for non-database use
function bb2_read_settings() {
	global $badbehavior_settings, $modSettings;

	$badbehavior_settings['enable_badbehavior'] = $modSettings['enable_badbehavior'];
	$badbehavior_settings['log_table'] = $modSettings['badbehavior_log_table'];
	$badbehavior_settings['display_stats'] = $modSettings['badbehavior_display_stats'];
	$badbehavior_settings['strict'] = $modSettings['badbehavior_strict'];
	$badbehavior_settings['verbose'] = $modSettings['badbehavior_verbose'];
	$badbehavior_settings['logging'] = $modSettings['badbehavior_logging'];
	$badbehavior_settings['httpbl_key'] = $modSettings['badbehavior_httpbl_key'];
	$badbehavior_settings['httpbl_threat'] = $modSettings['badbehavior_httpbl_threat'];
	$badbehavior_settings['httpbl_maxage'] = $modSettings['badbehavior_httpbl_maxage'];
	$badbehavior_settings['offsite_forms'] = $modSettings['badbehavior_offsite_forms'];
	$badbehavior_settings['reverse_proxy'] = $modSettings['badbehavior_reverse_proxy'];
	$badbehavior_settings['reverse_proxy_header'] = $modSettings['badbehavior_reverse_proxy_header'];

	if (isset($modSettings['badbehavior_reverse_proxy_addresses']) && !empty($modSettings['badbehavior_reverse_proxy_addresses'])) {
		$reverse_proxy_addresses = '';
		$badbehavior_settings['reverse_proxy_addresses'] = array();

		$reverse_proxy_addresses = preg_split('/[\s,]+/m', $modSettings['badbehavior_reverse_proxy_addresses'], -1, PREG_SPLIT_NO_EMPTY);  //  possibly /^(?=\d+\s)/m
		foreach ($reverse_proxy_addresses as $reverse_proxy_addresses_chunk) {
			$badbehavior_settings['reverse_proxy_addresses'][] = explode('\n\r', trim($reverse_proxy_addresses_chunk));
		}
		unset($reverse_proxy_addresses_chunk);
	} else {
		$badbehavior_settings['reverse_proxy_addresses'] = $modSettings['badbehavior_reverse_proxy_addresses'] = '';
	}

	return $badbehavior_settings;
}

//	Write settings to database
//  Settings are written in SMF admin panel, this code is here for future referance
//  and only contains the essentials for BB
function bb2_write_settings($badbehavior_settings) {
	global $txt, $modSettings, $db_prefix, $smcFunc, $settings;

	if (empty($badbehavior_settings)) return false;

	if (!is_array($badbehavior_settings)) return false;

	if (is_array($badbehavior_settings['reverse_proxy_addresses'])) {
		$reverse_proxy_addresses = '';
		foreach($badbehavior_settings['reverse_proxy_addresses'] as $key => $value) {
			$reverse_proxy_addresses .= $value . '\n\r'; }
		unset($value);
		$reverse_proxy_addresses = substr($reverse_proxy_addresses, 0, -4);
	} else {
		$reverse_proxy_addresses = $badbehavior_settings['reverse_proxy_addresses'];
	}

	$smcFunc['db_insert']('ignore',
		'{db_prefix}settings',
			array('variable' => 'string', 'value' => 'string',
			),
			array(
				array('enable_badbehavior', $badbehavior_settings['enable_badbehavior']),
				array('badbehavior_log_table', $badbehavior_settings['log_table']),
				array('badbehavior_display_stats', $badbehavior_settings['display_stats']),
				array('badbehavior_strict', $badbehavior_settings['strict']),
				array('badbehavior_verbose', $badbehavior_settings['verbose']),
				array('badbehavior_logging', $badbehavior_settings['logging']),
				array('badbehavior_httpbl_key', $badbehavior_settings['httpbl_key']),
				array('badbehavior_httpbl_threat', $badbehavior_settings['httpbl_threat']),
				array('badbehavior_httpbl_maxage', $badbehavior_settings['httpbl_maxage']),
				array('badbehavior_offsite_forms', $badbehavior_settings['offsite_forms']),
				array('badbehavior_reverse_proxy', $badbehavior_settings['reverse_proxy']),
				array('badbehavior_reverse_proxy_header', $badbehavior_settings['reverse_proxy_header']),
				array('badbehavior_reverse_proxy_addresses', $reverse_proxy_addresses),
			),
			array('variable')
		);

	unset($reverse_proxy_addresses);
	return true;
}

// installation
function bb2_install() {
	global $badbehavior_settings, $modSettings;

	$badbehavior_settings = bb2_read_settings();
	if (!$badbehavior_settings['logging']) return;

	bb2_db_query(bb2_table_structure($badbehavior_settings['log_table']));
	return;
}

function badbehavior_checkGoodgroup($badbehavior_ip) {
	global $sourcedir, $context, $user_info, $smcFunc;

	if ($badbehavior_ip == '') return true;

	$badbehavior_goodgroup = array();
	$dos_cond = true;

	$qresult = $smcFunc['db_query']('', '
		SELECT id_group, permission, add_deny
		FROM {db_prefix}permissions
		WHERE permission = {string:search_permission}
			AND add_deny = {int:permission_state}',
		array(
			'search_permission' => 'badbehavior_goodgroup',
			'permission_state' => 1,
		)
	);

	if ($smcFunc['db_num_rows']($qresult) > 0) {
		while ($row = $smcFunc['db_fetch_assoc']($qresult)) {
			$badbehavior_goodgroup[$row['permission']] = $row['id_group'];
	}	}
	$smcFunc['db_free_result']($qresult);

	if (!empty($badbehavior_goodgroup) && is_array($badbehavior_goodgroup)) {
		if (function_exists('loadIllegalPermissions')) {
			loadIllegalPermissions();
		} else {
			require_once($sourcedir . '/ManagePermissions.php');
			loadIllegalPermissions(); }
		foreach ($badbehavior_goodgroup as $perm => $group_id) {
			if ($user_info['groups']['0'] == $group_id) $dos_cond = false;
			if (!empty($context['illegal_permissions']) && in_array($perm, $context['illegal_permissions'])) {
				$dos_cond = true;
				break; }
			$qresult = $smcFunc['db_query']('', '
				SELECT
					id_group, member_ip, member_ip2, is_activated
				FROM {db_prefix}members
				WHERE member_ip = {string:ipofuser}
					OR member_ip2 = {string:ipofuser}',
				array(
					'ipofuser' => $badbehavior_ip,
				)
			);
			if ($smcFunc['db_num_rows']($qresult) > 0) {
				while ($row = $smcFunc['db_fetch_assoc']($qresult)) {
					if ($row['id_group'] == $group_id) {
						$dos_cond = false;
						if ($row['is_activated'] >= 10) $dos_cond = true;  //  is_banned
						break;
			}	}	}
	$smcFunc['db_free_result']($qresult); }
	unset($perm, $group_id); }

	unset($badbehavior_goodgroup);

	return $dos_cond;
}

// Screener
// Insert this into the <head> section of your HTML through a template call
// or whatever is appropriate. This is optional we'll fall back to cookies
// if you don't use it.
function bb2_insert_head() {
	global $bb2_timer_total;
	global $bb2_javascript;
	echo "\n\r<!-- Bad Behavior " . BB2_VERSION . " run time: " . number_format(1000 * $bb2_timer_total, 3) . " ms -->\n\r";
	echo $bb2_javascript;
}

// Get the total amount of blocked spammers in x days
function bb2_insert_stats($force = false) {
	global $smcFunc, $modSettings, $badbehavior_settings;

	if (!$modSettings['enable_badbehavior']) return;

	$badbehavior_settings = bb2_read_settings();

	$blocked = $qresult = $total_result = $where = '';

	$where = "bb.id >= 0";
	$qresult = $smcFunc['db_query']('', '
			SELECT COUNT(*)
			FROM {db_prefix}log_badbehavior AS bb
			WHERE ' . $where,
			array(
			'selected_key' => '00000000',
			)
		);
	if ($smcFunc['db_num_rows']($qresult) > 0) {
		$smcFunc['db_free_result']($qresult);

		$where = "bb.key NOT LIKE {string:selected_key}";
		$total_result = $smcFunc['db_query']('', '
			SELECT COUNT(*)
			FROM {db_prefix}log_badbehavior AS bb
			WHERE ' . $where,
			array(
			'selected_key' => '00000000',
			)
		);
		list ($blocked) = $smcFunc['db_fetch_row']($total_result);
		$smcFunc['db_free_result']($total_result);
	} else {
		$smcFunc['db_free_result']($qresult);
	}

	if (!empty($blocked)) {
		$timelimit = 7;  //  default 7 days
		if ((int) $modSettings['badbehavior_timelimit'] > 0)
			$timelimit = (int) $modSettings['badbehavior_timelimit'];

         echo '<p><a href="http://www.bad-behavior.ioerror.us/">Bad Behavior</a> has blocked <strong>'.$blocked.'</strong> access attempts in the last '.$timelimit.' days.</p>';
	}
}

// Return the top-level relative path of wherever we are (for cookies)
// You should provide in $url the top-level URL for your site.
function bb2_relative_path() {
global $boardurl;
	return $boardurl;
}

if ($user_info['is_admin'])
	require_once($sourcedir . '/BadBehavior-Admin.php');

// Calls inward to Bad Behavor itself.
require_once($sourcedir . '/bad-behavior/bad-behavior/core.inc.php');

//bb2_install();	// I do not think I need thnis - see above
if (($modSettings['enable_badbehavior']) && (!$user_info['is_admin']))
	$_SESSION['BB2_RESULT'] = bb2_start(bb2_read_settings());

$bb2_mtime = explode(" ", microtime());
$bb2_timer_stop = $bb2_mtime[1] + $bb2_mtime[0];
$bb2_timer_total = $bb2_timer_stop - $bb2_timer_start;

?>

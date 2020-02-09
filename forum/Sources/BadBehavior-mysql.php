<?php
/**********************************************************************************
* BadBehavior-mysql.php - PHP database interface for Bad Behavior mod
* Version 1.4.0 by JMiller a/k/a butchs
* (http://www.eastcoastrollingthunder.com) 
*********************************************************************************
* This program is distributed in the hope that it is and will be useful, but
* WITHOUT ANY WARRANTIES; without even any implied warranty of MERCHANTABILITY
* or FITNESS FOR A PARTICULAR PURPOSE.
**********************************************************************************/

if (!defined('SMF')) die('Hacking attempt...');

global $txt, $modSettings, $db_prefix, $smcFunc, $sourcedir;

require_once($sourcedir . '/bad-behavior/BadBehavior-SMF.php');

// Our log table structure
function bb2_table_structure($name) {

global $txt, $modSettings, $db_prefix, $smcFunc;

db_extend('packages');

	// It's not paranoia if they really are out to get you.

	return $smcFunc['db_table_structure']($db_prefix.'log_badbehavior');
}

// Create a new table for SMF 2.0 RC2
function bb2_insert_table() {

global $smcFunc, $txt, $user_info, $db_prefix, $ssi_theme;


	if (function_exists('db_extend')) {
		db_extend('packages');
		db_extend();
	} else {
		require_once($sourcedir . '/Subs-Db-mysql.php');
		db_extend('packages');
		db_extend(); }

	//  check for old database
	$old_table_exists = $smcFunc['db_list_tables'](false, $db_prefix . 'log_badbehavior');
	//  add new 2.0 database
	if (empty($old_table_exists)) {
		$smcFunc['db_create_table']($db_prefix.'log_badbehavior',
			array(
				array(
					'name' => 'id',
					'type' => 'int',
					'size' => 11,
					'null' => false,
					'default' => '',
					'auto' => true,
					'unsigned' => true,
				),
				array(
					'name' => 'ip',
					'type' => 'varchar',
					'size' => 16,
					'null' => false,
					'default' => '',
					'auto' => false,
					'unsigned' => false,
				),
				array(
					'name' => 'date',
					'type' => 'varchar',
					'size' => 19,
					'null' => false,
					'default' => '0000-00-00 00:00:00',
					'auto' => false,
					'unsigned' => false,
				),
				array(
					'name' => 'request_method',
					'type' => 'varchar',
					'size' => 4,
					'null' => false,
					'default' => '',
					'auto' => false,
					'unsigned' => false,
				),
				array(
					'name' => 'request_uri',
					'type' => 'varchar',
					'size' => 255,
					'null' => false,
					'default' => '',
					'auto' => false,
					'unsigned' => false,
				),
				array(
					'name' => 'server_protocol',
					'type' => 'varchar',
					'size' => 15,
					'null' => false,
					'default' => '',
					'auto' => false,
					'unsigned' => false,
				),
				array(
					'name' => 'http_headers',
					'type' => 'text',
					'size' => '',
					'null' => false,
					'default' => '',
					'auto' => false,
					'unsigned' => false,
				),
				array(
					'name' => 'user_agent',
					'type' => 'varchar',
					'size' => 255,
					'null' => false,
					'default' => '',
					'auto' => false,
					'unsigned' => false,
				),
				array(
					'name' => 'request_entity',
					'type' => 'varchar',
					'size' => 255,
					'null' => false,
					'default' => '',
					'auto' => false,
					'unsigned' => false,
				),
				array(
					'name' => 'key',
					'type' => 'varchar',
					'size' => 255,
					'null' => false,
					'default' => '',
					'auto' => false,
					'unsigned' => false,
				),
			),
			array( 
				array(
					'type' => 'primary',
					'columns' => array('id')
				),
				array(
					'type' => 'index',
					'columns' => array('ip'),
					'size' => 15,
				),
				array(
					'type' => 'index',
					'columns' => array('user_agent'),
					'size' => 10,
				)
			),
			'ignore'
		);
	} else {  //  Upgrade from old version
			// Change headers
			$smcFunc['db_change_column']($db_prefix . 'log_badbehavior', 'http_headers',
				array(
					'name' => 'http_headers',
					'type' => 'text',
				),
				array(
					'no_prefix' => true,
				)
			);
			// Second time to make sure
			$smcFunc['db_change_column']($db_prefix . 'log_badbehavior', 'http_headers',
				array(
					'name' => 'http_headers',
					'type' => 'text',
				),
				array(
					'no_prefix' => true,
				)
			);
			// Change date
			$smcFunc['db_change_column']($db_prefix . 'log_badbehavior', 'date',
				array(
					'name' => 'date',
					'size' => 19,
				),
				array(
					'no_prefix' => true,
				)
			);
	}
}

// Insert a new record modified for SMF 2.0 RC2
function bb2_insert($bb2_settings, $package, $key)
{
	global $txt, $modSettings, $db_prefix, $smcFunc;

	if (empty($package)) return;
	if (!is_array($package)) return;

	$request = $ip = $date = $request_method = $request_entity = '';
	$request_uri = $server_protocol = $user_agent = $headers = '';

	$ip = bb2_db_escape($package['ip']);

	$date = bb2_db_date();

	$request_method = bb2_db_escape($package['request_method']);
	$request_uri = bb2_db_escape($package['request_uri']);
	$server_protocol = bb2_db_escape($package['server_protocol']);
	$user_agent = bb2_db_escape($package['user_agent']);
	$headers = "$request_method $request_uri $server_protocol\n\r";
	foreach ($package['headers'] as $h => $v) {
		$headers .= bb2_db_escape("$h: $v\n\r");
	}
	unset($v);

	if (!strcasecmp($request_method, "POST")) {
		foreach ($package['request_entity'] as $h => $v) {
			$request_entity .= bb2_db_escape("$h: $v\n\r");
		}
	unset($v);
	}

$request = $smcFunc['db_insert']('insert',
	'{db_prefix}log_badbehavior',
	array(
		'ip' => 'string-16', 
		'date' => 'string-19',
		'request_method' => 'string-4',
		'request_uri'=> 'string-255',
		'server_protocol' => 'string-15',
		'http_headers' => 'string-65534',
		'user_agent' => 'string-255',
		'request_entity' => 'string-255',
		'key' => 'string-255',
         ),
	array(
		$ip,
		$date,
		$request_method,
		$request_uri,
		$server_protocol,
		$headers,
		$user_agent,
		$request_entity,
		$key,
	),
	array()
);

	return $request;
}

?>

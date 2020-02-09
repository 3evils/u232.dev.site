<?php
/*  Copyright 2011 Michael Oestergaard Pedersen

    This file is part of Users Online Today Mod.

    Users Online Today Mod is free software: you can redistribute it and/or
    modify it under the terms of the GNU Affero General Public License as
    published by the Free Software Foundation, either version 3 of the License,
    or (at your option) any later version.

    Users Online Today Mod is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU Affero General Public License for more details.

    You should have received a copy of the GNU Affero General Public License along
    with Users Online Today Mod.  If not, see <http://www.gnu.org/licenses/>.
*/

if (!defined('SMF'))
	die('Hacking attempt...');

// Add mod settings to the administration interface
function UsersOnlineToday_settings(&$param1)
{
	global $txt;

	// Load the language file for this mod
	loadLanguage('UsersOnlineToday');

	// Update the config_vars array
	$param1 = array_merge($param1, array(
		array('select', 'uot_setting_sortby', 'label' => 'Users Online Today', array('username' => $txt['uot_setting_username'], 'login_time' => $txt['uot_setting_login_time']), 'preinput' => $txt['uot_setting_sortby_pre_txt'], 'postinput' => $txt['uot_setting_sortby_post_txt']),
		array('select', 'uot_setting_sortorder', array('ascending' => $txt['uot_setting_ascending'], 'descending' => $txt['uot_setting_descending']), 'preinput' => $txt['uot_setting_sortorder_pre_txt'], 'postinput' => $txt['uot_setting_sortorder_post_txt']),
		array('select', 'uot_setting_period', array('current_day' => $txt['uot_setting_current_day'], 'last_24_hours' => $txt['uot_setting_last_24_hours'], 'last_7_days' => $txt['uot_setting_last_7_days']), 'preinput' => $txt['uot_setting_period_pre_txt'], 'postinput' => $txt['uot_setting_period_post_txt']),
		array('select', 'uot_setting_canview', array('admin' => $txt['uot_setting_admin'], 'registered' => $txt['uot_setting_registered'], 'everyone' => $txt['uot_setting_everyone']), 'preinput' => $txt['uot_setting_canview_pre_txt'], 'postinput' => $txt['uot_setting_canview_post_txt'])
        ));
}

// Retrieve a list and several other statistics of the users online during a specific period.
function getUsersOnlineTodayStats()
{
	global $smcFunc, $context, $scripturl, $user_info, $modSettings, $txt;

	// The list can be sorted by last login or member name.
	$allowed_sortby_options = array(
		'username',
		'login_time',
	);

	// The list can be sorted ascending or descending
	$allowed_sortorder_options = array(
		'ascending',
		'descending',
	);

	// There are three periods to choose from.
	$allowed_period_options = array(
		'current_day',
		'last_24_hours',
		'last_7_days',
	);

	// Who can view the online today list.
	$allowed_canview_options = array(
		'everyone',
		'registered',
		'admin',
	);

	// Not allowed sort by? Bang! Error!
	if (!in_array($modSettings['uot_setting_sortby'], $allowed_sortby_options))
	{
		$modSettings['uot_setting_sortby'] = 'login_time';
		$smcFunc['db_insert']('replace', '{db_prefix}settings', array('variable' => 'string', 'value' => 'string'), array('uot_setting_sortby', 'login_time'), array('variable'));
		trigger_error('Sort by for getUsersOnlineTodayStats() function is not allowed', E_USER_NOTICE);
	}

	// Not allowed sort order? Bang! Error!
	if (!in_array($modSettings['uot_setting_sortorder'], $allowed_sortorder_options))
	{
		$modSettings['uot_setting_sortorder'] = 'descending';
		$smcFunc['db_insert']('replace', '{db_prefix}settings', array('variable' => 'string', 'value' => 'string'), array('uot_setting_sortorder', 'descending'), array('variable'));
		trigger_error('Sort order for getUsersOnlineTodayStats() function is not allowed', E_USER_NOTICE);
	}

	// Not allowed period? Bang! Error!
	if (!in_array($modSettings['uot_setting_period'], $allowed_period_options))
	{
		$modSettings['uot_setting_period'] = 'current_day';
		$smcFunc['db_insert']('replace', '{db_prefix}settings', array('variable' => 'string', 'value' => 'string'), array('uot_setting_period', 'current_day'), array('variable'));
		trigger_error('Period for getUsersOnlineTodayStats() function is not allowed', E_USER_NOTICE);
	}

	// Not allowed canview? Bang! Error!
	if (!in_array($modSettings['uot_setting_canview'], $allowed_canview_options))
	{
		$modSettings['uot_setting_canview'] = 'registered';
		$smcFunc['db_insert']('replace', '{db_prefix}settings', array('variable' => 'string', 'value' => 'string'), array('uot_setting_canview', 'registered'), array('variable'));
		trigger_error('Canview for getUsersOnlineTodayStats() function is not allowed', E_USER_NOTICE);
	}

	// Initialize the array that'll be returned later on.
	$usersOnlineTodayStats = array(
		'users_online_today' => array(),
		'list_users_online_today' => array(),
		'groups_online_today' => array(),
		'num_buddies_today' => 0,
		'num_users_hidden_today' => 0,
		'num_users_online_today' => 0,
		'viewing_allowed' => false,
	);

	// Load the users online during last period.
	if ($modSettings['uot_setting_period'] == 'current_day')
	{
		$date = @getdate(forum_time(false));
		$midnight = mktime(0, 0, 0, $date['mon'], $date['mday'], $date['year']) - ($modSettings['time_offset'] * 3600);
	}
	else
	{
		if ($modSettings['uot_setting_period'] == 'last_24_hours') $midnight = time() - 86400;
		elseif ($modSettings['uot_setting_period'] == 'last_7_days') $midnight = time() - 604800; 
	}

	$request = $smcFunc['db_query']('', '
		SELECT
			mem.id_member, mem.last_login, mem.real_name, mem.member_name, mem.show_online,
			mg.online_color, mg.id_group, mg.group_name
		FROM {db_prefix}members AS mem
			LEFT JOIN {db_prefix}membergroups AS mg ON (mg.id_group = CASE WHEN mem.id_group = {int:reg_mem_group} THEN mem.id_post_group ELSE mem.id_group END)
			WHERE mem.last_login >= {int:midnight}',
		array(
			'reg_mem_group' => 0,
			'midnight' => $midnight,
		)
	);

	// Time string formatting
	$s = strpos($user_info['time_format'], '%S') === false ? '' : ':%S';
	if (strpos($user_info['time_format'], '%H') === false && strpos($user_info['time_format'], '%T') === false)
		$time_fmt = '%I:%M' . $s . ' %p';
	else
		$time_fmt = '%H:%M' . $s;

	// Is the user allowed to view the user list
	$usersOnlineTodayStats['viewing_allowed'] = allowedTo('moderate_forum') || $modSettings['uot_setting_canview'] == 'everyone';
	$usersOnlineTodayStats['viewing_allowed'] = $usersOnlineTodayStats['viewing_allowed'] || (!$context['user']['is_guest'] && $modSettings['uot_setting_canview'] == 'registered');

	// Load the language file for this mod
	loadLanguage('UsersOnlineToday');

	while ($row = $smcFunc['db_fetch_assoc']($request))
	{
		if (empty($row['show_online']))
		{
			$usersOnlineTodayStats['num_users_hidden_today']++;
			if (!allowedTo('moderate_forum')) continue;
		}

		// Generate the text to hover over the user name
		$userday = strftime('%d', forum_time(true));
		$loginday = strftime('%d', forum_time(true, $row['last_login']));
		if ($userday == $loginday)
			$last_login_txt = strftime($time_fmt, forum_time(true, $row['last_login']));
		else
			$last_login_txt = strip_tags(timeformat($row['last_login']));
		$title = ' title="' . $last_login_txt . '"';

		// Some basic color coding...
		if (!empty($row['online_color']))
			$link = '<a href="' . $scripturl . '?action=profile;u=' . $row['id_member'] . '"' . $title . ' style="color: ' . $row['online_color'] . ';">' . $row['real_name'] . '</a>';
		else
			$link = '<a href="' . $scripturl . '?action=profile;u=' . $row['id_member'] . '"' . $title . '>' . $row['real_name'] . '</a>';

		// Buddies get counted and highlighted.
		$is_buddy = in_array($row['id_member'], $user_info['buddies']);
		if ($is_buddy)
		{
			$usersOnlineTodayStats['num_buddies_today']++;
			$link = '<b>' . $link . '</b>';
		}

		// Determine the sort key
		if ($modSettings['uot_setting_sortby'] == 'login_time') $sortkey = $row['last_login'] . strtolower($row['member_name']);
								elseif ($modSettings['uot_setting_sortby'] == 'username') $sortkey = strtolower($row['member_name']);

		// A lot of useful information for each member.
		$usersOnlineTodayStats['users_online_today'][$sortkey] = array(
			'id' => $row['id_member'],
			'username' => $row['member_name'],
			'name' => $row['real_name'],
			'group' => $row['id_group'],
			'href' => $scripturl . '?action=profile;u=' . $row['id_member'],
			'link' => $link,
			'is_buddy' => $is_buddy,
			'hidden' => empty($row['show_online']),
			'is_last' => false,
		);

		// This is the compact version, simply implode it to show.
		$usersOnlineTodayStats['list_users_online_today'][$sortkey] = empty($row['show_online']) ? '<i>' . $link . '</i>' : $link;

		// Store all distinct (primary) membergroups that are shown.
		if (!isset($usersOnlineTodayStats['groups_online_today'][$row['id_group']]))
			$usersOnlineTodayStats['groups_online_today'][$row['id_group']] = array(
				'id' => $row['id_group'],
				'name' => $row['group_name'],
				'color' => $row['online_color']
			);
	}
	$smcFunc['db_free_result']($request);

	// Time to sort the list a bit.
	if (!empty($usersOnlineTodayStats['users_online_today']))
	{
		// Determine the sort direction.
		$sortFunction = ($modSettings['uot_setting_sortorder'] == 'descending') ? 'krsort' : 'ksort';

		// Sort the two lists.
		$sortFunction($usersOnlineTodayStats['users_online_today']);
		$sortFunction($usersOnlineTodayStats['list_users_online_today']);

		// Mark the last list item as 'is_last'.
		$userKeys = array_keys($usersOnlineTodayStats['users_online_today']);
		$usersOnlineTodayStats['users_online_today'][end($userKeys)]['is_last'] = true;
	}

	// Also sort the membergroups.
	ksort($usersOnlineTodayStats['groups_online_today']);

	// Hidden and non-hidden members make up all online members.
	$usersOnlineTodayStats['num_users_online_today'] = count($usersOnlineTodayStats['users_online_today']);
	if (!allowedTo('moderate_forum'))
		$usersOnlineTodayStats['num_users_online_today'] += $usersOnlineTodayStats['num_users_hidden_today'];

	return $usersOnlineTodayStats;
}
?>

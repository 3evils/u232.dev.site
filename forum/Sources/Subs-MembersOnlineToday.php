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

/*	This file currently only holds the function for showing a list of online
	users used by the board index and SSI. In the future it'll also contain
	functions used by the Who's online page.

	array getMembersOnlineTodayStats(array membersOnlineTodayOptions)
		- retrieve a list and several other statistics of the users currently
			online on the forum.
		- used by the board index and SSI.
		- also returns the membergroups of the users that are currently online.
		- hides members that chose to hide their online presense (optional).
*/

// Retrieve a list and several other statistics of the users online during a specific period.
function getMembersOnlineTodayStats($membersOnlineTodayOptions)
{
	global $smcFunc, $context, $scripturl, $user_info, $modSettings, $txt;

	// The list can be sorted by last login or member name.
	$allowed_sort_options = array(
		'login_time',
		'member_name',
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

	// Default the sorting method to 'most recent online members first'.
	if (!isset($membersOnlineTodayOptions['sort']))
	{
		$membersOnlineTodayOptions['sort'] = 'login_time';
		$membersOnlineTodayOptions['reverse_sort'] = true;
	}
	// Not allowed sort method? Bang! Error!
	elseif (!in_array($membersOnlineTodayOptions['sort'], $allowed_sort_options))
		trigger_error('Sort method for getMembersOnlineTodayStats() function is not allowed', E_USER_NOTICE);

	// Default period is the current day.
	if (!isset($membersOnlineTodayOptions['period']))
				{
								$membersOnlineTodayOptions['period'] = 'current_day';
				}
	// Not allowed period method? Bang! Error!
	elseif (!in_array($membersOnlineTodayOptions['period'], $allowed_period_options))
		trigger_error('Period method for getMembersOnlineTodayStats() function is not allowed', E_USER_NOTICE);

	// Default is that only registered users can see the list. 
	if (!isset($membersOnlineTodayOptions['canview']))
	{
		$membersOnlineTodayOptions['canview'] = 'registered';
	}
	// Not allowed canview method? Bang! Error!
	elseif (!in_array($membersOnlineTodayOptions['canview'], $allowed_canview_options))
		trigger_error('Canview method for getMembersOnlineTodayStats() function is not allowed', E_USER_NOTICE);
	
	// Initialize the array that'll be returned later on.
	$membersOnlineTodayStats = array(
		'users_online_today' => array(),
		'list_users_online_today' => array(),
		'groups_online_today' => array(),
		'num_buddies_today' => 0,
		'num_users_hidden_today' => 0,
		'num_users_online_today' => 0,
		'viewing_allowed' => false,
	);

	// Load the users online during last period.
	if ($membersOnlineTodayOptions['period'] == 'current_day')
	{
		$date = @getdate(forum_time(false));
		$midnight = mktime(0, 0, 0, $date['mon'], $date['mday'], $date['year']) - ($modSettings['time_offset'] * 3600);
	}
	else
	{
		if ($membersOnlineTodayOptions['period'] == 'last_24_hours') $midnight = time() - 86400;
		elseif ($membersOnlineTodayOptions['period'] == 'last_7_days') $midnight = time() - 604800; 
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
	$membersOnlineTodayStats['viewing_allowed'] = allowedTo('moderate_forum') || $membersOnlineTodayOptions['canview'] == 'everyone';
	$membersOnlineTodayStats['viewing_allowed'] = $membersOnlineTodayStats['viewing_allowed'] || (!$context['user']['is_guest'] && $membersOnlineTodayOptions['canview'] == 'registered');

	// Load the language file for this mod
	loadLanguage('MembersOnlineToday');

	while ($row = $smcFunc['db_fetch_assoc']($request))
	{
		if (empty($row['show_online']))
		{
			$membersOnlineTodayStats['num_users_hidden_today']++;
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
			$membersOnlineTodayStats['num_buddies_today']++;
			$link = '<b>' . $link . '</b>';
		}

		// Determine the sort key
		if ($membersOnlineTodayOptions['sort'] == 'login_time') $sortkey = $row['last_login'] . strtolower($row['member_name']);
								elseif ($membersOnlineTodayOptions['sort'] == 'member_name') $sortkey = strtolower($row['member_name']);

		// A lot of useful information for each member.
		$membersOnlineTodayStats['users_online_today'][$sortkey] = array(
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
		$membersOnlineTodayStats['list_users_online_today'][$sortkey] = empty($row['show_online']) ? '<i>' . $link . '</i>' : $link;

		// Store all distinct (primary) membergroups that are shown.
		if (!isset($membersOnlineTodayStats['groups_online_today'][$row['id_group']]))
			$membersOnlineTodayStats['groups_online_today'][$row['id_group']] = array(
				'id' => $row['id_group'],
				'name' => $row['group_name'],
				'color' => $row['online_color']
			);
	}
	$smcFunc['db_free_result']($request);

	// Time to sort the list a bit.
	if (!empty($membersOnlineTodayStats['users_online_today']))
	{
		// Determine the sort direction.
		$sortFunction = $membersOnlineTodayOptions['reverse_sort'] ? 'krsort' : 'ksort';

		// Sort the two lists.
		$sortFunction($membersOnlineTodayStats['users_online_today']);
		$sortFunction($membersOnlineTodayStats['list_users_online_today']);

		// Mark the last list item as 'is_last'.
		$userKeys = array_keys($membersOnlineTodayStats['users_online_today']);
		$membersOnlineTodayStats['users_online_today'][end($userKeys)]['is_last'] = true;
	}

	// Also sort the membergroups.
	ksort($membersOnlineTodayStats['groups_online_today']);

	// Hidden and non-hidden members make up all online members.
	$membersOnlineTodayStats['num_users_online_today'] = count($membersOnlineTodayStats['users_online_today']);
	if (!allowedTo('moderate_forum'))
		$membersOnlineTodayStats['num_users_online_today'] += $membersOnlineTodayStats['num_users_hidden_today'];

	return $membersOnlineTodayStats;
}

?>

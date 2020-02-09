<?php

/**
 * Simple Machines Forum (SMF)
 *
 * @package SMF
 * @author Simple Machines http://www.simplemachines.org
 * @copyright 2011 Simple Machines
 * @license http://www.simplemachines.org/about/smf/license.php BSD
 *
 * @version 2.0
 */

if (!defined('SMF'))
	die('Hacking attempt...');

/*	The single function this file contains is used to display the main
	board index.  It uses just the following functions:

	void BoardIndex()
		- shows the board index.
		- uses the BoardIndex template, and main sub template.
		- may use the boardindex subtemplate for wireless support.
		- updates the most online statistics.
		- is accessed by ?action=boardindex.

	void CollapseCategory()
		- collapse or expand a category
*/

// Show the board index!
function BoardIndex()
{
	global $txt, $user_info, $sourcedir, $modSettings, $context, $settings, $scripturl;

	// For wireless, we use the Wireless template...
	if (WIRELESS)
		$context['sub_template'] = WIRELESS_PROTOCOL . '_boardindex';
	else
		loadTemplate('BoardIndex');

	// Set a canonical URL for this page.
	$context['canonical_url'] = $scripturl;

	// Do not let search engines index anything if there is a random thing in $_GET.
	if (!empty($_GET))
		$context['robot_no_index'] = true;

	// Retrieve the categories and boards.
	require_once($sourcedir . '/Subs-BoardIndex.php');
	$boardIndexOptions = array(
		'include_categories' => true,
		'base_level' => 0,
		'parent_id' => 0,
		'set_latest_post' => true,
		'countChildPosts' => !empty($modSettings['countChildPosts']),
	);
	$context['categories'] = getBoardIndex($boardIndexOptions);
	
	// Calculation of board viewers ... By HarzeM
	if (!empty($modSettings['enable_board_viewers']))
	{
		foreach($context['categories'] as $keyc => $category)
		{
			foreach($category['boards'] as $key => $boards)
			{
				$this_guests = $boards['viewers']['guests'];
				$this_members = $boards['viewers']['members'];
				$this_viewers = $this_guests + $this_members;
			
				$context['categories'][$keyc]['boards'][$key]['viewer_text'] = ($this_viewers > 0) ?
					('<span class="smalltext" style="float:right;">(' .
						(!empty($modSettings['boardViewersClickable']) ? '<a href="'.$scripturl.'?action=viewers;board='.$key.'" onclick="return reqWin(this.href);" title="'.$txt['viewersBrowsing'].'">' : '') .
						(!empty($modSettings['boardViewersGuestsMembers']) ? 
							((($this_members > 1) ? "<b>".$this_members."</b> ". $txt['board_v_members'] : "<b>".$this_members."</b> ". $txt['board_v_member']) .
							(($this_guests > 1) ? ", <b>".$this_guests."</b> ". $txt['board_v_guests'] : ", <b>".$this_guests."</b> ". $txt['board_v_guest']))
							: (($this_viewers > 1) ? "<b>".$this_viewers."</b> ". $txt['board_viewers'] : "<b>".$this_viewers."</b> ". $txt['board_viewer'])
						) .
						(!empty($modSettings['boardViewersClickable']) ? '</a>' : '') .
					')</span>')
					: '' ;

				if($boards['children'] !== array())
				{
					foreach($boards['children'] as $keych => $child)
					{
						$this_guests = $child['viewers']['guests'];
						$this_members = $child['viewers']['members'];
						$this_viewers = $this_guests + $this_members;
						$context['categories'][$keyc]['boards'][$key]['children'][$keych]['viewer_text'] = ($this_viewers > 0) ? (($this_viewers > 1) ? ", ". $txt['board_viewers_child'] . ": " . $this_viewers : ", ". $txt['board_viewer_child'] . ": " . $this_viewers) : '';
					}
				}
			}
		}
	}

	// Get the user online list.
	require_once($sourcedir . '/Subs-MembersOnline.php');
	$membersOnlineOptions = array(
		'show_hidden' => allowedTo('moderate_forum'),
		'sort' => 'log_time',
		'reverse_sort' => true,
	);
	$context += getMembersOnlineStats($membersOnlineOptions);

	// Get the user online today list.
	$context += getUsersOnlineTodayStats();
			
	// Get the user online today list.
	//$context += getUsersOnlineTodayStats();
			
	$context['show_buddies'] = !empty($user_info['buddies']);

	// Are we showing all membergroups on the board index?
	if (!empty($settings['show_group_key']))
		$context['membergroups'] = cache_quick_get('membergroup_list', 'Subs-Membergroups.php', 'cache_getMembergroupList', array());

	// Track most online statistics? (Subs-MembersOnline.php)
	if (!empty($modSettings['trackStats']))
		trackStatsUsersOnline($context['num_guests'] + $context['num_spiders'] + $context['num_users_online']);

	// Retrieve the latest posts if the theme settings require it.
	if (isset($settings['number_recent_posts']) && $settings['number_recent_posts'] > 1)
	{
		$latestPostOptions = array(
			'number_posts' => $settings['number_recent_posts'],
		);
		$context['latest_posts'] = cache_quick_get('boardindex-latest_posts:' . md5($user_info['query_wanna_see_board'] . $user_info['language']), 'Subs-Recent.php', 'cache_getLastPosts', array($latestPostOptions));
	}

	$settings['display_recent_bar'] = !empty($settings['number_recent_posts']) ? $settings['number_recent_posts'] : 0;
	$settings['show_member_bar'] &= allowedTo('view_mlist');
	$context['show_stats'] = allowedTo('view_stats') && !empty($modSettings['trackStats']);
	$context['show_member_list'] = allowedTo('view_mlist');
	$context['show_who'] = allowedTo('who_view') && !empty($modSettings['who_enabled']);

	// Load the calendar?
	if (!empty($modSettings['cal_enabled']) && allowedTo('calendar_view'))
	{
		// Retrieve the calendar data (events, birthdays, holidays).
		$eventOptions = array(
			'include_holidays' => $modSettings['cal_showholidays'] > 1,
			'include_birthdays' => $modSettings['cal_showbdays'] > 1,
			'include_events' => $modSettings['cal_showevents'] > 1,
			'num_days_shown' => empty($modSettings['cal_days_for_index']) || $modSettings['cal_days_for_index'] < 1 ? 1 : $modSettings['cal_days_for_index'],
		);
		$context += cache_quick_get('calendar_index_offset_' . ($user_info['time_offset'] + $modSettings['time_offset']), 'Subs-Calendar.php', 'cache_getRecentEvents', array($eventOptions));

		// Whether one or multiple days are shown on the board index.
		$context['calendar_only_today'] = $modSettings['cal_days_for_index'] == 1;

		// This is used to show the "how-do-I-edit" help.
		$context['calendar_can_edit'] = allowedTo('calendar_edit_any');
	}
	else
		$context['show_calendar'] = false;

	$context['page_title'] = sprintf($txt['forum_index'], $context['forum_name']);
}

// Collapse or expand a category
function CollapseCategory()
{
	global $user_info, $sourcedir, $context;

	// Just in case, no need, no need.
	$context['robot_no_index'] = true;

	checkSession('request');

	if (!isset($_GET['sa']))
		fatal_lang_error('no_access', false);

	// Check if the input values are correct.
	if (in_array($_REQUEST['sa'], array('expand', 'collapse', 'toggle')) && isset($_REQUEST['c']))
	{
		// And collapse/expand/toggle the category.
		require_once($sourcedir . '/Subs-Categories.php');
		collapseCategories(array((int) $_REQUEST['c']), $_REQUEST['sa'], array($user_info['id']));
	}

	// And go back to the board index.
	BoardIndex();
}

?>

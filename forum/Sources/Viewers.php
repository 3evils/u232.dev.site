<?php
/**********************************************************************************
* Viewers.php                                                                     *
***********************************************************************************
* SMF: Simple Machines Forum                                                      *
* Open-Source Project Inspired by Zef Hemel (zef@zefhemel.com)                    *
* =============================================================================== *
* Software Version:           SMF 2.0 RC5                                         *
* Software by:                Simple Machines (http://www.simplemachines.org)     *
* Copyright 2006-2010 by:     Simple Machines LLC (http://www.simplemachines.org) *
*           2001-2006 by:     Lewis Media (http://www.lewismedia.com)             *
* Support, News, Updates at:  http://www.simplemachines.org                       *
***********************************************************************************
* This program is free software; you may redistribute it and/or modify it under   *
* the terms of the provided license as published by Simple Machines LLC.          *
*                                                                                 *
* This program is distributed in the hope that it is and will be useful, but      *
* WITHOUT ANY WARRANTIES; without even any implied warranty of MERCHANTABILITY    *
* or FITNESS FOR A PARTICULAR PURPOSE.                                            *
*                                                                                 *
* See the "license.txt" file for details of the Simple Machines license.          *
* The latest version can always be found at http://www.simplemachines.org.        *
***********************************************************************************
*                                                                             	  *
* This file is distributed within Board Viewers Mod.                              *
* Mod by HarzeM                                                                   *
*                                                                                 *
**********************************************************************************/

if (!defined('SMF'))
	die('Hacking attempt...');

/*	This file is distributed within Board Viewers Mod for SMF. Its aim is to
	display a popup window when someone clicks on the necessary link.

	void BoardViewers()
		- shows a popup for displaying board viewers.
		- detects the board hierarchy to count child boards.
		- counts the members and guests in the board and its child boards.
		- reads the member names and group colors to display in a colorfull way.
		- uses the Help template, popup sub template, no layers.
		- accessed via ?action=viewers;board=??;
	
	array getBoardList()
		- returns all level child boards of a board.
		- uses getBoardsRecursive function to detect parents.
	
	boolean getBoardsRecursive()
		- uses recursion to detect whenther a given board is any level child of another board.
		- returns boolean value.
*/


function BoardViewers()
{

	global $txt, $context, $board, $smcFunc, $modSettings, $scripturl, $user_info;

	// No pass after this line!
	if (empty($modSettings['enable_board_viewers']) || empty($modSettings['boardViewersClickable']) || empty($board))
		redirectexit($scripturl);
	
	// Load the admin help language file and template.
	loadLanguage('Help');
	loadTemplate('Help');

	// Don't show any template layers, just the popup sub template.
	$context['template_layers'] = array();
	$context['sub_template'] = 'popup';

	$boards = array();
	// Read boards from the database.
	$result = $smcFunc['db_query']('', '
		SELECT id_board, id_parent, name
		FROM {db_prefix}boards',
		array(
		)
	);
	while($row = $smcFunc['db_fetch_assoc']($result))
	{
		$boards[$row['id_board']] = isset($row['id_parent']) ? $row['id_parent'] : 0;
		// also get the name of our dear board :)
		if($row['id_board'] == $board)
			$boardname = $row['name'];
	}

	// Get the boards that is childs of the board we are checking into.
	$boardList = getBoardList($boards);
	$board_query = array();
	foreach($boardList as $b)
		$board_query[] = "INSTR(url, 's:5:\"board\";i:".$b.";')";
	
	// get the users that are on the boards we are interested in looking.
	$request = $smcFunc['db_query']('', '
		SELECT session, url, id_member
		FROM {db_prefix}log_online
		WHERE {raw:boards}',
		array(
			'boards' => implode(" OR ", $board_query),
		)
	);

	// create some variables.
	$members_in_this = 0;
	$guests_in_this = 0;
	$members_in_child = 0;
	$guests_in_child = 0;
	
	$board_viewers = array();
	// for each user ...
	while($row = $smcFunc['db_fetch_assoc']($request))
	{
		// no user ?! useless forum!
		if (empty($row['session']))
			continue;
	
		$viewerdata = @unserialize($row['url']);

		// if a member, but not you of course. You are just looking at the viewers, not the board itself!
		if($row['id_member'] > 0 && $row['id_member'] != $user_info['id'])
			$board_viewers[$row['id_member']] = $viewerdata['board'];
		elseif($row['id_member'] == 0)
		{
			if($viewerdata['board'] == $board)
				$guests_in_this ++;
			else
				$guests_in_child ++;
		}
			
	}
	
	// If we are a guest, remove ourselves from the list.
	if($user_info['id'] == 0 && $guests_in_this > 0)
		$guests_in_this--;
		
	$smcFunc['db_free_result']($request);

	$member_list_this = '';
	$member_list_child = '';
	// If we have any members at all
	if($board_viewers != array())
	{
		$member_array = array();
		foreach($board_viewers as $id => $dummy)
			$member_array[] = $id;

		// Now get the user names and colors.
		$request = $smcFunc['db_query']('', '
			SELECT mem.id_member as id_member, mem.real_name as name, mg.online_color as color
			FROM {db_prefix}members as mem
				LEFT JOIN {db_prefix}membergroups as mg ON (mem.id_group = mg.id_group)
			WHERE FIND_IN_SET(mem.id_member , {string:members})',
			array(
				'members' => implode(",",$member_array),
			)
		);
		while($row = $smcFunc['db_fetch_assoc']($request))
		{
			// add a member to the list
			$dummy = '<li><a href="'.$scripturl.'?action=profile;u='.$row['id_member'].'" target="_blank"><span'.
				(!empty($row['color']) ? ' style="color:'.$row['color'].'"' : '')
				.'>'.$row['name'].'</span></a></li>';
			// decide which list to add the member
			if($board == $board_viewers[$row['id_member']])
			{
				$member_list_this .= $dummy;
				$members_in_this ++;
			}
			else
			{
				$member_list_child .= $dummy;
				$members_in_child ++;
			}
		}
	}
	
	$context['help_text'] = '';
	if($members_in_this + $guests_in_this + $members_in_child + $guests_in_child == 0)
		$context['help_text'] .= $txt['bv_members_guests_none'];
	
	if($members_in_this + $guests_in_this > 0)
		$context['help_text'] .= sprintf($txt['bv_members_guests_this'],$members_in_this ,$guests_in_this);
	if($members_in_this > 0)
		$context['help_text'] .= '<br/><br/><b>' . $txt['bv_members'] . ':</b><ul>' . $member_list_this . '</ul>';
		
	if($members_in_child + $guests_in_child > 0)
		$context['help_text'] .= sprintf($txt['bv_members_guests_child'],$members_in_child ,$guests_in_child);
	if($members_in_child > 0)
		$context['help_text'] .= '<br/><br/><b>' . $txt['bv_members'] . ':</b><ul>' . $member_list_child . '</ul>';
	
	
	// Set the page title to something relevant.
	$context['page_title'] = $context['forum_name'] . ' - ' . $boardname;

	
	 
}

function getBoardList($boards)
{
	global $board, $db_prefix;
	$collected =  array();
	// we have a trivial board in the list!
	$collected[] = $board;
	
	foreach($boards as $b => $p) // board => parent
	{
		// If the parent is our board, record the child.
		// Or if it has a parent, check whether one of the parents is our board
		if($p == $board || ((!empty($p) && getBoardsRecursive($b, $boards, $collected))))
			$collected[] = $b;
	}
	
	return $collected;

}
function getBoardsRecursive($b, $boards, $collected)
{
	global $board;
	// If the parent is our board, store it.
	if($boards[$b] == $board || (!empty($boards[$b]) && getBoardsRecursive($boards[$b], $boards, $collected)))
		return true;

	return false;
		
}
?>
<?php
/**********************************************************************************
* Subs-TopicSolved.php                                                            *
***********************************************************************************
* TopicSolved                                                                     *
* =============================================================================== *
* Software Version:           TopicSolved 1.1.1                                   *
* Software by:                Blue Dream (http://www.simpleportal.net)            *
* Copyright 2006-2008 by:     Blue Dream (http://www.simpleportal.net)            *
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
**********************************************************************************/

if (!defined('SMF'))
	die('Hacking attempt...');

/*	This file is responsible for taking care of TopicSolved actions.
	It does them with the following functions:

	void TopicSolved()
		- deals with topic solved feature.
		- toggles between solved/not solved.
		- changes the topic icon accordingly.
		- logs the changes.
		- requires solve_topic_own/any permission.
 		- returns to the last post of topic after it is done.
		- accessed via ?action=topicsolved.

*/

// Makes the topic solved changes.
function TopicSolved()
{
	global $smcFunc, $topic, $board, $board_info, $user_info;

	// We can't do this without a topic.
	if (empty($topic))
		fatal_lang_error('not_a_topic', false);

	// Better safe than sorry...
	checkSession('get');

	// Make sure that we are in a "topic solved" board.
	if (!$board_info['topic_solved'])
		fatal_lang_error('topic_solved_no_board', false);

	// Let's get some info about the topic.
	$request = $smcFunc['db_query']('', '
		SELECT id_member_started, id_first_msg, id_last_msg, is_solved
		FROM {db_prefix}topics
		WHERE id_topic = {int:topic}
		LIMIT {int:limit}',
		array(
			'topic' => $topic,
			'limit' => 1,
		)
	);
	$row = $smcFunc['db_fetch_assoc']($request);
	$smcFunc['db_free_result']($request);

	// Check if he is allowed.
	if (!allowedTo('solve_topic_any') && $user_info['id'] == $row['id_member_started'])
		isAllowedTo('solve_topic_own');
	else
		isAllowedTo('solve_topic_any');

	// Change the status.
	$smcFunc['db_query']('', '
		UPDATE {db_prefix}topics
		SET is_solved = {int:is_solved}
		WHERE id_topic = {int:topic}
		LIMIT {int:limit}',
		array(
			'topic' => $topic,
			'is_solved' => empty($row['is_solved']) ? 1 : 0,
			'limit' => 1,
		)
	);

	// Change the icon.
	$smcFunc['db_query']('', '
		UPDATE {db_prefix}messages
		SET icon = {string:icon}
		WHERE id_msg = {int:msg}
		LIMIT {int:limit}',
		array(
			'msg' => $row['id_first_msg'],
			'icon' => empty($row['is_solved']) ? 'topicsolved' : 'xx',
			'limit' => 1,
		)
	);

	// Do some logging, only for moderators though...
	if ($user_info['id'] != $row['id_member_started'])
		logAction(empty($row['is_solved']) ? 'solve' : 'not_solve', array('topic' => $topic, 'board' => $board), 'topic_solved');

	// Take us back to last post.
	redirectexit('topic=' . $topic . '.msg' . $row['id_last_msg'] . '#new');
}

?>
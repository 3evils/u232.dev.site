<?php
/**********************************************************************************
* POST RATINGS 1.3                                                                *
***********************************************************************************
* Created by:            Solomon Closson (aka: SoLoGHoST)     					  *
* Copyright 2009 by:     Solomon Closson (http://graphicsmayhem.com)			  *
* =============================================================================== *
* PostRatings.php 											                      *
**********************************************************************************/

if (!defined('SMF'))
	die('Hacking attempt...');


// main function that gets called with ?action=postratings
function PostRatings()
{
	global $context, $txt, $modSettings;
	
	// Guests must login first!
	is_not_guest();
	
	// is it enabled?
	if (empty($modSettings['post_ratings_enable']))
		fatal_error($txt['post_ratings_disabled'], false);

	// what we'll need	
	$context['firstPostEnabled'] = !empty($modSettings['post_ratings_first_post']) ? true : false;
	$context['lock_disabled'] = !empty($modSettings['post_ratings_disable_locked_topic']) ? true : false;

	// all passed, move along...
	$subActions = array(
		// Administrative
		'deleteall' => 'DeleteRatings',
		'enable' => 'EnablePostRatings',
		'disable' => 'DisablePostRatings',
		'enabletopic' => 'EnableTopicRatings',
		'disabletopic' => 'DisableTopicRatings',
		// Non Administrative
		'rate' => 'RatePost',
		'delete' => 'DeleteOwnRating',
	);

	if (isset($_REQUEST['sa']) && isset($subActions[$_REQUEST['sa']]))
		$context['sub_action'] = $_REQUEST['sa'];
	else
		redirectexit();
	
	// follow through...
	$subActions[$context['sub_action']]();
}

function DeleteRatings()
{
	global $smcFunc, $context, $txt;

	isAllowedTo('postratings_administrate');

	validateSession();

	$queryStr = '';
	
	// defaults used by both topic and msg
	$query = array(
		't.id_topic = m.id_topic',
		't.is_ratings != {int:is_zero}',		
	);

	if ($context['lock_disabled'])
		$query[] = 't.locked = {int:is_zero}';

	// do we have a post or topic?
	if (isset($_REQUEST['msg']) && !empty($_REQUEST['msg']))
	{
		$_REQUEST['msg'] = (int) $_REQUEST['msg'];
			
		if (!$context['firstPostEnabled'])
			$query[] = 't.id_first_msg != {int:id_msg}';
		
		// build the string from the array of data...
		foreach ($query as $key => $value)
		{
			$queryStr .= $value;
			$queryStr .= ($key < (count($query) - 1)) ? ' AND ' : '';
		}

		$request = $smcFunc['db_query']('', '
		  SELECT m.id_msg
		  FROM {db_prefix}messages as m
		  INNER JOIN {db_prefix}topics as t ON (' . $queryStr . ')
		  WHERE m.id_topic = t.id_topic AND m.id_msg = {int:id_msg} AND m.ratings_enabled != {int:is_zero} AND m.total_ratings != {int:is_zero} LIMIT 1',
		  array(
			 'id_msg' => $_REQUEST['msg'],
			 'is_zero' => 0,
		  )
	   );
		   
		if ($smcFunc['db_num_rows']($request) == 0)
			fatal_error($txt['ratings_delete_post_err'], false);	
		   
		list ($id_msg) = $smcFunc['db_fetch_row']($request);
		$smcFunc['db_free_result']($request);
		
		// all passed, now delete the post rating	
		DeletePostRatings($id_msg);
	} 
	elseif (isset($_REQUEST['topic']) && !empty($_REQUEST['topic']))
	{
		$_REQUEST['topic'] = (int) $_REQUEST['topic'];
		
		// build the string from the array of data...
		foreach ($query as $key => $value)
		{
			$queryStr .= $value;
			$queryStr .= ($key < (count($query) - 1)) ? ' AND ' : '';
		}

		// you can not delete ratings from disabled posts/topics
		$request = $smcFunc['db_query']('', '
		  SELECT m.id_msg
		  FROM {db_prefix}messages AS m
		  INNER JOIN {db_prefix}topics AS t ON (' . $queryStr . ')
		  WHERE m.id_topic = {int:id_topic} AND m.ratings_enabled != {int:is_zero} AND m.total_ratings != {int:is_zero}' . (!$context['firstPostEnabled'] ? ' AND m.id_msg != t.id_first_msg' : ''),
		  array(
			 'id_topic' => $_REQUEST['topic'],
			 'is_zero' => 0,
		  )
		);
			
		if ($smcFunc['db_num_rows']($request) == 0)
			fatal_error($txt['ratings_delete_topic_err'], false);
					
		$msgIds = array();
		
		while ($row = $smcFunc['db_fetch_assoc']($request))
		{
			$msgIds[] = $row['id_msg'];
		}
		
		$smcFunc['db_free_result']($request);

		// shall we?
		DeleteTopicRatings($msgIds, $_REQUEST['topic']);
	}
	else
		fatal_error($txt['ratings_delete_no_msg_topic'], false);
}

// function that deletes all ratings from a specific post!
function DeletePostRatings($messageId = 0)
{
	global $smcFunc, $txt;

	$messageId = !empty($messageId) ? (int) $messageId : fatal_error($txt['ratings_delete_no_msg_topic'], false);

	// Kill em' all!
	$smcFunc['db_query']('', '
			DELETE FROM {db_prefix}message_ratings
			WHERE id_msg = {int:id_msg}',
			array(
				'id_msg' => $messageId,
			)
		);
		
	// reset to default values
	$smcFunc['db_query']('', '
				UPDATE {db_prefix}messages
				SET total_ratings = {int:no_rating},
					rating = {int:no_rating}
				WHERE id_msg = {int:id_msg}
				LIMIT 1',
				array(
					'id_msg' => $messageId,
					'no_rating' => 0,
				)
			);
	
	// Oh won't you please take me home...
	$result = $smcFunc['db_query']('', '
		  SELECT id_topic
		  FROM {db_prefix}messages
		  WHERE id_msg = {int:id_msg}',
		  array(
			 'id_msg' => $messageId,
		  )
	);
   	list ($topicId) = $smcFunc['db_fetch_row']($result);
	$smcFunc['db_free_result']($result);	

	redirectexit('topic=' . $topicId . '.msg' . $messageId . '#msg' . $messageId);	
}

// function that deletes all ratings from a topic, pending 1st post or not
function DeleteTopicRatings($id_msgs = array(), $topicId = 0)
{
	global $smcFunc, $txt;
	

	// Kill em' all!
	$smcFunc['db_query']('', '
			DELETE FROM {db_prefix}message_ratings
			WHERE id_msg IN ({array_int:messages_list})',
			array(
				'messages_list' => $id_msgs,
			)
		);
		
	// reset to default values
	$smcFunc['db_query']('', '
			UPDATE {db_prefix}messages
			SET total_ratings = {int:no_rating},
				rating = {int:no_rating}
			WHERE id_msg IN ({array_int:messages_list})',
			array(
				'messages_list' => $id_msgs,
				'no_rating' => 0,
			)
		);	

	// back to the main topic page.
	redirectexit('topic=' . $topicId . '.0');
}

function CanRatingsEnableDisable($topicId = 0, $msgId = 0)
{
	global $context, $user_info, $smcFunc, $txt, $ratings_err;
	
	$ratings_enable_allowed = allowedTo('ratings_enable_post_any') || allowedTo('ratings_enable_post_own') ? true : false;
	
	if (!$ratings_enable_allowed)
	{
		$ratings_err = 'not_allowed';
		return false;
	}
	else
		$ratings_err = '';

	$condStr = '';
	$on_queryStr = '';
	$condition = array(
		'm.id_msg = {int:id_msg}',
		'm.id_topic = {int:id_topic}',
	);

	if (!allowedTo('ratings_enable_post_any'))
		$condition[] = 'm.id_member = {int:id_member}';

	$on_query = array(
		't.id_topic = {int:id_topic}',
		't.is_ratings != {int:is_zero}',			
	);
		
	if ($context['lock_disabled'])
		$on_query[] = 't.locked = {int:is_zero}';
		
	if (!$context['firstPostEnabled'])
		$on_query[] = 't.id_first_msg != {int:id_msg}';


	foreach ($condition as $key => $value)
	{
		$condStr .= $value;
		$condStr .= ($key < (count($condition) - 1)) ? ' AND ' : '';
	}
	
	foreach ($on_query as $key => $value)
	{
		$on_queryStr .= $value;
		$on_queryStr .= ($key < (count($on_query) - 1)) ? ' AND ' : '';
	}

	// lets get it on...ohh, lets get it on.
	if (!empty($msgId))
	{
		$request = $smcFunc['db_query']('', '
			SELECT m.id_msg
			FROM {db_prefix}messages AS m
			INNER JOIN {db_prefix}topics AS t ON (' . $on_queryStr . ') 
			WHERE ' . $condStr . '
			LIMIT 1',
			array(
				'id_topic' => $topicId,
				'is_zero' => 0,
				'id_msg' => $msgId,
				'id_member' => $user_info['id'],
			)
		);
		if ($smcFunc['db_num_rows']($request) == 0)
			return false;

		$smcFunc['db_free_result']($request);
	}

	return true;
}

function CanRatingsEnableTopic($topicId = 0)
{
	global $context, $smcFunc, $txt, $user_info, $modSettings, $ratings_err;
	
	if (!empty($topicId))
		$topicId = (int) $topicId;
	else
		return false;
	
	$perm_allowed = allowedTo('ratings_enable_topic_any') || allowedTo('ratings_enable_topic_own') ? true : false;
	
	// permissions first.
	if (!$perm_allowed)
	{
		$ratings_err = 'not_allowed';
		return false;
	}
	else
		$ratings_err = '';

	$condStr = '';

	$condition = array(
		'id_topic = {int:id_topic}',
	);

	if (!allowedTo('ratings_enable_topic_any'))
		$condition[] = 'id_member_started = {int:id_member}';
		
	if ($context['lock_disabled'])
		$condition[] = 'locked = {int:is_zero}';


	foreach ($condition as $key => $value)
	{
		$condStr .= $value;
		$condStr .= ($key < (count($condition) - 1)) ? ' AND ' : '';
	}

	$request = $smcFunc['db_query']('', '
				SELECT id_topic
				FROM {db_prefix}topics
				WHERE ' . $condStr . '
				LIMIT 1',
				array(
					'id_topic' => $topicId,
					'is_zero' => 0,
					'id_member' => $user_info['id'],
				)
			);
			if ($smcFunc['db_num_rows']($request) == 0)
				return false;

			$smcFunc['db_free_result']($request);
	
	return true;
}

function EnableTopicRatings()
{
	global $txt, $smcFunc, $ratings_err;
	
	$_REQUEST['topic'] = isset($_REQUEST['topic']) && !empty($_REQUEST['topic']) ? (int) $_REQUEST['topic'] : fatal_error($txt['postratings_no_topic'], false);
	
	checkSession('get');
	
	if (!CanRatingsEnableTopic($_REQUEST['topic']))
		if (!empty($ratings_err))
			fatal_error($txt['cannot_ratings_enable_disable'], false);
		else
			fatal_error($txt['topic_enable_disable_error'], false);


	$smcFunc['db_query']('', '
				UPDATE {db_prefix}topics
				SET is_ratings = {int:enable}
				WHERE id_topic = {int:id_topic}
				LIMIT 1',
				array(
					'enable' => 1,
					'id_topic' => $_REQUEST['topic'],
				)
			);

	redirectexit('topic=' . $_REQUEST['topic'] . '.0');
}

function DisableTopicRatings()
{
	global $txt, $smcFunc, $ratings_err;
	
	$_REQUEST['topic'] = isset($_REQUEST['topic']) && !empty($_REQUEST['topic']) ? (int) $_REQUEST['topic'] : fatal_error($txt['postratings_no_topic'], false);
	
	checkSession('get');
		
	if (!CanRatingsEnableTopic($_REQUEST['topic']))
		if (!empty($ratings_err))
			fatal_error($txt['cannot_ratings_enable_disable'], false);
		else
			fatal_error($txt['topic_enable_disable_error'], false);
		
		
	$smcFunc['db_query']('', '
				UPDATE {db_prefix}topics
				SET is_ratings = {int:disable}
				WHERE id_topic = {int:id_topic}
				LIMIT 1',
				array(
					'disable' => 0,
					'id_topic' => $_REQUEST['topic'],
				)
			);

	redirectexit('topic=' . $_REQUEST['topic'] . '.0');	
}

function EnablePostRatings()
{
	global $txt, $smcFunc, $ratings_err;
	
	$_REQUEST['msg'] = isset($_REQUEST['msg']) && !empty($_REQUEST['msg']) ? (int) $_REQUEST['msg'] : fatal_error($txt['postratings_no_message'], false);
	$_REQUEST['topic'] = isset($_REQUEST['topic']) && !empty($_REQUEST['topic']) ? (int) $_REQUEST['topic'] : fatal_error($txt['postratings_no_topic'], false);
	
	checkSession('get');
	
	if (!CanRatingsEnableDisable($_REQUEST['topic'], $_REQUEST['msg']))
		if (!empty($ratings_err))
			fatal_error($txt['cannot_ratings_enable_disable'], false);
		else
			fatal_error($txt['postratings_enable_disable_error'], false);	
	
		
	$smcFunc['db_query']('', '
				UPDATE {db_prefix}messages
				SET ratings_enabled = {int:enable}
				WHERE id_msg = {int:id_msg}
					AND id_topic = {int:id_topic}',
				array(
					'enable' => 1,
					'id_msg' => $_REQUEST['msg'],
					'id_topic' => $_REQUEST['topic'],
				)
			);

	redirectexit('topic=' . $_REQUEST['topic'] . '.msg' . $_REQUEST['msg'] . '#msg' . $_REQUEST['msg']);
}

// function for disabling all ratings within a post
function DisablePostRatings()
{
	global $txt, $smcFunc, $ratings_err;

	$_REQUEST['msg'] = isset($_REQUEST['msg']) && !empty($_REQUEST['msg']) ? (int) $_REQUEST['msg'] : fatal_error($txt['postratings_no_message'], false);
	$_REQUEST['topic'] = isset($_REQUEST['topic']) && !empty($_REQUEST['topic']) ? (int) $_REQUEST['topic'] : fatal_error($txt['postratings_no_topic'], false);

	checkSession('get');

	if (!CanRatingsEnableDisable($_REQUEST['topic'], $_REQUEST['msg']))
		if (!empty($ratings_err))
			fatal_error($txt['cannot_ratings_enable_disable'], false);
		else
			fatal_error($txt['postratings_enable_disable_error'], false);
		
	$smcFunc['db_query']('', '
				UPDATE {db_prefix}messages
				SET ratings_enabled = {int:disable}
				WHERE id_msg = {int:id_msg}
					AND id_topic = {int:id_topic}',
				array(
					'disable' => 0,
					'id_msg' => $_REQUEST['msg'],
					'id_topic' => $_REQUEST['topic'],
				)
			);

	redirectexit('topic=' . $_REQUEST['topic'] . '.msg' . $_REQUEST['msg'] . '#msg' . $_REQUEST['msg']);
}	

// function used to delete a users rating from within a post.
function DeleteOwnRating()
{
	global $context, $txt, $smcFunc, $modSettings, $user_info;
	
	checkSession('get');
	
	isAllowedTo('postratings_delete_own_rating');

	$post_id = isset($_GET['post']) && !empty($_GET['post']) ? (int) $_GET['post'] : 0;

	if (!empty($post_id))
	{
		$msgId = (int) $_REQUEST['pr_del' . $post_id];
		$topicId = (int) $_REQUEST['pr_topicid' . $post_id];
	}
	// check the message
	if (empty($msgId))
		fatal_error($txt['postratings_no_post_selected'], false);

	// check the topic
	if (empty($topicId))
		fatal_error($txt['postratings_no_topic_selected'], false);
	
	$cond = '';
	$onTop = '';

	$values = array(
		'id_msg' => $msgId,
		'id_member' => $user_info['id'],
		'is_zero' => 0,
		'id_topic' => $topicId,
	);

	$condition = array(
		'm.id_msg = {int:id_msg}',
		'm.id_member != {int:id_member}',
		'm.ratings_enabled != {int:is_zero}',
	);

	$on_topics = array(
		't.id_topic = {int:id_topic}',
		't.is_ratings != {int:is_zero}',
	);

	// only unlocked topics
	if ($context['lock_disabled'])
		$on_topics[] = 't.locked = {int:is_zero}';

	// is it the first post and disabled?
	if (!$context['firstPostEnabled'])
		$on_topics[] = 't.id_first_msg != {int:id_msg}';

	// ratings limit?
	if (!empty($modSettings['post_ratings_limit_count']))
	{
		$condition[] = 'm.total_ratings < {int:ratings_limit}';
		$values = array_merge($values, array(
			'ratings_limit' => $modSettings['post_ratings_limit_count'],
			)
		);
	}
	// build em'
	foreach ($on_topics as $key => $value)
	{
		$onTop .= $value;
		$onTop .= ($key < (count($on_topics) - 1)) ? ' AND ' : '';
	}

	foreach ($condition as $key => $value)
	{
		$cond .= $value;
		$cond .= ($key < (count($condition) - 1)) ? ' AND ' : '';
	}

	$request = $smcFunc['db_query']('', '
			SELECT
				m.id_msg, m.id_last_rating, mr.value, mr.id_rating
			FROM {db_prefix}messages as m
			LEFT JOIN {db_prefix}message_ratings as mr ON (mr.id_msg = {int:id_msg} AND mr.id_member = {int:id_member})
			INNER JOIN {db_prefix}topics as t ON (' . $onTop . ')
			WHERE ' . $cond . '
			LIMIT 1',
				$values
			);

	if ($smcFunc['db_num_rows']($request) == 0)
		fatal_error($txt['postratings_delete_post_error'], false);

	list ($id_msg, $last_rating, $ur_rating, $id_rating) = $smcFunc['db_fetch_row']($request);
	$smcFunc['db_free_result']($request);

	// be sure the rating has something in it.
	if (!empty($ur_rating))
	{
		// get the ratings strength level
		$ratingLevel = (int) $modSettings['post_ratings_level'];
		
		// Delete their rating...
		$smcFunc['db_query']('', '
			DELETE FROM {db_prefix}message_ratings
			WHERE id_msg = {int:id_msg} AND id_member = {int:id_member}',
			array(
				'id_msg' => $id_msg,
				'id_member' => $user_info['id'],
			)
		);		
		
		// It couldn't be empty, but just in case...
		if (!empty($id_rating) && !empty($last_rating) && $id_rating == $last_rating)
		{
			// get a new rating id for id_last_rating
			$result = $smcFunc['db_query']('', '
				SELECT
					id_rating
				FROM {db_prefix}message_ratings
				WHERE id_msg = {int:id_msg}
				ORDER BY date DESC
				LIMIT 1',
				array(
					'id_msg' => $id_msg,
				  )
			);
		
			list ($rating_id) = $smcFunc['db_fetch_row']($result);
			$smcFunc['db_free_result']($result);
			
			$rating_id = !empty($rating_id) ? (int) $rating_id : 0;
		}

		// calculate the new sum...
		$request = $smcFunc['db_query']('', '
			SELECT
				SUM(value)
			FROM {db_prefix}message_ratings
			WHERE id_msg = {int:id_msg}',
			array(
				'id_msg' => $id_msg,	
			  )
		);
	
		list ($total_value) = $smcFunc['db_fetch_row']($request);
		$smcFunc['db_free_result']($request);
		
		// Update the totalratings, overall score, and last rating id (if different)...
		$smcFunc['db_query']('', '
			UPDATE {db_prefix}messages
			SET total_ratings = total_ratings - 1,
				rating = (({int:sum_values} / (total_ratings * 5) * 100) * {int:rating_level})' . (!empty($rating_id) ? ', 
				id_last_rating = {int:last_rating}' : '') . '
			WHERE id_msg = {int:id_msg}
			LIMIT 1',
			array(
				'id_msg' => $id_msg,
				'sum_values' => !empty($total_value) ? $total_value : 0,
				'last_rating' => $rating_id,
				'rating_level' => $ratingLevel,
			)
		);	
	}
	
	// All done, go back to the message...
	redirectexit('topic=' . $topicId . '.msg' . $msgId . '#msg' . $msgId);
}

// function used when rating, or editing their rating for a post.
function RatePost()
{
	global $context, $txt, $smcFunc, $modSettings, $user_info;
	
	checkSession('get');

	// not allowed to rate or edit rating, goodbye!
	if (!allowedTo('postratings_rate') && !allowedTo('postratings_edit_own_rating'))
		fatal_error($txt['cannot_postratings_rate'], false);
	
	$post_id = isset($_GET['post']) && !empty($_GET['post']) ? (int) $_GET['post'] : 0;
	
	// if no rating, don't bother getting message and topic
	if (!empty($_POST['rating']) && !empty($post_id))
	{
		$rating = (int) $_POST['rating'];
		$msgId = (int) $_REQUEST['pr_messageid' . $post_id];
		$topicId = (int) $_REQUEST['pr_topicid' . $post_id];
	}
	
	// check the rating
	if (empty($rating))
		fatal_error($txt['postratings_no_rating_selected'], false);
	
	// check the topic
	if (empty($topicId))
		fatal_error($txt['postratings_no_topic_selected'], false);
		
	// check the message	
	if (empty($msgId))
		fatal_error($txt['postratings_no_post_selected'], false);

	$cond = '';
	$onTop = '';

	$condition = array(
		'm.id_msg = {int:id_msg}',
		'm.id_member != {int:id_member}',
		'm.ratings_enabled != {int:is_zero}',
	);

	$on_topics = array(
		't.id_topic = {int:id_topic}',
		't.is_ratings != {int:is_zero}',
	);

	// can they edit their own rating?
	if (!allowedTo('postratings_edit_own_rating'))
		$on_topics[] = 't.id_member_started != {int:id_member}';

	// is topic locked, and setting set? If so, only return unlocked topics	
	if ($context['lock_disabled'])
		$on_topics[] = 't.locked = {int:is_zero}';

	// is first post rating disabled?  If so, only return posts that are not the first post
	if (!$context['firstPostEnabled'])
		$on_topics[] = 't.id_first_msg != {int:id_msg}';

	foreach ($on_topics as $key => $value)
	{
		$onTop .= $value;
		$onTop .= ($key < (count($on_topics) - 1)) ? ' AND ' : '';
	}

	foreach ($condition as $key => $value)
	{
		$cond .= $value;
		$cond .= ($key < (count($condition) - 1)) ? ' AND ' : '';
	}

	$request = $smcFunc['db_query']('', '
			SELECT
				m.id_msg, m.total_ratings, mr.value, mr.id_rating
			FROM {db_prefix}messages as m
			LEFT JOIN {db_prefix}message_ratings as mr ON (mr.id_msg = {int:id_msg} AND mr.id_member = {int:id_member})
			INNER JOIN {db_prefix}topics as t ON (' . $onTop . ')
			WHERE ' . $cond . '
			LIMIT 1',
			array(
				'id_msg' => $msgId,
				'id_member' => $user_info['id'],
				'is_zero' => 0,
				'id_topic' => $topicId,
			  )
			);

			if ($smcFunc['db_num_rows']($request) == 0)
				fatal_error($txt['postratings_rate_post_error'], false);

			list ($id_msg, $totalratings, $value, $id_rating) = $smcFunc['db_fetch_row']($request);

			$smcFunc['db_free_result']($request);
	
	
	// is limit reached and not allowed to edit your rating...
	if (allowedTo('postratings_edit_own_rating') && !empty($value))
	{
		// Already rated...
		$already_rated = true;
	} 
	else
	{	// else check if limit has been reached
		if (!empty($modSettings['post_ratings_limit_count']) && $modSettings['post_ratings_limit_count'] <= $totalratings)	
			fatal_error('This post has reached it\'s limit of ratings already', false);
		
		$already_rated = false;
	}
	
	// get the ratings strength level
	$ratingLevel = (int) $modSettings['post_ratings_level'];

	// This should never occur, but if it does, change it to neutral
	if ($rating < 1 || $rating > 5)
		$rating = 3;
		
	if ($already_rated)
	{
		// just double checking here...
		if (!allowedTo('postratings_edit_own_rating'))
			fatal_error($txt['postratings_not_able_to_edit'], false);

		// if rating the same, no use in updating it...
		if ($value == $rating)
			redirectexit('topic=' . $topicId . '.msg' . $msgId . '#msg' . $msgId);
			
		// update the value and time of changed rating...
		$smcFunc['db_query']('', '
				UPDATE {db_prefix}message_ratings
				SET value = {int:rating},
					date = {int:current_time}
				WHERE id_msg = {int:id_msg} AND id_member = {int:id_member}',
				array(
					'id_msg' => $msgId,
					'rating' => $rating,
					'id_member' => $user_info['id'],
					'current_time' => time(),
				)
			);

		// Now get the sum of the values. 
		$request = $smcFunc['db_query']('', '
		SELECT
			SUM(value)
		FROM {db_prefix}message_ratings
		WHERE id_msg = {int:id_msg}',
		array(
			'id_msg' => $msgId,	
		  )
		);
	
		list ($total_value) = $smcFunc['db_fetch_row']($request);
		$smcFunc['db_free_result']($request);
		
		// update just the overall rating score of the post
		$smcFunc['db_query']('', '
			UPDATE {db_prefix}messages
			SET rating = (({int:sum_values} / (total_ratings * 5) * 100) * {int:rating_level}),
			id_last_rating = {int:id_rating}
			WHERE id_msg = {int:id_msg}',
			array(
				'id_msg' => $msgId,
				'sum_values' => $total_value,
				'id_rating' => $id_rating,
				'rating_level' => $ratingLevel,
			)
		);
	} else {
		// just double checking here...
		if (!allowedTo('postratings_rate'))
			fatal_error($txt['postratings_not_able_to_rate'], false);
		
		// Insert the new rating
		$smcFunc['db_insert']('',
			'{db_prefix}message_ratings',
			array(
				'id_msg' => 'int', 'id_member' => 'int', 'date' => 'int', 'value' => 'int'
			),
			array(
				$msgId, $user_info['id'], time(), $rating,
			),
			array('id_rating', 'id_msg')
		);

		$id_rating = $smcFunc['db_insert_id']('{db_prefix}message_ratings', 'id_rating');

		// get the sum of all of the ratings...
		$request = $smcFunc['db_query']('', '
		SELECT
			SUM(value)
		FROM {db_prefix}message_ratings
		WHERE id_msg = {int:id_msg}',
		array(
			'id_msg' => $msgId,	
		  )
		);
	
		list ($total_value) = $smcFunc['db_fetch_row']($request);
		$smcFunc['db_free_result']($request);

		// Update the total ratings and overall rating score of the post.
		$smcFunc['db_query']('', '
			UPDATE {db_prefix}messages
			SET total_ratings = total_ratings + 1,
				rating = (({int:sum_values} / (total_ratings * 5) * 100) * {int:rating_level}),
				id_last_rating = {int:last_rating}
			WHERE id_msg = {int:id_msg}
			LIMIT 1',
			array(
				'id_msg' => $msgId,
				'sum_values' => $total_value,
				'last_rating' => $id_rating,
				'rating_level' => $ratingLevel,
			)
		);
	}

	// Finished, Go back to where you came from
	redirectexit('topic=' . $topicId . '.msg' . $msgId . '#msg' . $msgId);
}
	
?>
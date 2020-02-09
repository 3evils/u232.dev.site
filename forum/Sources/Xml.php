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

/*	This file maintains all XML-based interaction (mainly XMLhttp).

	void GetJumpTo()

*/

function XMLhttpMain()
{
	loadTemplate('Xml');

	$sub_actions = array(
		'jumpto' => array(
			'function' => 'GetJumpTo',
		),
		'messageicons' => array(
			'function' => 'ListMessageIcons',
		),
		'reputation' => array(
			'function' => 'Reputation',
		),
	);
	if (!isset($_REQUEST['sa'], $sub_actions[$_REQUEST['sa']]))
		fatal_lang_error('no_access', false);

	$sub_actions[$_REQUEST['sa']]['function']();
}

// Get a list of boards and categories used for the jumpto dropdown.
function GetJumpTo()
{
	global $user_info, $context, $smcFunc, $sourcedir;

	// Find the boards/cateogories they can see.
	require_once($sourcedir . '/Subs-MessageIndex.php');
	$boardListOptions = array(
		'use_permissions' => true,
		'selected_board' => isset($context['current_board']) ? $context['current_board'] : 0,
	);
	$context['jump_to'] = getBoardList($boardListOptions);

	// Make the board safe for display.
	foreach ($context['jump_to'] as $id_cat => $cat)
	{
		$context['jump_to'][$id_cat]['name'] = un_htmlspecialchars(strip_tags($cat['name']));
		foreach ($cat['boards'] as $id_board => $board)
			$context['jump_to'][$id_cat]['boards'][$id_board]['name'] = un_htmlspecialchars(strip_tags($board['name']));
	}

	$context['sub_template'] = 'jump_to';
}

function ListMessageIcons()
{
	global $context, $sourcedir, $board;

	require_once($sourcedir . '/Subs-Editor.php');
	$context['icons'] = getMessageIcons($board);

	$context['sub_template'] = 'message_icons';
}

// Pfft, reloading is for old people!
function Reputation()
{
	global $context, $modSettings, $txt, $user_info, $smcFunc;

	// If the mod is disabled, show an error.
	if (empty($modSettings['karmaMode']))
		fatal_lang_error('feature_disabled', true);

	// If you're a guest or can't do this, blow you off...
	is_not_guest();
	isAllowedTo('karma_edit');

	checkSession('request');

	loadLanguage('Errors');

	// We're gonna do things in a different order here, to facilitate errors.
	$uid = (int) $_POST['uid'];
	$message_id = (int) $_POST['m'];
	$topic_id = (int) $_POST['topic'];
	$points = $user_info['karma_good'] - $user_info['karma_bad'];
	$power = ($points - ($points % $modSettings['karmaBarPower'])) / $modSettings['karmaBarPower'];
	$power = (int) (($power > 0) ? $power : 0);
	$comment = strip_tags($_POST['reputation_comment']);
	$comment = (strlen($comment) > 300) ? substr($comment, 0, 300) : $comment;
	$yesterday = time() - 86400;
	$longtimeago = time() - (60 * 60 * ($modSettings['karmaWaitTime']));
	$hoursAgo = ($modSettings['karmaWaitTime'] < 24) ? $yesterday : $longtimeago;
	$context['error_txt'] = '';

	// If you don't have enough posts, tough luck.
	if ($user_info['posts'] < $modSettings['karmaMinPosts'])
		$context['error_txt'] .= "\n" . sprintf($txt['not_enough_posts_karma'], $modSettings['karmaMinPosts']);

	// And you can't modify your own, punk! (use the profile if you need to, admins!)
	else if (empty($uid) || $uid == $user_info['id'])
		$context['error_txt'] .= "\n" . $txt['cant_change_own_karma'];
	
	// I fart in your general direction!
	else if (strtolower($comment) == 'your mother was a hamster and your father smelt of elderberries!')
		$context['error_txt'] .= "\n...and Saint Attila raised the hand grenade up on high, saying, \"O Lord, bless this Thy hand grenade that with it Thou mayest blow Thine enemies to tiny bits, in Thy mercy.\" And the Lord did grin and the people did feast upon the lambs and sloths and carp and anchovies and orangutans and breakfast cereals, and fruit bats and large chu... *ahem* And the Lord spake, saying, \"First shalt thou take out the Holy Pin, then shalt thou count to three, no more, no less. Three shall be the number thou shalt count, and the number of the counting shall be three. Four shalt thou not count, neither count thou two, excepting that thou then proceed to three. Five is right out. Once the number three, being the third number, be reached, then lobbest thou thy Holy Hand Grenade of Antioch towards thy foe, who being naughty in my sight, shall snuff it.\" Amen.";

	// Applauding or smiting? Don't try to do a cheap javascript injection on me...
	switch ($_POST['type'])
	{
		case 'agree':
			if(!allowedTo('positive_karma'))
				$context['error_txt'] .= "\n" . $txt['karma_cant_agree'];
			$karma_which = 'karma_good';
			break;
		case 'disagree':
			if(!allowedTo('negative_karma'))
				$context['error_txt'] .= "\n" . $txt['karma_cant_disagree'];
			$karma_which = 'karma_bad';
			break;
		default:
			// Should never get here
			$context['error_txt'] .= "\n" . $txt['karma_choose_action'];
	}

	// Going in order of permissions... it only gets added if it passes all of the tests!

	// Used up all of their $modSettings['karmaMaxPerDay'] ?
	if (!empty($modSettings['karmaMaxPerDay']) && ($modSettings['karmaMaxPerDay'] > 0))
	{
		// Find out if this user has done this in the past 24 hours.
		$request = $smcFunc['db_query']('', '
			SELECT log_time
			FROM {db_prefix}log_karma
			WHERE id_executor = {int:current_member}
				AND log_time >= {int:yesterday}
			LIMIT 1',
			array(
				'current_member' => $user_info['id'],
				'yesterday' => $hoursAgo,
			)
		);

		if ($smcFunc['db_num_rows']($request) >= $modSettings['karmaMaxPerDay'])
			$context['error_txt'] .= "\n" . sprintf($txt['karma_maxed_out'], $modSettings['karmaMaxPerDay']);

		$smcFunc['db_free_result']($request);
	}

	// Haven't waited long enough?
	if (!empty($modSettings['karmaWaitTime']) && (!empty($modSettings['karmaTimeRestrictAdmins']) || !allowedTo('moderate_forum')) && $modSettings['karmaWaitTime'] > 0)
	{
		// Find out if this user has done this in the past $longenough.
		$request = $smcFunc['db_query']('', '
			SELECT log_time
			FROM {db_prefix}log_karma
			WHERE id_executor = {int:current_member}
				AND log_time >= {int:waittime}
			LIMIT 1',
			array(
				'current_member' => $user_info['id'],
				'waittime' => $longtimeago,
			)
		);

		if ($smcFunc['db_num_rows']($request) > 0)
		{
			// Give them approximate minutes until they can, to be nice ;)
			list ($log_time) = $smcFunc['db_fetch_row']($request);
			$log_time_wait = ($log_time + (60 * 60 * $modSettings['karmaWaitTime'])) - time(); // seconds
			$log_time_wait = ($log_time_wait - ($log_time_wait % 60)) / 60; // minutes
			$context['error_txt'] .= "\n" . sprintf($txt['karma_please_wait'], $modSettings['karmaWaitTime'], $log_time_wait);
		}

		$smcFunc['db_free_result']($request);
	}

	// Need to spread it around a bit? No, not the flu, silly!
	if (!empty($modSettings['karmaSpreadAround']) && ($modSettings['karmaSpreadAround'] > 0))
	{
		// Grab the latest karmaSpreadAround actions - if the target is in there, throw an error!
		$request = $smcFunc['db_query']('', '
			SELECT id_target
			FROM {db_prefix}log_karma
			WHERE id_executor = {int:current_member}
			ORDER BY log_time DESC
			LIMIT {int:spread}',
			array(
				'current_member' => $user_info['id'],
				'spread' => $modSettings['karmaSpreadAround'],
			)
		);

		if ($smcFunc['db_num_rows']($request) > 0)
			while($row = $smcFunc['db_fetch_assoc']($request))
				if($row['id_target'] == $uid)
					$context['error_txt'] .= "\n" . $txt['karma_spread_around'];

		$smcFunc['db_free_result']($request);
	}

	// One last test - have they already sent rep to this particular post?
	$request = $smcFunc['db_query']('', '
		SELECT log_time
		FROM {db_prefix}log_karma
		WHERE id_executor = {int:executor}
			AND message = {string:message}
		LIMIT 1',
		array(
			'executor' => $user_info['id'],
			'message' => $message_id
		)
	);

	if($smcFunc['db_num_rows']($request) != 0)
		$context['error_txt'] .= "\n" . $txt['karma_sent_twice'];

	$smcFunc['db_free_result']($request);

	// Finally! Now do an update if there's no error
	if($context['error_txt'] == "")
	{
		$request = $smcFunc['db_query']('', '
			UPDATE {db_prefix}members
			SET {raw:which} = {raw:which} + {int:power}
			WHERE id_member = {int:uid}',
			array(
				'power' => $power,
				'uid' => $uid,
				'which' => $karma_which,
			)
		);

		if(!$request)
			$context['error_txt'] .= "\n" . $txt['karma_didnt_update'];

		$action_type = ($power != 0) ? $karma_which : 'karma_disabled';

		$request = $smcFunc['db_query']('', '
			SELECT subject
			FROM {db_prefix}messages
			WHERE id_msg = {int:id_message}
			LIMIT 1',
			array(
				'id_message' => $message_id,
			)
		);

		list ($title) = $smcFunc['db_fetch_row']($request);

		$smcFunc['db_free_result']($request);

		// Log this new action!
		$smcFunc['db_insert']('replace',
			'{db_prefix}log_karma',
			array('action' => 'int', 'id_target' => 'int', 'id_executor' => 'int', 'log_time' => 'int', 'comment' => 'string', 'action_type' => 'string', 'message' => 'int', 'topic' => 'int', 'title' => 'string'),
			array($power, $uid, $user_info['id'], time(), $comment, $action_type, $message_id, $topic_id, $title),
			array('id_target', 'id_executor')
		);
	}

	$context['sub_template'] = 'reputation';
}

?>
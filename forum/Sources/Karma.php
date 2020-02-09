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

/*	This file contains one humble function, which applauds or smites a user.

	void ModifyKarma()
		- gives or takes karma from a user.
		- redirects back to the referrer afterward, whether by javascript or
		  the passed parameters.
		- requires the karma_edit permission, and that the user isn't a guest.
		- depends on the karmaMode, karmaWaitTime, and karmaTimeRestrictAdmins
		  settings.
		- is accessed via ?action=modifykarma.
*/

/******************* Disabled by Advanced Reputation System **********************
**********************************************************************************
// Modify a user's karma the old and disgusting way...
function ModifyKarma_old()
{
	global $modSettings, $txt, $user_info, $topic, $smcFunc, $context;

	// If the mod is disabled, show an error.
	if (empty($modSettings['karmaMode']))
		fatal_lang_error('feature_disabled', true);

	// If you're a guest or can't do this, blow you off...
	is_not_guest();
	isAllowedTo('karma_edit');

	checkSession('get');

	// If you don't have enough posts, tough luck.
	// !!! Should this be dropped in favor of post group permissions?  Should this apply to the member you are smiting/applauding?
	if (!$user_info['is_admin'] && $user_info['posts'] < $modSettings['karmaMinPosts'])
		fatal_lang_error('not_enough_posts_karma', true, array($modSettings['karmaMinPosts']));

	// And you can't modify your own, punk! (use the profile if you need to.)
	if (empty($_REQUEST['uid']) || (int) $_REQUEST['uid'] == $user_info['id'])
		fatal_lang_error('cant_change_own_karma', false);

	// The user ID _must_ be a number, no matter what.
	$_REQUEST['uid'] = (int) $_REQUEST['uid'];

	// Applauding or smiting?
	$dir = $_REQUEST['sa'] != 'applaud' ? -1 : 1;

	// Delete any older items from the log. (karmaWaitTime is by hour.)
	$smcFunc['db_query']('', '
		DELETE FROM {db_prefix}log_karma
		WHERE {int:current_time} - log_time > {int:wait_time}',
		array(
			'wait_time' => (int) ($modSettings['karmaWaitTime'] * 3600),
			'current_time' => time(),
		)
	);

	// Start off with no change in karma.
	$action = 0;

	// Not an administrator... or one who is restricted as well.
	if (!empty($modSettings['karmaTimeRestrictAdmins']) || !allowedTo('moderate_forum'))
	{
		// Find out if this user has done this recently...
		$request = $smcFunc['db_query']('', '
			SELECT action
			FROM {db_prefix}log_karma
			WHERE id_target = {int:id_target}
				AND id_executor = {int:current_member}
			LIMIT 1',
			array(
				'current_member' => $user_info['id'],
				'id_target' => $_REQUEST['uid'],
			)
		);
		if ($smcFunc['db_num_rows']($request) > 0)
			list ($action) = $smcFunc['db_fetch_row']($request);
		$smcFunc['db_free_result']($request);
	}

	// They haven't, not before now, anyhow.
	if (empty($action) || empty($modSettings['karmaWaitTime']))
	{
		// Put it in the log.
		$smcFunc['db_insert']('replace',
				'{db_prefix}log_karma',
				array('action' => 'int', 'id_target' => 'int', 'id_executor' => 'int', 'log_time' => 'int'),
				array($dir, $_REQUEST['uid'], $user_info['id'], time()),
				array('id_target', 'id_executor')
			);

		// Change by one.
		updateMemberData($_REQUEST['uid'], array($dir == 1 ? 'karma_good' : 'karma_bad' => '+'));
	}
	else
	{
		// If you are gonna try to repeat.... don't allow it.
		if ($action == $dir)
			fatal_lang_error('karma_wait_time', false, array($modSettings['karmaWaitTime'], $txt['hours']));

		// You decided to go back on your previous choice?
		$smcFunc['db_query']('', '
			UPDATE {db_prefix}log_karma
			SET action = {int:action}, log_time = {int:current_time}
			WHERE id_target = {int:id_target}
				AND id_executor = {int:current_member}',
			array(
				'current_member' => $user_info['id'],
				'action' => $dir,
				'current_time' => time(),
				'id_target' => $_REQUEST['uid'],
			)
		);

		// It was recently changed the OTHER way... so... reverse it!
		if ($dir == 1)
			updateMemberData($_REQUEST['uid'], array('karma_good' => '+', 'karma_bad' => '-'));
		else
			updateMemberData($_REQUEST['uid'], array('karma_bad' => '+', 'karma_good' => '-'));
	}

	// Figure out where to go back to.... the topic?
	if (!empty($topic))
		redirectexit('topic=' . $topic . '.' . $_REQUEST['start'] . '#msg' . (int) $_REQUEST['m']);
	// Hrm... maybe a personal message?
	elseif (isset($_REQUEST['f']))
		redirectexit('action=pm;f=' . $_REQUEST['f'] . ';start=' . $_REQUEST['start'] . (isset($_REQUEST['l']) ? ';l=' . (int) $_REQUEST['l'] : '') . (isset($_REQUEST['pm']) ? '#' . (int) $_REQUEST['pm'] : ''));
	// JavaScript as a last resort.
	else
	{
		echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"', $context['right_to_left'] ? ' dir="rtl"' : '', '>
	<head>
		<title>...</title>
		<script type="text/javascript"><!-- // --><![CDATA[
			history.go(-1);
		// ]]></script>
	</head>
	<body>&laquo;</body>
</html>';

		obExit(false);
	}
}
**********************************************************************************
******************** Disabled by Advanced Reputation System *********************/

// What's this?  I dunno, what are you talking about?  Never seen this before, nope.  No siree.
function BookOfUnknown()
{
	global $context;

	if (strpos($_GET['action'], 'mozilla') !== false && !$context['browser']['is_gecko'])
		redirectexit('http://www.getfirefox.com/');
	elseif (strpos($_GET['action'], 'mozilla') !== false)
		redirectexit('about:mozilla');

	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"', $context['right_to_left'] ? ' dir="rtl"' : '', '>
	<head>
		<title>The Book of Unknown, ', @$_GET['verse'] == '2:18' ? '2:18' : '4:16', '</title>
		<style type="text/css">
			em
			{
				font-size: 1.3em;
				line-height: 0;
			}
		</style>
	</head>
	<body style="background-color: #444455; color: white; font-style: italic; font-family: serif;">
		<div style="margin-top: 12%; font-size: 1.1em; line-height: 1.4; text-align: center;">';
	if (@$_GET['verse'] == '2:18')
		echo '
			Woe, it was that his name wasn\'t <em>known</em>, that he came in mystery, and was recognized by none.&nbsp;And it became to be in those days <em>something</em>.&nbsp; Something not yet <em id="unknown" name="[Unknown]">unknown</em> to mankind.&nbsp; And thus what was to be known the <em>secret project</em> began into its existence.&nbsp; Henceforth the opposition was only <em>weary</em> and <em>fearful</em>, for now their match was at arms against them.';
	else
		echo '
			And it came to pass that the <em>unbelievers</em> dwindled in number and saw rise of many <em>proselytizers</em>, and the opposition found fear in the face of the <em>x</em> and the <em>j</em> while those who stood with the <em>something</em> grew stronger and came together.&nbsp; Still, this was only the <em>beginning</em>, and what lay in the future was <em id="unknown" name="[Unknown]">unknown</em> to all, even those on the right side.';
	echo '
		</div>
		<div style="margin-top: 2ex; font-size: 2em; text-align: right;">';
	if (@$_GET['verse'] == '2:18')
		echo '
			from <span style="font-family: Georgia, serif;"><strong><a href="http://www.unknownbrackets.com/about:unknown" style="color: white; text-decoration: none; cursor: text;">The Book of Unknown</a></strong>, 2:18</span>';
	else
		echo '
			from <span style="font-family: Georgia, serif;"><strong><a href="http://www.unknownbrackets.com/about:unknown" style="color: white; text-decoration: none; cursor: text;">The Book of Unknown</a></strong>, 4:16</span>';
	echo '
		</div>
	</body>
</html>';

	obExit(false);
}

// Modify a user's karma, only better than the old one!
function ModifyKarma()
{
	global $modSettings, $txt, $user_info, $smcFunc;

	// A fix for templates that don't get edited correctly, and still show the old links.
	if(isset($_GET['sa']) && ($_GET['sa'] == 'applaud' || $_GET['sa'] == 'smite'))
		redirectexit('action=reputation;uid=' . $_GET['uid'] . ';m=' . $_GET['m'] . ';topic=' . $_GET['topic'] . ';' . $context['session_var'] . '=' . $context['session_id']);

	// If the mod is disabled, show an error.
	if (empty($modSettings['karmaMode']))
		fatal_lang_error('feature_disabled', true);

	// If you're a guest or can't do this, blow you off...
	is_not_guest();
	isAllowedTo('karma_edit');

	checkSession('request');

	// These _must_ be numbers, no matter what.
	$uid = (int) $_POST['uid'];
	$message_id = (int) $_POST['m'];
	$topic_id = (int) $_POST['topic'];

	// If you don't have enough posts, tough luck.
	// !!! Should this be dropped in favor of post group permissions?  Should this apply to the member you are smiting/applauding?
	if ($user_info['posts'] < $modSettings['karmaMinPosts'])
		fatal_lang_error('not_enough_posts_karma', true, array($modSettings['karmaMinPosts']));

	// And you can't modify your own, punk! (use the profile if you need to, admins!)
	if (empty($uid) || $uid == $user_info['id'])
		fatal_lang_error('cant_change_own_karma', false);

	// Find their (super)power (and their secret identity)! Can't go into negatives...
	$points = $user_info['karma_good'] - $user_info['karma_bad'];
	$power = ($points - ($points % $modSettings['karmaBarPower'])) / $modSettings['karmaBarPower'];
	$power = (int) (($power > 0) ? $power : 0);

	$comment = strip_tags($_POST['reputation_comment']);
	$comment = (strlen($comment) > 300) ? substr($comment, 0, 300) : $comment;

	// I fart in your general direction!
	if (strtolower($comment) == 'your mother was a hamster and your father smelt of elderberries!')
		fatal_error('...and Saint Attila raised the hand grenade up on high, saying, "O Lord, bless this Thy hand grenade that with it Thou mayest blow Thine enemies to tiny bits, in Thy mercy." And the Lord did grin and the people did feast upon the lambs and sloths and carp and anchovies and orangutans and breakfast cereals, and fruit bats and large chu... *ahem* And the Lord spake, saying, "First shalt thou take out the Holy Pin, then shalt thou count to three, no more, no less. Three shall be the number thou shalt count, and the number of the counting shall be three. Four shalt thou not count, neither count thou two, excepting that thou then proceed to three. Five is right out. Once the number three, being the third number, be reached, then lobbest thou thy Holy Hand Grenade of Antioch towards thy foe, who being naughty in my sight, shall snuff it." Amen.', false);

	//Find the time 24 hours ago... but wait... what if karmaWaitTime is greater than 24?! Bad var name, but who gives a hoot, if it works?
	$yesterday = time() - 86400;
	$longtimeago = time() - (60 * 60 * ($modSettings['karmaWaitTime']));
	$hoursAgo = ($modSettings['karmaWaitTime'] < 24) ? $yesterday : $longtimeago;

	// Applauding or smiting? Don't try to do a cheap javascript injection on me...
	switch ($_POST['type'])
	{
		case 'agree':
			if(!allowedTo('positive_karma'))
				fatal_lang_error('karma_cant_agree', false);
			$karma_which = 'karma_good';
			break;
		case 'disagree':
			if(!allowedTo('negative_karma'))
				fatal_lang_error('karma_cant_disagree', false);
			$karma_which = 'karma_bad';
			break;
		default:
			// Should never get here
			fatal_lang_error('karma_choose_action', false);
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
				AND log_time >= {int:yesterday}',
			array(
				'current_member' => $user_info['id'],
				'yesterday' => $hoursAgo,
			)
		);

		if ($smcFunc['db_num_rows']($request) >= $modSettings['karmaMaxPerDay']){ // but why would they be over?
			$smcFunc['db_free_result']($request);
			fatal_lang_error('karma_maxed_out', false, array($modSettings['karmaMaxPerDay']));
		}

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
			$smcFunc['db_free_result']($request);
			fatal_lang_error('karma_please_wait', false, array($modSettings['karmaWaitTime'], $log_time_wait));
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
					fatal_lang_error('karma_spread_around', false);

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
		fatal_lang_error('karma_sent_twice', false);

	$smcFunc['db_free_result']($request);

	// Finally! Now do an update

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
		fatal_lang_error('karma_didnt_update', false);

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

	/* It would be a shame if the recipient never knew he got the reputation, wouldn't it?
	if (!empty($modSettings['karmaSendPM']))
	{
		global $user_profile;

		// Grab the name for the {receiver} variable.
		$request = $smcFunc['db_query']('', '
			SELECT member_name
			FROM {db_prefix}members
			WHERE id_member = {int:uid}',
			array('uid' => $uid)
		);

		$var = array('{receiver}', '{sender}', '{comment}', '{posorneg}');

		while($row = $smcFunc['db_fetch_assoc']($request))
			$subst = array($row['member_name'], $user_info['name'], $comment, ($karma_which == 'karma_good' ? $txt['positive'] : $txt['negative']));

		$recipients = array(
			'to' => array($uid),
			'bcc' => array()
		);
		$subject = $modSettings['karmaPMSubject'];

		// Make it look like we actually care about the member by personalizing it a bit.
		$message = parse_bbc(str_replace($var, $subst, $modSettings['karmaPMContents']));

		loadMemberData($modSettings['karmaPMSenderID']);

		$from = array('id' => $modSettings['karmaPMSenderID'], 'name' => $user_profile[$modSettings['karmaPMSenderID']]['member_name'], 'username' =>$user_profile[$modSettings['karmaPMSenderID']]['real_name']);
		sendpm($recipients, $subject, $message, '0', $from);
	}*/

	// Figure out where to go back to.... the topic?
	if (isset($_POST['topic']))
		redirectexit('topic=' . $_POST['topic'] . '#msg' . $_POST['m']);

	// If not, just go back 1 item in your history.
	else
	{
		echo '
<html>
	<head>
		<title>...</title>
		<script language="JavaScript" type="text/javascript"><!-- // -->
			history.go(-1);
		</script>
	</head>
	<body>&laquo;</body>
</html>';

		obExit(false);
	}
}

// For those poor souls without javascript support :(
function SendKarma()
{
	global $modSettings, $user_info, $context, $smcFunc, $txt;

	// If the mod is disabled, show an error.
	if (empty($modSettings['karmaMode']))
		fatal_lang_error('feature_disabled', true);

	if(!isset($_GET['uid']) && !isset($_GET['m']) && !isset($_GET['topic']))
		return ReputationLog();

	// If you're a guest or can't do this, blow you off...
	is_not_guest();
	isAllowedTo('karma_edit');
	checkSession('request');

	// These _must_ be a number, no matter what.
	$uid = (int) $_GET['uid'];
	$message = (int) $_GET['m'];
	$topic = (int) $_GET['topic'];

	// You can't modify your own, punk! (use the profile if you need to.)
	if (empty($uid) || $uid == $user_info['id'])
		fatal_lang_error('cant_change_own_karma', false);

	// If you don't have enough posts, tough luck.
	// !!! Should this be dropped in favor of post group permissions?  Should this apply to the member you are smiting/applauding?
	if ($user_info['posts'] < $modSettings['karmaMinPosts'])
		fatal_lang_error('not_enough_posts_karma', true, array($modSettings['karmaMinPosts']));

	$message_request = $smcFunc['db_query']('', '
		SELECT *
		FROM {db_prefix}messages
		WHERE id_msg = {int:message}
		LIMIT 1',
		array(
			'message' => $message,
		)
	);

	while($row = $smcFunc['db_fetch_assoc']($message_request))
	{
		$context['message'] = $row;

		$board_request = $smcFunc['db_query']('', '
			SELECT *
			FROM {db_prefix}boards
			WHERE id_board = {int:board}',
			array(
				'board' => $context['message']['id_board']
			)
		);

		$board = $smcFunc['db_fetch_assoc']($board_request);

		// Load the membergroups allowed, and check permissions.
		$board_groups = $board['member_groups'] == '' ? array() : explode(',', $board['member_groups']);
		$smcFunc['db_free_result']($board_request);

		// Kick 'em out if they don't have permission to view this topic!
		if (count(array_intersect($user_info['groups'], $board_groups)) == 0 && !$user_info['is_admin'])
			fatal_lang_error('topic_gone', false);

		// Put it in quote tags to make it stand out a little... and because it's a quote.
		$context['message']['body'] = "[quote author={$context['message']['poster_name']} link=topic={$context['message']['id_topic']}.msg{$context['message']['id_msg']}#msg{$context['message']['id_msg']} date={$context['message']['poster_time']}]{$context['message']['body']}[/quote]";

		// Amish girls gone wild!
		censorText($context['message']['body']);
		censorText($context['message']['subject']);

		// Run BBC interpreter on the message.
		$context['message']['body'] = parse_bbc($context['message']['body'], $context['message']['smileys_enabled'], $context['message']['id_msg']);
	}

	$context['sub_template'] = 'main';
	$context['page_title'] = $txt['karma_add_to'];
	$context['topic'] = $topic;
	$context['m'] = $message;
	$context['uid'] = $uid;
	$context['can_pos_rep'] = allowedTo('positive_karma');
	$context['can_neg_rep'] = allowedTo('negative_karma');

	loadTemplate('Karma');
}

function ReputationLog()
{
	global $modSettings, $user_info, $context, $smcFunc, $txt, $scripturl;

	// If the mod is disabled, show an error.
	if (empty($modSettings['karmaMode']))
		fatal_lang_error('feature_disabled', true);

	// If you're a guest or can't do this, blow you off...
	is_not_guest();
	isAllowedTo('karma_edit');

	// Set up the stuff and load the user.
	$context['page_title'] = $txt['reputation_log'];
	$context['start'] = isset($_GET['start']) ? (int) $_GET['start'] : 0;

	$context['rep_sent'] = array();

	$request = $smcFunc['db_query']('', '
		SELECT COUNT(*) AS count
		FROM {db_prefix}log_karma',
		array()
	);

	list ($num_given) = $smcFunc['db_fetch_row']($request);
	$smcFunc['db_free_result']($request);

	$request = $smcFunc['db_query']('', '
		SELECT k.*, r.real_name AS receiver_name, s.real_name AS sender_name
		FROM {db_prefix}log_karma AS k
			LEFT JOIN {db_prefix}members AS r ON (r.id_member = k.id_target)
			LEFT JOIN {db_prefix}members AS s ON (s.id_member = k.id_executor)
		ORDER BY log_time DESC
		LIMIT {int:start}, 50',
		array(
			'start' => $context['start'],
		)
	);

	while ($row = $smcFunc['db_fetch_assoc']($request))
	{
		parsesmileys($row['comment']);

		$context['rep_sent'][] = array(
			'action_type' => !empty($row['action_type']) ? $row['action_type'] : ($row['action'] > 0) ? 'karma_good' : 'karma_bad',
			'executor' => array(
				'name' => $row['sender_name'],
				'id' => $row['id_executor'],
			),
			'target' => array(
				'name' => $row['receiver_name'],
				'id' => $row['id_target'],
			),
			'time' => timeformat($row['log_time']),
			'comment' => $row['comment'],
			'topic_href' => $row['topic'] . '.msg' . $row['message'] . '#msg' . $row['message'],
			'topic_title' => $row['title'],
		);
	}

	$smcFunc['db_free_result']($request);

	loadTemplate('Karma');
	$context['sub_template'] = 'log';

	// Construct the page index
	$context['page_index'] = constructPageIndex($scripturl . '?action=profile;area=reputation;u=' . $memID, $context['start'], $total_actions, $amount);
}
?>
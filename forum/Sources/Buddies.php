<?php
/**********************************************************************************
* Buddies.php                                                                      *
***********************************************************************************
* Version: 0.9.1
* This file is a part of Ultimate Profile mod
* Author: Jovan Turanjanin                                                      *
**********************************************************************************/

if (!defined('SMF'))
	die('Hacking attempt...');
	

function BuddiesMain()
{
	isAllowedTo('profile_extra_own');
	
	loadTemplate('Buddies');
	loadLanguage('UltimateProfile');
	
	switch (@$_GET['sa']) {
		case 'add': BuddyAdd(); break;
		case 'remove': BuddyRemove(); break;
		case 'approve': BuddyApprove(); break;
		case 'order': BuddyOrder(); break;
		default: BuddiesShow();
	}
}


function BuddiesShow()
{
	global $smcFunc, $context, $user_profile, $memberContext, $txt;
	
	// approved buddies
	$buddies = array();
	$request = $smcFunc['db_query']('', '
		SELECT buddy_id 
		FROM {db_prefix}buddies 
		WHERE id_member = {int:id_member}
			AND approved = 1 
		ORDER BY position ASC, time_updated DESC',
		array(
			'id_member' => $context['user']['id'],
		)
	);
	
	while ($row = $smcFunc['db_fetch_assoc']($request))
		$buddies[] = $row['buddy_id'];
	$smcFunc['db_free_result']($request);

	// Load all the members up.
	loadMemberData($buddies, false, 'profile');

	$context['buddies'] = array();
	foreach ($buddies as $buddy) {
		loadMemberContext($buddy);
		$context['buddies'][$buddy] = $memberContext[$buddy];
	}
	
	// unapproved buddies
	$buddies = array();
	$request = $smcFunc['db_query']('', '
		SELECT buddy_id 
		FROM {db_prefix}buddies 
		WHERE id_member = {int:id_member}
			AND approved = 0 
			AND requested <> {int:requested}
		ORDER BY position ASC, time_updated DESC',
		array(
			'id_member' => $context['user']['id'],
			'requested' => $context['user']['id'],
		)
	);
	
	while ($row = $smcFunc['db_fetch_assoc']($request))
		$buddies[] = $row['buddy_id'];
	$smcFunc['db_free_result']($request);
	
	if (count($buddies) > 0) {
		// Load all the members up.
		loadMemberData($buddies, false, 'profile');
	
		$context['unapproved'] = array();
		foreach ($buddies as $buddy) {
			loadMemberContext($buddy);
			$context['unapproved'][$buddy] = $memberContext[$buddy];
		}
	}
	
	// pending buddies
	$buddies = array();
	$request = $smcFunc['db_query']('', '
		SELECT id_member 
		FROM {db_prefix}buddies 
		WHERE buddy_id = {int:buddy_id}
			AND approved = 0 
			AND requested = {int:requested}
		ORDER BY position ASC, time_updated DESC',
		array(
			'buddy_id' => $context['user']['id'],
			'requested' => $context['user']['id'],
		)
	);
	
	while ($row = $smcFunc['db_fetch_assoc']($request))
		$buddies[] = $row['id_member'];
	$smcFunc['db_free_result']($request);
	
	if (count($buddies) > 0) {
		// Load all the members up.
		loadMemberData($buddies, false, 'profile');
	
		$context['pending'] = array();
		foreach ($buddies as $buddy) {
			loadMemberContext($buddy);
			$context['pending'][$buddy] = $memberContext[$buddy];
		}
	}
	
	$_GET['action'] = 'profile'; // £ust for the tab...
	$context['page_title'] = $txt['buddy_center'];
	$context['sub_template'] = 'buddy_center';
}

function BuddyOrder()
{
	global $smcFunc, $context;
	
	checkSession('get');
	
	$_GET['u'] = (int)$_GET['u'];
	
	$request = $smcFunc['db_query']('', '
		SELECT position 
		FROM {db_prefix}buddies 
		WHERE buddy_id = {int:buddy_id} 
			AND id_member = {int:id_member}', 
		array(
			'buddy_id' => $_GET['u'],
			'id_member' => $context['user']['id'],
		)
	);
	list($old_position) = $smcFunc['db_fetch_row']($request);
	
	if ($_GET['dir'] == 'up')
		$request = $smcFunc['db_query']('', '
			SELECT buddy_id, position 
			FROM {db_prefix}buddies 
			WHERE id_member = {int:id_member}
				AND position < {int:position}
			ORDER BY time_updated DESC LIMIT 1',
			array(
				'id_member' => $context['user']['id'],
				'position' => $old_position,
			)
		);
	else
		$request = $smcFunc['db_query']('', '
			SELECT buddy_id, position 
			FROM {db_prefix}buddies 
			WHERE id_member = {int:id_member}
				AND position > {int:position}
			ORDER BY time_updated DESC LIMIT 1',
			array(
				'id_member' => $context['user']['id'],
				'position' => $old_position,
			)
		);
	
	list($buddy_id, $new_position) = $smcFunc['db_fetch_row']($request);
	$buddy_id = (int)$buddy_id;
	$new_position = (int)$new_position;
	
	if ($new_position == 0)
		$new_position = ($_GET['dir'] == 'up') ? $old_position - 1 : $old_position + 1;
	
	$smcFunc['db_query']('', '
		UPDATE {db_prefix}buddies SET 
			position = {int:position}, 
			time_updated = {int:time}
		WHERE id_member = {int:id_member}
			AND buddy_id = {int:buddy_id}',
			array(
				'position' => $new_position,
				'time' => time(),
				'id_member' => $context['user']['id'],
				'buddy_id' => $_GET['u'],
			)
	);
	$smcFunc['db_query']('', '
		UPDATE {db_prefix}buddies SET 
			position = {int:position}, 
			time_updated = {int:time}
		WHERE id_member = {int:id_member}
			AND buddy_id = {int:buddy_id}',
			array(
				'position' => $old_position,
				'time' => time(),
				'id_member' => $context['user']['id'],
				'buddy_id' => $buddy_id,
			)
	);
	redirectexit('action=profile;area=lists;sa=buddies;u=' . $context['user']['id']);
}

function BuddyAdd()
{
	global $smcFunc, $sourcedir, $txt, $context, $scripturl, $language, $modSettings;
	
	checkSession('get');
	
	$_GET['u'] = (int)$_GET['u'];
	
	$request = $smcFunc['db_query']('', '
		SELECT approved 
		FROM {db_prefix}buddies 
		WHERE id_member = {int:id_member}
			AND buddy_id = {int:buddy_id}',
		array(
			'id_member' => $context['user']['id'],
			'buddy_id' => $_GET['u'],
		)
	);
	if ($smcFunc['db_num_rows']($request) > 0)
		fatal_error($txt['buddy_already_added'], false);
	
	$request = $smcFunc['db_query']('', '
		SELECT real_name 
		FROM {db_prefix}members 
		WHERE id_member = {int:id_member}',
		array(
			'id_member' => $_GET['u'],
		)
	);
	if ($smcFunc['db_num_rows']($request) < 1)
		redirectexit();
		
	// Find the new position.
	$request = $smcFunc['db_query']('', 'SELECT position 
		FROM {db_prefix}buddies 
		WHERE id_member = {int:id_member}
		ORDER BY position DESC
		LIMIT 1',
		array(
			'id_member' => $context['user']['id'],
		)
	);
	list($position) = $smcFunc['db_fetch_row']($request);
	$position = $position + 1;
	
	$smcFunc['db_insert']('normal', '{db_prefix}buddies',
			array(
				'id_member' => 'int',
				'buddy_id' => 'int',
				'approved' => 'int',
				'position' => 'int',
				'time_updated' => 'int',
				'requested' => 'int',
			),
			array(
				'id_member' => $context['user']['id'],
				'buddy_id' => $_GET['u'],
				'approved' => '0',
				'position' => $position,
				'time_updated' => time(),
				'requested' => $context['user']['id'],
			),
			array()
	);
	
	$request = $smcFunc['db_query']('', '
		SELECT position 
		FROM {db_prefix}buddies 
		WHERE id_member = {int:id_member}
		ORDER BY position DESC
		LIMIT 1',
		array(
			'id_member' => $_GET['u'],
		)
	);
	list($position) = $smcFunc['db_fetch_row']($request);
	$position = $position + 1;
	
	$smcFunc['db_insert']('normal', '{db_prefix}buddies',
			array(
				'buddy_id' => 'int',
				'id_member' => 'int',
				'approved' => 'int',
				'position' => 'int',
				'time_updated' => 'int',
				'requested' => 'int',
			),
			array(
				'buddy_id' => $context['user']['id'],
				'id_member' => $_GET['u'],
				'approved' => '0',
				'position' => $position,
				'time_updated' => time(),
				'requested' => $context['user']['id'],
			),
			array()
	);

	
	// Let's notify the user.
	$request = $smcFunc['db_query']('', '
		SELECT lngfile 
		FROM {db_prefix}members 
		WHERE id_member = {int:id_member}',
		array(
			'id_member' => $_GET['u'],
		)
	);
	list($user_language) = $smcFunc['db_fetch_row']($request);
	
	loadLanguage('UltimateProfile', empty($user_language) || empty($modSettings['userLanguage']) ? $language : $user_language, false);
	
	require_once $sourcedir . '/Subs-Post.php';
	sendpm(
		array('to' => array($_GET['u']), 'bcc' => array()),
		sprintf($txt['buddy_notif_new_subject'], $context['user']['name']),
		sprintf($txt['buddy_notif_new_body'], $context['user']['name'], $scripturl . '?action=profile;area=lists;sa=buddies;u=' . $_GET['u']),
		false,
		array('id' => 0, 'name' => $txt['profile_notif_com_user'], 'username' => $txt['profile_notif_com_user'])
	);
	
	redirectexit('action=profile;u=' . $_GET['u']);
}

function BuddyApprove()
{
	global $smcFunc, $user_info, $user_profile, $context;
	
	checkSession('get');
	
	$_GET['u'] = (int)$_GET['u'];
	
	$smcFunc['db_query']('', '
		UPDATE {db_prefix}buddies SET 
		approved = 1 
		WHERE id_member = {int:id_member}
			AND buddy_id = {int:buddy_id}',
		array(
			'id_member' => $context['user']['id'],
			'buddy_id' => $_GET['u'],
		)
	);
	$smcFunc['db_query']('', '
		UPDATE {db_prefix}buddies SET 
		approved = 1 
		WHERE buddy_id = {int:buddy_id}
			AND id_member = {int:id_member}',
		array(
			'buddy_id' => $context['user']['id'],
			'id_member' => $_GET['u'],
		)
	);
	
	// Update SMF's system as well...
	$user_info['buddies'][] = $_GET['u'];
	updateMemberData($context['user']['id'], array('buddy_list' => implode(',', $user_info['buddies'])));
	
	loadMemberData($_GET['u'], false, 'normal');
	$buddies = explode(',', $user_profile[$_GET['u']]['buddy_list']);
	$buddies[] = $context['user']['id'];
	updateMemberData($_GET['u'], array('buddy_list' => implode(',', $buddies)));
	
	redirectexit('action=profile;area=lists;sa=buddies;u=' . $context['user']['id']);
}

function BuddyRemove()
{
	global $smcFunc, $user_info, $user_profile, $context;
	
	checkSession('get');
	
	$_GET['u'] = (int)$_GET['u'];
	
	$smcFunc['db_query']('', '
		DELETE FROM {db_prefix}buddies 
		WHERE id_member = {int:id_member}
			AND buddy_id = {int:buddy_id}',
		array(
			'id_member' => $context['user']['id'],
			'buddy_id' => $_GET['u'],
		)
	);
	$smcFunc['db_query']('', '
		DELETE FROM {db_prefix}buddies 
		WHERE buddy_id = {int:buddy_id}
			AND id_member = {int:id_member}',
		array(
			'buddy_id' => $context['user']['id'],
			'id_member' => $_GET['u'],
		)
	);
	
	// Update SMF's system as well...
	$user_info['buddies'] = array_diff($user_info['buddies'], array($_GET['u']));
	updateMemberData($context['user']['id'], array('buddy_list' => implode(',', $user_info['buddies'])));
	
	loadMemberData($_GET['u'], false, 'normal');
	$buddies = explode(',', $user_profile[$_GET['u']]['buddy_list']);
	$buddies = array_diff($buddies, array($context['user']['id']));
	updateMemberData($_GET['u'], array('buddy_list' => implode(',', $buddies)));
	
	redirectexit('action=profile;area=lists;sa=buddies;u=' . $context['user']['id']);
}

?>
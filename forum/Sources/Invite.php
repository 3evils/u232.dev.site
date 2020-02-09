<?php
if (!defined('SMF'))
	die('Hacking attempt...');

function invite()
{
	global $context, $scripturl, $txt, $sourcedir, $user_info, $modSettings;
	
	if($user_info['is_guest'])
		redirectexit();
	
	// Let's set up the template and language.
	require_once($sourcedir . '/ManageServer.php');
	require_once($sourcedir . '/Subs-Menu.php');
	loadLanguage('Admin');
	loadLanguage('Modifications');
	loadLanguage('ManageSettings');
	loadTemplate('Invite');
	
	// Invite areas.
	$areas = array(
		'manage' => 'ManageInviteKeys',
		'generate' => 'GenerateInviteKey',
		'email' => 'EmailKey',
	);

	// Default the area to the manage keys page.
	$_REQUEST['area'] = isset($_REQUEST['area']) && isset($areas[$_REQUEST['area']]) ? $_REQUEST['area'] : 'manage';

	// Declare the page title, duh =P
	$context['page_title'] = $txt['invite_system'];

	$invite_areas = array(
		'manage' => array(
			'title' => $txt['invite_manage'],
			'areas' => array(
				'manage' => array(
					'label' => $txt['invite_manage'],
					'file' => 'Invite.php',
					'function' => 'ManageInviteKeys',
				),
			),
		),
		'generate' => array(
			'title' => $txt['invite_generate'],
			'areas' => array(
				'generate' => array(
					'label' => $txt['invite_generate'],
					'file' => 'Invite.php',
					'function' => 'GenerateInviteKey',
				),
			),
		),
	);
	
	if($modSettings['invite_email'] || allowedTo('admin_forum'))
		$invite_areas['email'] = array(
			'title' => $txt['invite_email_send'],
			'areas' => array(
				'email' => array(
					'label' => $txt['invite_email_member'],
					'file' => 'Invite.php',
					'function' => 'EmailKey',
				),
			),
		);
	
	$menuOptions = array(
		'disable_url_session_check' => true,
		'current_area' => $_REQUEST['area'],
	);
	
	createMenu($invite_areas, $menuOptions);
	
	// And finally, the Link Tree.
	$context['linktree'][] = array(
		'url' => $scripturl . '?action=invite;area=' . $_REQUEST['area'],
		'name' => $txt['invite_' . $_REQUEST['area']],
	);
	
	// Call the right function for this area.
	$areas[$_REQUEST['area']]();
}

function invite_admin()
{
	global $context, $scripturl, $txt, $sourcedir;
	
	// If you're not an admin, gtfo.
	isAllowedTo('admin_forum');
	
	// Let's set up the template and language.
	require_once($sourcedir . '/ManageServer.php');
	loadLanguage('Modifications');
	loadLanguage('ManageSettings');
	loadTemplate('Invite');
	
	$subActions = array(
		'modify' => 'ModifyInviteSettings',
		'modifygroup' => 'ModifyGroupSettings',
		'email' => 'EmailSettings',
		'manage' => 'ManageKeys',
		'generate' => 'GenerateKey',
	);

	// Default the sub-action to the modify settings page.
	$_REQUEST['sa'] = isset($_REQUEST['sa']) && isset($subActions[$_REQUEST['sa']]) ? $_REQUEST['sa'] : 'modify';

	// Declare the page title, duh =P
	$context['page_title'] = $txt['invite_system'];

	// Tabs for browsing the different invite functions.
	$context[$context['admin_menu_name']]['tab_data'] = array(
		'title' => $txt['invite_system'],
		'description' => $txt['invite_system_mod_settings_desc'],
		'tabs' => array(
			'modify' => array(
				'description' => $txt['invite_system_mod_settings_desc'],
				'href' => $scripturl . '?action=admin;area=invite;sa=modify',
				'is_selected' => $_REQUEST['sa'] == 'modify',
			),
			'modifygroup' => array(
				'description' => $txt['invite_system_mod_settings_group_desc'],
				'href' => $scripturl . '?action=admin;area=invite;sa=modifygroup',
				'is_selected' => $_REQUEST['sa'] == 'modifygroup',
			),
			'email' => array(
				'description' => $txt['invite_system_mod_email_desc'],
				'href' => $scripturl . '?action=admin;area=invite;sa=email',
				'is_selected' => $_REQUEST['sa'] == 'email',
			),
			'manage' => array(
				'description' => $txt['invite_system_mod_manage_desc'],
				'href' => $scripturl . '?action=admin;area=invite;sa=manage',
				'is_selected' => $_REQUEST['sa'] == 'manage',
			),
			'generate' => array(
				'description' => $txt['invite_system_mod_generate_desc'],
				'href' => $scripturl . '?action=admin;area=invite;sa=generate',
				'is_selected' => $_REQUEST['sa'] == 'generate',
			),
		),
	);

	// Call the right function for this sub-acton.
	$subActions[$_REQUEST['sa']]();
}

function ManageInviteKeys()
{
	global $context, $txt, $modSettings, $scripturl, $sourcedir, $smcFunc, $user_info;

	// Get rid of all of em!
	if (!empty($_POST['removeAll']))
	{
		checkSession();
		
		$smcFunc['db_query']('truncate_table', '
			DELETE FROM {db_prefix}invites
			WHERE member_id = {int:key_list}',
			array(
				'key_list' => (int)$user_info['id'],
			)
		);
		
		redirectexit($scripturl . '?action=invite;area=manage');
	}
			
	// User pressed the "remove selection button".
	if (!empty($_POST['remove']) && !empty($_POST['changeArr']) && is_array($_POST['changeArr']))
	{
		checkSession();

		// Make sure every entry is a proper integer.
		foreach ($_POST['changeArr'] as $index => $page_id)
			$_POST['changeArr'][(int) $index] = (int) $page_id;

		// Delete the key!
		$smcFunc['db_query']('', '
			DELETE FROM {db_prefix}invites
			WHERE invite_id IN ({array_int:key_list})',
			array(
				'key_list' => $_POST['changeArr'],
			)
		);

		redirectexit($scripturl . '?action=invite;area=manage');
	}

	// Now we create the list.
	$listOptions = array(
		'id' => 'invite_system_list_member',
		'items_per_page' => 20,
		'base_href' => $scripturl . '?action=invite;area=manage',
		'default_sort_col' => 'invite_id',
		'default_sort_dir' => 'desc',
		'get_items' => array(
			'function' => 'list_getKeysMember',
		),
		'get_count' => array(
			'function' => 'list_getNumKeysMember',
		),
		'no_items_label' => $txt['invite_system_no_keys'],
		'columns' => array(
			'invite_id' => array(
				'header' => array(
					'value' => $txt['invite_system_key_id'],
				),
				'data' => array(
					'db' => 'invite_id',
					'class' => 'windowbg',
					'style' => 'text-align: center;',
				),
				'sort' => array(
					'default' => 'inv.invite_id',
					'reverse' => 'inv.invite_id DESC',
				),
			),
			'key' => array(
				'header' => array(
					'value' => $txt['invite_system_key'],
				),
				'data' => array(
					'db' => 'key',
					'class' => 'windowbg',
					'style' => 'text-align: center;',
				),
				'sort' => array(
					'default' => 'inv.key',
					'reverse' => 'inv.key DESC',
				),
			),
			'active' => array(
				'header' => array(
					'value' => $txt['invite_system_status'],
				),
				'data' => array(
					'function' => create_function('$rowData', '
						global $txt;
						
						// Tell them the status of their page.
						if ($rowData[\'active\'])
							return sprintf(\'<span style="color: green;">%1$s</span>\', $txt[\'active\']);
						else
							return sprintf(\'<span style="color: red;">%1$s</span>\', $txt[\'nactive\']);
					'),
					'class' => 'windowbg',
					'style' => 'text-align: center;',
				),
				'sort' => array(
					'default' => 'inv.active',
					'reverse' => 'inv.active DESC',
				),
			),
			'check' => array(
				'header' => array(
					'value' => '<input type="checkbox" onclick="invertAll(this, this.form);" class="input_check" />',
				),
				'data' => array(
					'sprintf' => array(
						'format' => '<input type="checkbox" name="changeArr[]" value="%1$d" class="input_check" />',
						'params' => array(
							'invite_id' => false,
						),
					),
					'style' => 'text-align: center',
				),
			),
		),
		'form' => array(
			'href' => $scripturl . '?action=invite;area=manage',
		),
		'additional_rows' => array(
			array(
				'position' => 'below_table_data',
				'value' => '
					<input type="submit" name="remove" value="' . $txt['invite_system_remove'] . '" onclick="return confirm(\'' . $txt['invite_system_remove_confirm'] . '\');" class="button_submit" />
					<input type="submit" name="removeAll" value="' . $txt['invite_system_remove_all'] . '" onclick="return confirm(\'' . $txt['invite_system_remove_all_confirm'] . '\');" class="button_submit" />',
				'style' => 'text-align: right;',
			),
		),
	);
	
	require_once($sourcedir . '/Subs-List.php');
	createList($listOptions);

	$context['sub_template'] = 'show_list';
	$context['default_list'] = 'invite_system_list_member';
}

function list_getKeysMember($start, $items_per_page, $sort)
{
	global $smcFunc, $user_info;
	
	// Get all the settings from the DB.
	$request = $smcFunc['db_query']('', '
		SELECT inv.invite_id, inv.key, inv.member_id, inv.member_name, inv.active
		FROM {db_prefix}invites AS inv
		WHERE inv.member_id = {int:id}
		ORDER BY {raw:sort}
		LIMIT {int:offset}, {int:limit}',
		array(
			'sort' => $sort,
			'offset' => $start,
			'limit' => $items_per_page,
			'id' => (int)$user_info['id'],
		)
	);

	// Dump 'em into an array and return.
	$invites = array();
	while ($row = $smcFunc['db_fetch_assoc']($request))
		$invites[] = $row;

	$smcFunc['db_free_result']($request);

	return $invites;
}

function list_getNumKeysMember()
{
	global $smcFunc, $user_info;
	
	// Let's get the total number of keys, and return that.
	$request = $smcFunc['db_query']('', '
		SELECT COUNT(*) AS num_keys
		FROM {db_prefix}invites
		WHERE member_id = {int:id}',
		array(
			'id' => (int)$user_info['id'],
		)
	);

	list ($numKeys) = $smcFunc['db_fetch_row']($request);
	$smcFunc['db_free_result']($request);

	return $numKeys;
}

function GenerateInviteKey()
{
	global $context, $user_info, $smcFunc;
	
	$request = $smcFunc['db_query']('', '
		SELECT invite_count, invite_roll_max, invite_max
		FROM {db_prefix}members
		WHERE id_member = {int:id}
		LIMIT 1',
		array(
			'id' => (int)$user_info['id'],
		)
	);
	
	$member = $smcFunc['db_fetch_assoc']($request);
	
	if(allowedTo('admin_forum') || $member['invite_max'] < 0)
	{
		// This person is an admin, or has infinite invites, no need to ask any questions.
		$context['invitation_key'] = gen_key($user_info['id'], $user_info['name']);
		$context['sub_template'] = 'invite_generate';
	}
	else
	{
		// Sorry buddy, but the same can't be said about you ;)
		$smcFunc['db_free_result']($request);
		
		if($member['invite_count'] < $member['invite_roll_max'])
		{
			// Good, you have some invites left =]
			$context['invitation_key'] = gen_key($user_info['id'], $user_info['name']);
			$smcFunc['db_query']('', '
				UPDATE {db_prefix}members
				SET invite_count = {int:count}
				WHERE id_member = {int:id}',
				array(
					'count' => ((int)$member['invite_count']) + 1,
					'id' => (int)$user_info['id'],
				)
			);
			$context['sub_template'] = 'invite_generate';
		}
		else
			// Sorry, maybe next time ;)
			$context['sub_template'] = 'no_keys';
	}
}

function EmailKey()
{
	global $modSettings, $context, $user_info, $modSettings, $smcFunc, $scripturl, $webmaster_email;
	
	if(!$modSettings['invite_email'] && !allowedTo('admin_forum'))
		redirectexit();

	if(isset($_POST['submit']))
	{
		$email = isset($_POST['r_email']) ? $_POST['r_email'] : '';
		$name = isset($_POST['r_name']) ? $_POST['r_name'] : '';
		$message = isset($_POST['r_message']) ? $_POST['r_message'] : '';
		
		if(!preg_match('/^[^\W][a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\@[a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\.[a-zA-Z]{2,4}$/', $email))
			$context['sub_template'] = 'email_invalid';
		else if(empty($email) || empty($name) || empty($message))
			$context['sub_template'] = 'email_empty';
		else
		{
			$request = $smcFunc['db_query']('', '
				SELECT invite_count, invite_roll_max, invite_max
				FROM {db_prefix}members
				WHERE id_member = {int:id}
				LIMIT 1',
				array(
					'id' => (int)$user_info['id'],
				)
			);
	
			$member = $smcFunc['db_fetch_assoc']($request);
			
			$key = '';
			if(allowedTo('admin_forum') || $member['invite_max'] < 0)
			{
				// This person is an admin, or has infinite invites, no need to ask any questions.
				$key = gen_key($user_info['id'], $user_info['name']);
			}
			else
			{
				// Sorry buddy, but the same can't be said about you ;)
				$smcFunc['db_free_result']($request);
		
				if($member['invite_count'] < $member['invite_roll_max'])
				{
					// Good, you have some invites left =]
					$key = gen_key($user_info['id'], $user_info['name']);
					$smcFunc['db_query']('', '
						UPDATE {db_prefix}members
						SET invite_count = {int:count}
						WHERE id_member = {int:id}',
						array(
							'count' => ((int)$member['invite_count']) + 1,
							'id' => (int)$user_info['id'],
						)
					);
				}
			}
			if(!empty($key))
			{
				$subject = $modSettings['invite_email_subject'];
				$subject = str_replace('{invitee}', $name, $subject);
				$subject = str_replace('{inviter}', $user_info['name'], $subject);
				$subject = str_replace('{forum}', $context['forum_name'], $subject);
				
				$message_final = $modSettings['invite_email_message'];
				$message_final = str_replace('{invitee}', $name, $message_final);
				$message_final = str_replace('{inviter}', $user_info['name'], $message_final);
				$message_final = str_replace('{forum}', $context['forum_name'], $message_final);
				$message_final = str_replace('{message}', $message, $message_final);
				$message_final = str_replace('{key}', $key, $message_final);
				$message_final = str_replace('{link}', $scripturl . '?action=register', $message_final);
				
				if(mail($email, $subject, $message_final, $webmaster_email))
					$context['sub_template'] = 'email_sent';
				else
					$context['sub_template'] = 'email_not_sent';
			}
			else
				$context['sub_template'] = 'no_keys';
		}
	}
	else
		$context['sub_template'] = 'email';
}

function EmailSettings($return_config = false)
{
	global $context, $txt, $scripturl, $modSettings;
	
	$context['sub_template'] = 'show_settings';
	
	$config_vars = array(
		array('text', 'invite_email_subject'),
		array('large_text', 'invite_email_message'),
	);
	
	if ($return_config)
		return $config_vars;
		
	if (isset($_GET['save']))
	{
		// Looks like you pressed that save button. Great, let's throw that in then.
		checkSession();
		saveDBSettings($config_vars);		
		redirectexit('action=admin;area=invite;sa=modify');
	}
	
	$context['post_url'] = $scripturl . '?action=admin;area=invite;sa=email;save';
	$context['settings_title'] = $txt['invite_settings_title'];
	prepareDBSettingContext($config_vars);
}

function ModifyInviteSettings($return_config = false)
{
	global $context, $txt, $scripturl, $smcFunc, $modSettings;
	
	$context['sub_template'] = 'show_settings';
	
	$config_vars = array(
		array('check', 'invite_enabled'),
		array('check', 'roll_over'),
		array('check', 'invite_email'),
		array('int', 'key_renew'),
		array('int', 'key_expire'),
	);
	if ($return_config)
		return $config_vars;
	
	if (isset($_GET['save']))
	{
		// Looks like you pressed that save button. Great, let's throw that in then.
		checkSession();
		$smcFunc['db_query']('', '
			UPDATE {db_prefix}scheduled_tasks
			SET time_regularity = {int:time}, disabled = {int:active}
			WHERE task = {string:task}',
			array(
				'time' => (int)$_POST[$config_vars[4][1]],
				'active' => (int) $_POST[$config_vars[4][1]] == 0 ? 1 : 0,
				'task' => 'keyExpire',
			)
		);
		$smcFunc['db_query']('', '
			UPDATE {db_prefix}scheduled_tasks
			SET time_regularity = {int:time}, disabled = {int:active}
			WHERE task = {string:task}',
			array(
				'time' => (int)$_POST[$config_vars[3][1]],
				'active' => $_POST[$config_vars[3][1]] == 0 ? 1 : 0,
				'task' => 'keyRenew',
			)
		);
		saveDBSettings($config_vars);		
		redirectexit('action=admin;area=invite;sa=modify');
	}
	
	$context['post_url'] = $scripturl . '?action=admin;area=invite;sa=modify;save';
	$context['settings_title'] = $txt['invite_settings_title'];
	prepareDBSettingContext($config_vars);
}

function ModifyGroupSettings()
{
	global $context, $smcFunc, $modSettings;
	
	// Get all of the groups from the database.
	$request = $smcFunc['db_query']('', '
		SELECT group_name, id_group, max_invites
		FROM {db_prefix}membergroups
		WHERE id_group > {int:moderator_group} OR id_group = {int:global_moderator}
		ORDER BY min_posts, id_group != {int:global_moderator}, group_name',
		array(
			'moderator_group' => 3,
			'global_moderator' => 2,
		)
	);
	
	//Store the groups into an array.
	$context['invite_groups'] = array();
	
	while($row = $smcFunc['db_fetch_assoc']($request))
		$context['invite_groups'][] = $row;	

	if(isset($_POST['save']))
	{
		checkSession();
		
		// Store the members into an array for a bit.
		$request = $smcFunc['db_query']('', '
			SELECT invite_count, invite_roll_max, invite_max, id_post_group, id_group, id_member
			FROM {db_prefix}members',
			array()
		);
		
		$members = array();
		
		while($row = $smcFunc['db_fetch_assoc']($request))
			$members[] = $row;
			
		foreach($context['invite_groups'] as $group)
		{
			// Lets go through all the groups and save the new group invite values.
			$smcFunc['db_query']('', '
				UPDATE {db_prefix}membergroups
				SET max_invites = {int:max}
				WHERE id_group = {int:id}',
				array(
					'max' => (int)$_POST['group_' . $group['id_group']],
					'id' => (int)$group['id_group'],
				)
			);
			
			// Do the same for member invite values.
			foreach($members as $member)
			{
				if($member['id_group'] == 0)
				{
					// For regular members. Also, if we're not doing your group id, then skip it.
					if($member['id_post_group'] != $group['id_group'])
						continue;
					
					$invites = ($member['invite_roll_max'] - $member['invite_max']) + (isset($_POST['group_' . $group['id_group']]) ? $_POST['group_' . $group['id_group']] : 0);
					$smcFunc['db_query']('', '
						UPDATE {db_prefix}members
						SET invite_max = {int:max}
						WHERE id_member = {int:id}',
						array(
							'max' => (int)$_POST['group_' . $group['id_group']],
							'id' => (int)$member['id_member'],
						)
					);
					$smcFunc['db_query']('', '
						UPDATE {db_prefix}members
						SET invite_roll_max = {int:max}
						WHERE id_member = {int:id}',
						array(
							'max' => (int)$invites,
							'id' => (int)$member['id_member'],
						)
					);
				}
				else if($member['id_group'] != 0)
				{
					// For "special" members. Also, if we're not doing your group id, then skip it.
					if($member['id_group'] != $group['id_group'])
						continue;
					
					$invites = ($member['invite_roll_max'] - $member['invite_max']) + (isset($_POST['group_' . $group['id_group']]) ? $_POST['group_' . $group['id_group']] : 0);
					$smcFunc['db_query']('', '
						UPDATE {db_prefix}members
						SET invite_max = {int:max}
						WHERE id_member = {int:id}',
						array(
							'max' => (int)$_POST['group_' . $group['id_group']],
							'id' => (int)$member['id_member'],
						)
					);
					$smcFunc['db_query']('', '
						UPDATE {db_prefix}members
						SET invite_roll_max = {int:max}
						WHERE id_member = {int:id}',
						array(
							'max' => (int)$invites,
							'id' => (int)$member['id_member'],
						)
					);
				}
			}
		}
			
		redirectexit('action=admin;area=invite;sa=modifygroup');	
	}
		
	$smcFunc['db_free_result']($request);
	
	$context['sub_template'] = 'group_settings';
}

function ManageKeys()
{
	global $context, $txt, $modSettings, $scripturl, $sourcedir, $smcFunc;

	// Get rid of all of em!
	if (!empty($_POST['removeAll']))
	{
		checkSession();
		
		$smcFunc['db_query']('truncate_table', '
			TRUNCATE {db_prefix}invites',
			array()
		);
		
		redirectexit($scripturl . '?action=admin;area=invite;sa=manage');
	}
			
	// User pressed the "remove selection button".
	if (!empty($_POST['remove']) && !empty($_POST['changeArr']) && is_array($_POST['changeArr']))
	{
		checkSession();

		// Make sure every entry is a proper integer.
		foreach ($_POST['changeArr'] as $index => $page_id)
			$_POST['changeArr'][(int) $index] = (int) $page_id;

		// Delete the key!
		$smcFunc['db_query']('', '
			DELETE FROM {db_prefix}invites
			WHERE invite_id IN ({array_int:key_list})',
			array(
				'key_list' => $_POST['changeArr'],
			)
		);

		redirectexit($scripturl . '?action=admin;area=invite;sa=manage');
	}
	
	// User pressed the "Activate/Decativate" button.
	if (!empty($_POST['change']) && !empty($_POST['changeArr']) && is_array($_POST['changeArr']))
	{
		checkSession();

		// Make sure every entry is a proper integer.
		foreach ($_POST['changeArr'] as $index => $page_id)
			$_POST['changeArr'][(int) $index] = (int)$page_id;

		$smcFunc['db_query']('', '
			UPDATE {db_prefix}invites
			SET active = 1-active
			WHERE invite_id IN ({array_int:key_list})',
			array(
				'key_list' => $_POST['changeArr'],
			)
		);	

		redirectexit($scripturl . '?action=admin;area=invite;sa=manage');
	}
	
	// Now we create the list.
	$listOptions = array(
		'id' => 'invite_system_list',
		'items_per_page' => 20,
		'base_href' => $scripturl . '?action=admin;area=invite;sa=manage',
		'default_sort_col' => 'invite_id',
		'default_sort_dir' => 'desc',
		'get_items' => array(
			'function' => 'list_getKeys',
		),
		'get_count' => array(
			'function' => 'list_getNumKeys',
		),
		'no_items_label' => $txt['invite_system_no_keys'],
		'columns' => array(
			'invite_id' => array(
				'header' => array(
					'value' => $txt['invite_system_key_id'],
				),
				'data' => array(
					'db' => 'invite_id',
					'class' => 'windowbg',
					'style' => 'text-align: center;',
				),
				'sort' => array(
					'default' => 'inv.invite_id',
					'reverse' => 'inv.invite_id DESC',
				),
			),
			'member_name' => array(
				'header' => array(
					'value' => $txt['invite_system_member_name'],
				),
				'data' => array(
					'sprintf' => array(
						'format' => '<a href="' . $scripturl . '?action=profile;u=%2$s" target="_blank">%1$s</a>',
						'params' => array(
							'member_name' => false,
							'member_id' => false,
						),
					),
					'class' => 'windowbg',
					'style' => 'text-align: center;',
				),
				'sort' => array(
					'default' => 'inv.member_name',
					'reverse' => 'inv.member_name DESC',
				),
			),
			'key' => array(
				'header' => array(
					'value' => $txt['invite_system_key'],
				),
				'data' => array(
					'db' => 'key',
					'class' => 'windowbg',
					'style' => 'text-align: center;',
				),
				'sort' => array(
					'default' => 'inv.key',
					'reverse' => 'inv.key DESC',
				),
			),
			'active' => array(
				'header' => array(
					'value' => $txt['invite_system_status'],
				),
				'data' => array(
					'function' => create_function('$rowData', '
						global $txt;
						
						// Tell them the status of their page.
						if ($rowData[\'active\'])
							return sprintf(\'<span style="color: green;">%1$s</span>\', $txt[\'active\']);
						else
							return sprintf(\'<span style="color: red;">%1$s</span>\', $txt[\'nactive\']);
					'),
					'class' => 'windowbg',
					'style' => 'text-align: center;',
				),
				'sort' => array(
					'default' => 'inv.active',
					'reverse' => 'inv.active DESC',
				),
			),
			'check' => array(
				'header' => array(
					'value' => '<input type="checkbox" onclick="invertAll(this, this.form);" class="input_check" />',
				),
				'data' => array(
					'sprintf' => array(
						'format' => '<input type="checkbox" name="changeArr[]" value="%1$d" class="input_check" />',
						'params' => array(
							'invite_id' => false,
						),
					),
					'style' => 'text-align: center',
				),
			),
		),
		'form' => array(
			'href' => $scripturl . '?action=admin;area=invite;sa=manage',
		),
		'additional_rows' => array(
			array(
				'position' => 'below_table_data',
				'value' => '
					<input type="submit" name="remove" value="' . $txt['invite_system_remove'] . '" onclick="return confirm(\'' . $txt['invite_system_remove_confirm'] . '\');" class="button_submit" />
					<input type="submit" name="removeAll" value="' . $txt['invite_system_remove_all'] . '" onclick="return confirm(\'' . $txt['invite_system_remove_all_confirm'] . '\');" class="button_submit" />
					<input type="submit" name="change" value="' . $txt['invite_system_change'] . '" onclick="return confirm(\'' . $txt['invite_system_change_confirm'] . '\');" class="button_submit" />',
				'style' => 'text-align: right;',
			),
		),
	);
	
	require_once($sourcedir . '/Subs-List.php');
	createList($listOptions);

	$context['sub_template'] = 'show_list';
	$context['default_list'] = 'invite_system_list';
}

function list_getKeys($start, $items_per_page, $sort)
{
	global $smcFunc;
	
	// Get all the settings from the DB.
	$request = $smcFunc['db_query']('', '
		SELECT inv.invite_id, inv.key, inv.member_id, inv.member_name, inv.active
		FROM {db_prefix}invites AS inv
		ORDER BY {raw:sort}
		LIMIT {int:offset}, {int:limit}',
		array(
			'sort' => $sort,
			'offset' => $start,
			'limit' => $items_per_page,
		)
	);

	// Dump 'em into an array and return.
	$invites = array();
	while ($row = $smcFunc['db_fetch_assoc']($request))
		$invites[] = $row;

	$smcFunc['db_free_result']($request);

	return $invites;
}

function list_getNumKeys()
{
	global $smcFunc;
	
	// Let's get the total number of keys, and return that.
	$request = $smcFunc['db_query']('', '
		SELECT COUNT(*) AS num_keys
		FROM {db_prefix}invites',
		array(
		)
	);

	list ($numKeys) = $smcFunc['db_fetch_row']($request);
	$smcFunc['db_free_result']($request);

	return $numKeys;
}

function GenerateKey()
{
	global $context, $user_info;
	
	// Not much to do here, just generate a new key, and throw it at the template.
	$context['invitation_key'] = gen_key($user_info['id'], $user_info['name']);
	$context['sub_template'] = 'generate';
}

function gen_key($id, $name)
{
	global $smcFunc;
	
	while(true)
	{
		$key = $name . $id;
		$key_final = '';
		// let's generate random numbers into a string, long length to make sure we get decent results.
		for($i = 0; $i < 100; $i++)
			$key .= (string)rand(0, 9);
		// Now let's encrypt the key, mainly so that it becomes more unique than a bunch of random numbers.
		$key_final = sha1($key);
		$request = $smcFunc['db_query']('', '
			SELECT inv.key
			FROM {db_prefix}invites as inv
			WHERE inv.key = {string:key}
			LIMIT 1',
			array(
				'key' => $key_final,
			)
		);
		// Make sure that key doesn't exist
		if(!$smcFunc['db_num_rows']($request))
		{
			// Looks like it doesn't exist, dump it into the DB!
			$smcFunc['db_free_result']($request);
			$smcFunc['db_insert']('insert',
            	'{db_prefix}invites',
            	array(
                	'key' => 'string-255', 'active' => 'int', 'member_id' => 'int', 'member_name' => 'string-255', 'time' => 'int',
            	),
            	array(
             		$key_final, 1, $id, $name, time(),
           		),
            	array('invite_id')
        	);
        	return $key_final;
		}
	}
}
?>
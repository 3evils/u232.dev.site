<?php
/**********************************************************************************
* Shoutbox.php                                                                    *
***********************************************************************************
*                                                                                 *
* SMFPacks Shoutbox v1.0                                                          *
* Copyright (c) 2009-2010 by Makito and NIBOGO. All rights reserved.              *
* Powered by www.smfpacks.com                                                     *
* Created by Makito                                                               *
* Developed by NIBOGO for SMFPacks.com                                            *
*                                                                                 *
**********************************************************************************/

if (!defined('SMF'))
    die('Hacking attempt...');

function Shoutbox()
{
	$subs = array(
		// user
		'popup' => 'Shoutbox_Popup',
		'send' => 'Shoutbox_SendMsg',
		'get' => 'Shoutbox_GetMsgs',

		// panel
		'moderate' => 'Shoutbox_Panel',
		'moderate_getmsgs' => 'Shoutbox_Panel_GetMsgs',
		'moderate_editmsg' => 'Shoutbox_Panel_EditMsg',
		'moderate_getusers' => 'Shoutbox_Panel_ListUsers',
		'moderate_banusers' => 'Shoutbox_Panel_BanUsers',
	);

	$_REQUEST['sa'] = isset($_REQUEST['sa']) && isset($subs[$_REQUEST['sa']]) ? $_REQUEST['sa'] : 'popup';
	$subs[$_REQUEST['sa']]();
}

function Shoutbox_Popup()
{
	global $context, $shoutbox, $smcFunc;

	$context['template_layers'] = array();
	$context['sub_template'] = 'shoutbox_popup';

	$context['shoutbox_popup'] = true;
	$context['shoutbox_smf_2'] = true;

	// load config, template and language :P
	if (loadLanguage('Shoutbox') == false)
		loadLanguage('Shoutbox', 'english');

	loadTemplate('Shoutbox');
	Shoutbox_Settings();

	$context['page_title'] = $shoutbox['boxTitle'];

	// die
	if (!empty($shoutbox['disable']))
		return;

	$context['shoutbox'] = array();
	$context['shoutbox']['can_moderate'] = !$context['user']['is_guest'] && allowedTo(array('shoutbox_edit', 'shoutbox_delete', 'shoutbox_prune', 'shoutbox_ban'));
	$context['shoutbox']['banned'] = Shoutbox_isBanned();
	$context['shoutbox']['can_view'] = !$context['shoutbox']['banned'] && allowedTo('shoutbox_view');

	if (!$context['shoutbox']['can_view'] && !$context['shoutbox']['banned'])
	{
		unset($context['shoutbox']);
		return;
	}
	$context['shoutbox']['can_post'] = !$context['user']['is_guest'] && $context['shoutbox']['can_view'] && allowedTo('shoutbox_post');

	// post features... if can't post, scape
	if (!$context['shoutbox']['can_post'])
		return;

	$context['shoutbox']['disabled'] = array();
	if (isset($shoutbox['disableTags']))
		foreach (explode(',', $shoutbox['disableTags']) as $s)
			$context['shoutbox']['disabled'][$s] = 1;

	if (!isset($context['shoutbox']['disabled']['smileys']))
	{
		$context['shoutbox']['smileys'] = Shoutbox_LoadSmileys();

		// no smileys, disable it
		if (empty($context['shoutbox']['smileys']['postform']))
			$context['shoutbox']['disabled']['smileys'] = 1;
	}
}

// This functions must be the simplest and fastest posible
function Shoutbox_GetMsgs($error = false)
{
	global $context, $txt, $scripturl, $shoutbox, $smcFunc;

	// template
	$context['sub_template'] = 'Shoutbox_GetMsgs';

	if (!isset($_GET['xml']) || !isset($_GET['row']) || !allowedTo('shoutbox_view'))
		die();

	if (loadLanguage('Shoutbox') == false)
		loadLanguage('Shoutbox', 'english');

	loadTemplate('Shoutbox');
	Shoutbox_Settings();

	$context['shoutbox_echo'] = array();

	// if user is banned, close Shoutbox
	if (Shoutbox_isBanned())
	{
		$context['shoutbox_echo']['banned'] = true;
		return;
	}

	// can't be empty :|
	$_SESSION['shoutbox_lastid'] = empty($_SESSION['shoutbox_lastid']) ? -1 : (int) $_SESSION['shoutbox_lastid'];

	// limits !! load a few msgs if refresh time is out or if we want it
	if (isset($_GET['restart']) ||  empty($_SESSION['shoutbox_lastget']) || $_SESSION['shoutbox_lastget'] < time() - $shoutbox['refreshShouts'] + 15 || $_SESSION['shoutbox_lastget'] <= $shoutbox['lastPrune'])
	{
		// restart ALL
		$_SESSION['shoutbox_lastid'] = -1;
		$_SESSION['shoutbox_lastget'] = null;

		// new msgs first?
		if (empty($shoutbox['showmsg_down']))
			$limit = 'ORDER BY ID_SHOUT DESC
				LIMIT ' . $shoutbox['startShouts'];
		else
		{
			$query_count = $smcFunc['db_query']('', "SELECT COUNT(*) FROM {db_prefix}shoutbox");
			list ($limit_start) = $smcFunc['db_fetch_row']($query_count);
			$smcFunc['db_free_result']($query_count);

			$limit_start = $limit_start - $shoutbox['startShouts'] < 0 ? 0 : $limit_start - $shoutbox['startShouts'];

			$limit = 'ORDER BY ID_SHOUT ASC
				LIMIT ' . $limit_start . ', ' . $shoutbox['startShouts'];
		}

		$context['shoutbox_echo']['reset'] = true;
	}
	else
		$limit = 'WHERE ID_SHOUT > ' . $_SESSION['shoutbox_lastid'] . '
				ORDER BY ID_SHOUT ' . (empty($shoutbox['showmsg_down']) ? 'DESC' : 'ASC');

	$query = $smcFunc['db_query']('', "
				SELECT ID_MEMBER, realName, colorName, style, message, timestamp
				FROM {db_prefix}shoutbox
				$limit");

	$_SESSION['shoutbox_lastget'] = time();

	// so, no new msgs?
	if (empty($error) && $smcFunc['db_num_rows']($query) == 0)
		die;
	elseif (!empty($error))
	{
		$context['shoutbox_echo']['error'] = $error;
		return;
	}

	// last id
	$query_last = $smcFunc['db_query']('', "SELECT MAX(ID_SHOUT) FROM {db_prefix}shoutbox");
	list ($_SESSION['shoutbox_lastid']) = $smcFunc['db_fetch_row']($query_last);
	$smcFunc['db_free_result']($query_last);

	$disabled = array();
	if (isset($shoutbox['disableTags']))
		foreach (explode(',', $shoutbox['disableTags']) as $tag)
			$disabled[$tag] = 1;

	// get msgs
	$context['shoutbox_echo']['msgs'] = array();
	while ($s = $smcFunc['db_fetch_assoc']($query))
	{
		if ($context['user']['id'] != $s['ID_MEMBER'])
			$context['shoutbox_echo']['new_msgs'] = true;

		$cmd_me = substr($s['message'], 0, 17) == '<span class="me">';
		$context['shoutbox_echo']['msgs'][] = array(
			'poster' => ($cmd_me ? '' : '<a href="' . $scripturl . '?action=profile;u=' . $s['ID_MEMBER'] . '" target="_blank"' . (!empty($s['colorName']) ? ' style="color:' . $s['colorName'] . '"' : '') . '>' . $s['realName'] . '</a>') . ' <span style="color:' . $shoutbox['timeColor'] . '">[' . ($s['timestamp'] > 0 ? timeformat($s['timestamp'], $shoutbox['timeFormat']) : $txt[470]) . ']</span>:',
			'message' => '<span style="' . $s['style'] . '">' . ($cmd_me ? $s['realName'] . ' ' : '') . (isset($disabled['smileys']) ? censorText($s['message']) : Shoutbox_ParseSmileys(censorText($s['message']))) . '</div>',
		);
	}
	$smcFunc['db_free_result']($query);

	// count rows :)
	$_GET['row'] = (int) $_GET['row'];
}

function Shoutbox_SendMsg()
{
	global $smcFunc, $txt, $user_info;
	global $context, $shoutbox;

	if ($context['user']['is_guest'] || !isset($_GET['xml']) || !allowedTo('shoutbox_view') || !allowedTo('shoutbox_post'))
		die();

	if (loadLanguage('Shoutbox') == false)
		loadLanguage('Shoutbox', 'english');

	loadTemplate('Shoutbox');
	Shoutbox_Settings();

	// if user is banned, close Shoutbox
	if (Shoutbox_isBanned())
	{
		// template
		$context['sub_template'] = 'Shoutbox_GetMsgs';

		$context['shoutbox_echo'] = array();
		$context['shoutbox_echo']['banned'] == true;
		return;
	}

	if (checkSession('get', '', false) != '' || !isset($_POST['msg']))
		die();

	$msg = (string) $_POST['msg'];
	unset($_POST['msg']);

	$msg = stripslashes($msg);
	$msg = $smcFunc['htmltrim']($msg);
	$msg = $smcFunc['htmlspecialchars']($msg, ENT_QUOTES);
	$msg = strip_tags($msg);
	$msg = addslashes($msg);

	// command /clear o /prune
	if (($msg == '/clear' || $msg == '/prune') && allowedTo('shoutbox_prune'))
	{
		$smcFunc['db_query']('', "TRUNCATE TABLE {db_prefix}shoutbox");

		// last prune
		$smcFunc['db_query']('', "
			UPDATE {db_prefix}shoutbox_settings
			SET value = '" . time() . "'
			WHERE variable = 'lastPrune'
			LIMIT 1");

		// template
		$context['sub_template'] = 'Shoutbox_GetMsgs';

		$context['shoutbox_echo'] = array();
		$context['shoutbox_echo']['error'] = $txt['sbm_7'];
		$context['shoutbox_echo']['prune'] = 1;
		return;
	}

	// command /me
	$cmd_me = false;
	if (substr($msg, 0, 4) == '/me ')
	{
		$cmd_me = true;
		$msg = substr_replace($msg, '', 0, 4);
	}

	// from here any error will be printed
 	if ($smcFunc['strlen']($msg) < $shoutbox['minMsgLenght'])
		return Shoutbox_GetMsgs($txt['sb_12'] . $shoutbox['minMsgLenght']);
	if ($smcFunc['strlen']($msg) > $shoutbox['maxMsgLenght'])
		return Shoutbox_GetMsgs($txt['sb_12b'] . $shoutbox['maxMsgLenght']);

	// find links to convert
	if (strstr($msg, 'http://'))
	{
		$links = array();
		foreach (explode(' ', $msg) as $w)
			if (substr($w, 0, 7) == 'http://' && $smcFunc['strlen']($w) > 7)
				$links[] = '<a href="' . $w . '" target="_blank">' . ($smcFunc['strlen']($w) > $shoutbox['maxLinkLenght'] ? substr($w, 0, $shoutbox['maxLinkLenght'] - 8) . '...' . substr($w, -8) : $w) . '</a>';
			else
				$links[] = $w;
		$msg = implode(' ', $links);
	}

	// verifying word isn't too long... same SMF function ;)
	if ($shoutbox['fixLongWords'] > 5 && $shoutbox['fixLongWords'] < $shoutbox['maxMsgLenght'])
	{
		$non_breaking_space = $context['utf8'] ? ($context['server']['complex_preg_chars'] ? '\x{A0}' : "\xC2\xA0") : '\xA0';

		if ($context['browser']['is_gecko'] || $context['browser']['is_konqueror'])
			$breaker = '<span style="margin:0 -0.5ex 0 0"> </span>';
		elseif ($context['browser']['is_opera'])
			$breaker = '<span style="margin:0 -0.65ex 0 -1px"> </span>';
		else
			$breaker = '<span style="width:0;margin:0 -0.6ex 0 -1px"> </span>';

		$shoutbox['fixLongWords'] = (int) min(1024, $shoutbox['fixLongWords']);

		if (strlen($msg) > $shoutbox['fixLongWords'])
		{
			$msg = strtr($msg, array($breaker => '< >', '&nbsp;' => $context['utf8'] ? "\xC2\xA0" : "\xA0"));
			$msg = preg_replace(
				'~(?<=[>;:!? ' . $non_breaking_space . '\]()]|^)([\w\.]{' . $shoutbox['fixLongWords'] . ',})~e' . ($context['utf8'] ? 'u' : ''),
				'preg_replace(\'/(.{' . ($shoutbox['fixLongWords'] - 1) . '})/' . ($context['utf8'] ? 'u' : '') . '\', \'\\$1< >\', \'$1\')',
				$msg);
			$msg = strtr($msg, array('< >' => $breaker, $context['utf8'] ? "\xC2\xA0" : "\xA0" => '&nbsp;'));
		}
	}

	// style
	$disabled = array();
	if (isset($shoutbox['disableTags']))
		foreach (explode(',', $shoutbox['disableTags']) as $tag)
			$disabled[$tag] = 1;
	$style = '';

	// bold
	if (isset($_POST['bold']) && !isset($disabled['b']))
		$style .= 'font-weight:bold;';
	// italic
	if (isset($_POST['italic']) && !isset($disabled['i']))
		$style .= 'font-style:italic;';
	// underline
	if (isset($_POST['underline']) && !isset($disabled['u']))
		$style .= 'text-decoration:underline;';

	// face
	$_POST['face'] = !empty($_POST['face']) && ereg("^[a-zA-Z0-9 ]{3,30}$", $_POST['face']) ? $_POST['face'] : null;
	if (isset($_POST['face']) && !isset($disabled['face']))
		$style .= 'font-family:' . $_POST['face'] . ';';

	// color & bgcolor
	$_POST['color'] = isset($_POST['color']) && ereg("^(#[a-zA-Z0-9]{3}|#[a-zA-Z0-9]{6}|[a-zA-Z]{3,9})$", $_POST['color']) ? (string) $_POST['color'] : null;
	$_POST['bgcolor'] = isset($_POST['bgcolor']) &&  ereg("^(#[a-zA-Z0-9]{3}|#[a-zA-Z0-9]{6}|[a-zA-Z]{3,9})$", $_POST['bgcolor']) ? (string) $_POST['bgcolor'] : null;

	if (isset($_POST['color']) && !isset($disabled['color']) && isset($_POST['bgcolor']) && !isset($disabled['bgcolor']))
		$style .= 'color:' . $_POST['color'] . ';background-color:' . $_POST['bgcolor'] . ';';
	elseif (isset($_POST['color']) && !isset($disabled['color']))
		$style .= 'color:' . $_POST['color'] . ';background-color:transparent;';
	elseif (isset($_POST['bgcolor']) && !isset($disabled['bgcolor']))
		$style .= 'color:inherit;background-color:' . $_POST['bgcolor'] . ';';

	$style = stripslashes($style);
	$style = $smcFunc['htmltrim']($style);
	$style = $smcFunc['htmlspecialchars']($style, ENT_QUOTES);
	$style = strip_tags($style);
	$style = addslashes($style);

	// is it /me ?
	if ($cmd_me)
		$msg = '<span class="me">' . $msg . '</span>';

	// color gruop
	$query = $smcFunc['db_query']('', "
		SELECT mg.online_color
		FROM {db_prefix}membergroups AS mg
			LEFT JOIN {db_prefix}members AS mem ON (mem.id_member = {int:id_member})
		WHERE mg.id_group = IF(mem.id_group = 0, mem.id_post_group, mem.id_group)
		LIMIT 1",
		array(
			'id_member' => $user_info['id']
		)
	);
	@list($color) = $smcFunc['db_fetch_row']($query);
	$smcFunc['db_free_result']($query);

	// send msg :)
	$smcFunc['db_insert'](
		'insert',
		'{db_prefix}shoutbox',
		array(
			'ID_MEMBER' => 'int',
			'realName' => 'string',
			'style' => 'string',
			'message' => 'string',
			'timestamp' => 'int',
			'colorName' => 'string'
		),
		array(
			$user_info['id'],
			$user_info['name'],
			$style,
			$msg,
			time(),
			!empty($color) ? $color : ''
		),
		array(
			'ID_MEMBER'
		)
	);

	return Shoutbox_GetMsgs();
}

function Shoutbox_Panel()
{
	global $context, $txt;

	if (loadLanguage('Shoutbox') == false)
		loadLanguage('Shoutbox', 'english');

	loadTemplate('Shoutbox');
	Shoutbox_Settings();

	// a moderator must can view and post !!
	$out = !allowedTo('shoutbox_view') || !allowedTo('shoutbox_post');

	$context['shoutbox'] = array();
	$context['shoutbox']['can_ban'] = !$out && allowedTo('shoutbox_ban');
	$context['shoutbox']['can_prune'] = !$out && allowedTo('shoutbox_prune');
	$context['shoutbox']['can_delete'] = !$out && allowedTo(array('shoutbox_delete', 'shoutbox_prune'));
	$context['shoutbox']['can_edit'] = !$out && allowedTo(array('shoutbox_edit', 'shoutbox_delete', 'shoutbox_prune'));
	$context['shoutbox']['can_moderate'] = !$out && allowedTo(array('shoutbox_ban', 'shoutbox_edit', 'shoutbox_delete', 'shoutbox_prune'));

	$context['shoutbox_smf_2'] = true;
	$context['template_layers'] = array();
	$context['sub_template'] = 'shoutbox_panel';
	$context['page_title'] = $txt['sbm_1'];
}

function Shoutbox_Panel_GetMsgs($message = null)
{
	global $context, $txt, $scripturl, $shoutbox, $smcFunc;

	// ...
	if (!isset($_GET['xml']) || !allowedTo('shoutbox_view') || !allowedTo('shoutbox_post'))
		return Shoutbox_Panel();

	$context['shoutbox'] = array();
	$context['shoutbox']['can_delete'] = allowedTo(array('shoutbox_delete', 'shoutbox_prune'));
	$context['shoutbox']['can_edit'] = allowedTo(array('shoutbox_edit', 'shoutbox_delete', 'shoutbox_prune'));

	if (!$context['shoutbox']['can_edit'] || !$context['shoutbox']['can_delete'])
		return Shoutbox_Panel();

	if (loadLanguage('Shoutbox') == false)
		loadLanguage('Shoutbox', 'english');

	loadTemplate('Shoutbox');
	Shoutbox_Settings();

	if (isset($_GET['prune']))
	{
		if (!allowedTo('shoutbox_prune'))
			$message = $txt['sbm_6'];
		else
		{
			$smcFunc['db_query']('', "TRUNCATE TABLE {db_prefix}shoutbox");

			// last prune
			$smcFunc['db_query']('', "
				UPDATE {db_prefix}shoutbox_settings
				SET value = '" . time() . "'
				WHERE variable = 'lastPrune'
				LIMIT 1");

			$message = $txt['sbm_7'];
		}
	}

	// all wants a history :(
	$start = empty($_GET['start']) ? 0 : (int) $_GET['start'];
	$limit = empty($_GET['limit']) ? 0 : (int) $_GET['limit'];
	$url = 'javascript:Shoutbox_GetMsgs(%d);';

	$limit_new = false;
	if ($limit == 0)
	{
		$limit_new = true;

		$query = $smcFunc['db_query']('', "SELECT MAX(ID_SHOUT) FROM {db_prefix}shoutbox");
		@list ($limit) = $smcFunc['db_fetch_row']($query);
		$limit = empty($limit) ? 0 : $limit;
		$smcFunc['db_free_result']($query);
	}

	$query = $smcFunc['db_query']('', "
				SELECT COUNT(*) FROM {db_prefix}shoutbox" . ($limit > 0 ? "
					WHERE ID_SHOUT <= $limit" : ""));
	list ($count) = $smcFunc['db_fetch_row']($query);
	$smcFunc['db_free_result']($query);

	$context['shoutbox']['page_index'] = constructPageIndex($url, $start, $count, $shoutbox['startShouts'], true);

	// get msgs...
	$query = $smcFunc['db_query']('', "
				SELECT ID_SHOUT, ID_MEMBER, realName, colorName, style, message, timestamp
				FROM {db_prefix}shoutbox
					WHERE ID_SHOUT <= $limit
				ORDER BY ID_SHOUT DESC LIMIT $start, " . $shoutbox['startShouts']);

	$context['shoutbox']['msgs'] = array();
	while ($s = $smcFunc['db_fetch_assoc']($query))
	{
		$s['message_out'] = $s['message'];

		// first, delete links :)
		$s['message'] = str_replace('<a href="', '', $s['message']);
		$s['message'] = preg_replace("/\" target=\"\_blank\">.*<\/a>/", '', $s['message']);

		// find /me command
		$cmd_me = substr($s['message'], 0, 17) == '<span class="me">';

		// delete any tag
		$s['message'] = strip_tags($s['message']);

		$s['message'] = $cmd_me ? '/me ' . $s['message'] : $s['message'];
		$s['message_out'] = $cmd_me ? $s['realName'] . ' ' . $s['message_out'] : $s['message_out'];

		$context['shoutbox']['msgs'][] = array(
			'moderation' => $start > 0 ? null : ($context['shoutbox']['can_edit'] ? '<a href="javascript:;" onclick="Shoutbox_EditMsg(\'' . str_replace("'", "\'", $s['message']) . '\', ' . $s['ID_SHOUT'] . ')">[Edit]</a>' : '') . ($context['shoutbox']['can_delete'] ? ' <a href="javascript:;" onclick="if (window.confirm(\'' . $txt['sbm_12'] . '\')) Shoutbox_DeleteMsg(' . $s['ID_SHOUT'] . ');">[Delete]</a>' : ''),
			'user' => ($cmd_me ? '' : '<a href="' . $scripturl . '?action=profile;u=' . $s['ID_MEMBER'] . '" target="_blank"' . (!empty($s['colorName']) ? ' style="color:' . $s['colorName'] . '"' : '') . '>' . $s['realName'] . '</a>') . ' <span style="color:' . $shoutbox['timeColor'] . '">[' . ($s['timestamp'] > 0 ? timeformat($s['timestamp'], $shoutbox['timeFormat']) : $txt[470]) . ']</span>:',
			'msg' => '<span style="' . $s['style'] . '">' . Shoutbox_ParseSmileys($s['message_out']) . '</span>',
		);
	}
	$smcFunc['db_free_result']($query);

	if (isset($message))
		$context['shoutbox']['msg'] = $message;

	if ($limit_new)
		$context['shoutbox']['limit'] = $limit;

	$context['sub_template'] = 'shoutbox_panel_getmsgs';
}

function Shoutbox_Panel_EditMsg()
{
	global $smcFunc, $context, $txt, $shoutbox;

	if (!isset($_GET['xml']) || !allowedTo('shoutbox_view') || !allowedTo('shoutbox_post'))
		return Shoutbox_Panel();

	if (checkSession('get', '', false) != '' || empty($_REQUEST['shout']))
		return Shoutbox_Panel();

	if (loadLanguage('Shoutbox') == false)
		loadLanguage('Shoutbox', 'english');

	loadTemplate('Shoutbox');
	Shoutbox_Settings();

	// if can't edit, how is it here?
	if (!allowedTo(array('shoutbox_edit', 'shoutbox_delete', 'shoutbox_prune')))
		return Shoutbox_Panel();

	if (isset($_GET['delete']))
	{
		if (!allowedTo(array('shoutbox_delete', 'shoutbox_prune')))
			return Shoutbox_Panel_GetMsgs($txt['sbm_9']);

		$shout = (int) $_REQUEST['shout'];
		$smcFunc['db_query']('', "
			DELETE FROM {db_prefix}shoutbox
			WHERE ID_SHOUT = {int:id_shout}
			LIMIT 1",
			array(
				'id_shout' => $shout
			)
		);
		return Shoutbox_Panel_GetMsgs($txt['sbm_11']);
	}

	// the message ID
	$shout = (int) $_REQUEST['shout'];

	// skip if there is no msg
	if (isset($_POST['msg']))
	{
		$msg = (string) $_POST['msg'];
		unset($_POST['msg']);

		$msg = stripslashes($msg);
		$msg = $smcFunc['htmltrim']($msg);
		$msg = $smcFunc['htmlspecialchars']($msg, ENT_QUOTES);
		$msg = strip_tags($msg);
		$msg = addslashes($msg);

		// command /me
		$cmd_me = false;
		if (substr($msg, 0, 4) == '/me ')
		{
			$cmd_me = true;
			$msg = substr_replace($msg, '', 0, 4);
		}

		// from here any error will be printed
		if ($smcFunc['strlen']($msg) < $shoutbox['minMsgLenght'])
			return Shoutbox_Panel_GetMsgs($txt['sb_12'] . $shoutbox['minMsgLenght']);
		if ($smcFunc['strlen']($msg) > $shoutbox['maxMsgLenght'])
			return Shoutbox_Panel_GetMsgs($txt['sb_12b'] . $shoutbox['maxMsgLenght']);

		// find links to convert
		if (strstr($msg, 'http://'))
		{
			$links = array();
			foreach (explode(' ', $msg) as $w)
				if (substr($w, 0, 7) == 'http://' && $smcFunc['strlen']($w) > 7)
					$links[] = '<a href="' . $w . '" target="_blank">' . ($smcFunc['strlen']($w) > $shoutbox['maxLinkLenght'] ? substr($w, 0, $shoutbox['maxLinkLenght'] - 8) . '...' . substr($w, -8) : $w) . '</a>';
				else
					$links[] = $w;
			$msg = implode(' ', $links);
		}

		// verifying word isn't too long... same SMF function ;)
		if ($shoutbox['fixLongWords'] > 5)
		{
			$non_breaking_space = $context['utf8'] ? ($context['server']['complex_preg_chars'] ? '\x{A0}' : "\xC2\xA0") : '\xA0';

			if ($context['browser']['is_gecko'] || $context['browser']['is_konqueror'])
				$breaker = '<span style="margin:0 -0.5ex 0 0"> </span>';
			elseif ($context['browser']['is_opera'])
				$breaker = '<span style="margin:0 -0.65ex 0 -1px"> </span>';
			else
				$breaker = '<span style="width:0;margin:0 -0.6ex 0 -1px"> </span>';

			$shoutbox['fixLongWords'] = (int) min(65535, $shoutbox['maxMsgLenght'], $shoutbox['fixLongWords']);

			if (strlen($msg) > $shoutbox['fixLongWords'])
			{
				$msg = strtr($msg, array($breaker => '< >', '&nbsp;' => $context['utf8'] ? "\xC2\xA0" : "\xA0"));
				$msg = preg_replace(
					'~(?<=[>;:!? ' . $non_breaking_space . '\]()]|^)([\w\.]{' . $shoutbox['fixLongWords'] . ',})~e' . ($context['utf8'] ? 'u' : ''),
					'preg_replace(\'/(.{' . ($shoutbox['fixLongWords'] - 1) . '})/' . ($context['utf8'] ? 'u' : '') . '\', \'\\$1< >\', \'$1\')',
					$msg);
				$msg = strtr($msg, array('< >' => $breaker, $context['utf8'] ? "\xC2\xA0" : "\xA0" => '&nbsp;'));
			}
		}

		// is it /me ?
		if ($cmd_me)
			$msg = '<span class="me">' . $msg . '</span>';

		$smcFunc['db_query']('', "
			UPDATE {db_prefix}shoutbox
			SET message = {string:message}
			WHERE ID_SHOUT = {int:id_shout}
			LIMIT 1",
			array(
				'message' => $msg,
				'id_shout' => $shout
			)
		);
	}

	// delete style?
	if (isset($_POST['style']))
		$smcFunc['db_query']('', "
			UPDATE {$db_prefix}shoutbox
			SET style = ''
			WHERE ID_SHOUT = {int:id_shout}
			LIMIT 1",
			array(
				'id_shout' => $shout
			)
		);

	return Shoutbox_Panel_GetMsgs($txt['sbm_10']);
}

function Shoutbox_Panel_ListUsers()
{
	global $context, $smcFunc, $txt, $scripturl;

	if (!isset($_GET['xml']) || !allowedTo('shoutbox_view') || !allowedTo('shoutbox_post') || !allowedTo('shoutbox_ban'))
		return Shoutbox_Panel();

	if (loadLanguage('Shoutbox') == false)
		loadLanguage('Shoutbox', 'english');

	loadTemplate('Shoutbox');

	$context['shoutbox_echo'] = array();

	if (isset($_GET['delete']) && checkSession('get', '', false) == '')
	{
		$u = (int) $_GET['delete'];
		$smcFunc['db_query']('', "
			DELETE FROM {db_prefix}shoutbox_ban
			WHERE ID_MEMBER = {int:id_member} LIMIT 1",
			array(
				'id_member' => $u
			)
		);

		$context['shoutbox_echo']['msg'] = $txt['sbm_26'];
	}
	elseif (isset($_POST['user']) && checkSession('get', '', false) == '')
	{
		$u = (int) $_POST['user'];

		$reason = empty($_POST['reason']) ? '' : addslashes(strip_tags($smcFunc['htmlspecialchars']($smcFunc['htmltrim'](stripslashes($_POST['reason'])))));
		$days = empty($_POST['days']) ? 0 : ((int) $_POST['days']) * 86400;

		$smcFunc['db_query']('', "
			UPDATE {db_prefix}shoutbox_ban
			SET
				banEnd = " . ($days == 0 ? "0" : "banStart + {int:days}") . ",
				reason = {string:reason}
			WHERE ID_MEMBER = {int:id_member} LIMIT 1",
			array(
				'days' => $days == 0 ? null : $days,
				'reason' => $reason,
				'id_member' => $u
			)
		);

		// last ban update
		$smcFunc['db_query']('', "
			UPDATE {db_prefix}shoutbox_settings
			SET value = {int:time}
			WHERE variable = 'banUpadte'
			LIMIT 1",
			array(
				'time' => time()
			)
		);

		$context['shoutbox_echo']['msg'] = $txt['sbm_27'];
	}

	$query = $smcFunc['db_query']('', "
				SELECT
					s.ID_MEMBER, s.banStart, s.banEnd, s.reason, s.banBy, s.banByID,
					IFNULL(mem.real_name, '') as realName
				FROM {db_prefix}shoutbox_ban AS s
					LEFT JOIN {db_prefix}members AS mem ON (mem.id_member = s.ID_MEMBER)
				ORDER BY mem.real_name");
	$context['shoutbox_echo']['list'] = array();

	while ($u = $smcFunc['db_fetch_assoc']($query))
		$context['shoutbox_echo']['list'][] = array(
			'moderation' => '<a href="javascript:;" onclick="Shoutbox_EditUser(' . $u['ID_MEMBER'] . ', ' . ($u['banEnd'] == 0 ? 0 : ($u['banEnd'] - $u['banStart']) / 86400) . ', \'' . str_replace("'", "\'", $u['reason']) . '\')">[Edit]</a> <a href="javascript:;" onclick="if (window.confirm(\'' . $txt['sbm_25'] . '\')) Shoutbox_DeleteUser(' . $u['ID_MEMBER'] . ')">[Delete]</a>',
			'user' => '<a href="' . $scripturl . '?action=profile;u=' . $u['ID_MEMBER'] . '" target="_blank">' . $u['realName'] . '</a>',
			'reason' => $u['reason'] == '' ? '-' : $u['reason'],
			'details' => '<b>' . $txt['sbm_13'] . ':</b> ' . ($u['banEnd'] < time() ? '<span style="color:red">' : '') . ($u['banEnd'] == 0 ? $txt['sb_8'] : ($u['banEnd'] > 0 ? timeformat($u['banEnd']) : $txt[470])) . ($u['banEnd'] < time() ? '</span>' : '') . '<br /><b>' . $txt['sbm_14'] . ':</b> <a href="' . $scripturl . '?action=profile;u=' . $u['banByID'] . '" target="_blank">' . $u['banBy'] . '</a> [' . ($u['banStart'] > 0 ? timeformat($u['banStart']) : $txt[470]) . ']'
		);
	$smcFunc['db_free_result']($query);

	$context['sub_template'] = 'shoutbox_panel_listusers';
}

function Shoutbox_Panel_BanUsers()
{
	global $context, $txt, $scripturl, $smcFunc, $user_info;

	if (!isset($_GET['xml']) || !allowedTo('shoutbox_view') || !allowedTo('shoutbox_post') || !allowedTo('shoutbox_ban'))
		return Shoutbox_Panel();

	if (loadLanguage('Shoutbox') == false)
		loadLanguage('Shoutbox', 'english');

	loadTemplate('Shoutbox');

	if (isset($_POST['users']))
	{
		if (checkSession('get', '', false) != '')
			return Shoutbox_Panel();

		// function from SMF
		if (trim($_POST['users']) != '')
		{
			$users_string = strtr(addslashes($smcFunc['htmlspecialchars'](stripslashes($_POST['users']), ENT_QUOTES)), array('&quot;' => '"'));
			preg_match_all('~"([^"]+)"~', $users_string, $matches);
			$users = array_merge($matches[1], explode(',', preg_replace('~"([^"]+)"~', '', $users_string)));
			for ($k = 0, $n = count($users); $k < $n; $k++)
			{
				$users[$k] = trim($users[$k]);
				if (strlen($users[$k]) == 0)
					unset($users[$k]);
			}

			$usersIDs = array();
			if (!empty($users))
			{
				$request = $smcFunc['db_query']('', "
							SELECT id_member FROM {db_prefix}members
							WHERE member_name IN ('" . implode("','", $users) . "') 
								OR real_name IN ('" . implode("','", $users) . "')
							LIMIT " . count($users));
				while ($row = $smcFunc['db_fetch_assoc']($request))
					$usersIDs[] = $row['id_member'];
				$smcFunc['db_free_result']($request);
			}
		}
		if (empty($usersIDs))
		{
			// return the error...
			$context['shoutbox_echo']['msg'] = $txt['sbm_23'];
			$context['sub_template'] = 'shoutbox_panel_banusers';

			return;
		}

		$reason = empty($_POST['reason']) ? '' : addslashes(strip_tags($smcFunc['htmlspecialchars']($smcFunc['htmltrim'](stripslashes($_POST['reason'])))));
		$start = time();
		$days = empty($_POST['days']) ? 0 : ((int) $_POST['days']) * 86400 + $start;

		// delete old data
		/* will use REPLACE
		db_query("
			DELETE FROM {$db_prefix}shoutbox_ban
			WHERE ID_MEMBER IN (" . implode(",", $usersIDs) . ")
			LIMIT " . count($usersIDs), __FILE__, __LINE__); */

		// insert new data
		$set = '';
		foreach ($usersIDs as $u)
			$smcFunc['db_insert'](
				'replace',
				'{db_prefix}shoutbox_ban',
				array(
					'ID_MEMBER' => 'int',
					'banStart' => 'string',
					'banEnd' => 'string',
					'reason' => 'string',
					'banBy' => 'string',
					'banByID' => 'int'
				),
				array(
					$u,
					$start,
					$days,
					$reason,
					$user_info['name'],
					$user_info['id']
				),
				array(
					'ID_MEMBER'
				)
			);

		// last ban update
		$smcFunc['db_query']('', "
				UPDATE {db_prefix}shoutbox_settings
				SET value = '" . time() . "'
				WHERE variable = 'banUpadte'
				LIMIT 1");

		$context['shoutbox_echo']['msg'] = $txt['sbm_24'];
	}

	$context['shoutbox_echo']['form'] = true;
	$context['sub_template'] = 'shoutbox_panel_banusers';
}

?>
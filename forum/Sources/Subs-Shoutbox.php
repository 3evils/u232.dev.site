<?php
/**********************************************************************************
* Subs-Shoutbox.php                                                               *
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

function Shoutbox_Load($action = null)
{
	global $shoutbox, $context;

	// die
	if (Shoutbox_Settings('disable') != '')
		return $action;

	if ($action !== null)
	{
		$show = Shoutbox_Settings('showActions');
		if (!in_array(strtolower($action), explode(',', $show)))
			return $action;
	}
	$context['shoutbox'] = array();
	$context['shoutbox']['can_moderate'] = !$context['user']['is_guest'] && allowedTo(array('shoutbox_edit', 'shoutbox_delete', 'shoutbox_prune', 'shoutbox_ban'));
	$context['shoutbox']['banned'] = Shoutbox_isBanned();
	$context['shoutbox']['can_view'] = !$context['shoutbox']['banned'] && allowedTo('shoutbox_view');

	if (!$context['shoutbox']['can_view'] && !$context['shoutbox']['banned'])
	{
		unset($context['shoutbox']);
		return $action;
	}
	$context['shoutbox']['can_post'] = !$context['user']['is_guest'] && $context['shoutbox']['can_view'] && allowedTo('shoutbox_post');

	// load config, template and language :P
	if (loadLanguage('Shoutbox') == false)
		loadLanguage('Shoutbox', 'english');

	loadTemplate('Shoutbox');
	Shoutbox_Settings();

	// print from main?
	if (isset($shoutbox['out_main']) && in_array(strtolower($action), explode(',', $shoutbox['out_main'])))
		$context['shoutbox']['out_main'] = 1;

	// post features... if can't post, scape
	if (!$context['shoutbox']['can_post'])
		return $action;

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

	return $action;
}

// use allowedTo('shoutbox_view') or allowedTo('shoutbox_post') before
// $shoutbox must be defined
function Shoutbox_isBanned()
{
	global $context, $txt, $smcFunc, $shoutbox;

	// shoutbox doesn't ban guest
	if ($context['user']['is_guest'] || $context['user']['is_admin'])
		return false;

	// we need this be faster...
	if (!empty($_SESSION['shoutbox_lastget']) && $_SESSION['shoutbox_lastget'] > $shoutbox['banUpadte'])
		return false;

	// if can moderate, can't be banned...
	if (allowedTo(array('shoutbox_edit', 'shoutbox_delete', 'shoutbox_prune', 'shoutbox_ban')))
		return false;

	$query = $smcFunc['db_query']('', "
		SELECT reason, banEnd
		FROM {db_prefix}shoutbox_ban
		WHERE ID_MEMBER = {int:id_member}
		LIMIT 1",
		array(
			'id_member' => $context['user']['id']
		)
	);
	$row = $smcFunc['db_fetch_assoc']($query);
	$smcFunc['db_free_result']($query);

	if (empty($row))
		return false;

	if ($row['banEnd'] < time() && $row['banEnd'] != 0)
	{
		$smcFunc['db_query']('', "
			DELETE FROM {db_prefix}shoutbox_ban
			WHERE ID_MEMBER = {int:id_member}
			LIMIT 1",
			array(
				'id_member' => $context['user']['id']
			)
		);
		return false;
	}

	// ban :(
	$_SESSION['shoutbox_lastget'] = null;
	$_SESSION['shoutbox_lastid'] = null;

	return array(
		'reason' => $row['reason'],
		'end' => ($row['banEnd'] == 0 ? $txt['sb_8'] : ($row['banEnd'] > 0 ? timeformat($row['banEnd']) : $txt[470]))
	);	
}

function Shoutbox_Settings($setting = null)
{
	global $shoutbox, $smcFunc;

	if ($setting !== null)
	{
		$setting = (string) $setting;

		// if $setting is defined
		$result = $smcFunc['db_query']('', "
					SELECT value
					FROM {db_prefix}shoutbox_settings
					WHERE variable = {string:setting}
					LIMIT 1",
					array(
						'setting' => $setting
					)
				);
		$r = $smcFunc['db_fetch_row']($result);
		$smcFunc['db_free_result']($result);

		// return empty value if not found
		return !empty($r[0]) ? $r[0] : '';
	}

	// redefine if exists :|
	$shoutbox = array();

	$result = $smcFunc['db_query']('', "SELECT variable, value FROM {db_prefix}shoutbox_settings");
	while ($r = $smcFunc['db_fetch_assoc']($result))
		$shoutbox[$r['variable']] = $r['value'];
	$smcFunc['db_free_result']($result);
}

function Shoutbox_LoadSmileys()
{
	global $context, $modSettings, $user_info, $txt, $smcFunc;

	if (!empty($context['smileys']))
		return $context['smileys'];

	$context['smileys'] = array(
		'postform' => array(),
		'popup' => array(),
	);

	// Load smileys - don't bother to run a query if we're not using the database's ones anyhow.
	if (empty($modSettings['smiley_enable']) && $user_info['smiley_set'] != 'none')
		$context['smileys']['postform'][] = array(
			'smileys' => array(
				array('code' => ':)', 'filename' => 'smiley.gif', 'description' => $txt['icon_smiley']),
				array('code' => ';)', 'filename' => 'wink.gif', 'description' => $txt['icon_wink']),
				array('code' => ':D', 'filename' => 'cheesy.gif', 'description' => $txt['icon_cheesy']),
				array('code' => ';D', 'filename' => 'grin.gif', 'description' => $txt['icon_grin']),
				array('code' => '>:(', 'filename' => 'angry.gif', 'description' => $txt['icon_angry']),
				array('code' => ':(', 'filename' => 'sad.gif', 'description' => $txt['icon_sad']),
				array('code' => ':o', 'filename' => 'shocked.gif', 'description' => $txt['icon_shocked']),
				array('code' => '8)', 'filename' => 'cool.gif', 'description' => $txt['icon_cool']),
				array('code' => '???', 'filename' => 'huh.gif', 'description' => $txt['icon_huh']),
				array('code' => '::)', 'filename' => 'rolleyes.gif', 'description' => $txt['icon_rolleyes']),
				array('code' => ':P', 'filename' => 'tongue.gif', 'description' => $txt['icon_tongue']),
				array('code' => ':-[', 'filename' => 'embarrassed.gif', 'description' => $txt['icon_embarrassed']),
				array('code' => ':-X', 'filename' => 'lipsrsealed.gif', 'description' => $txt['icon_lips']),
				array('code' => ':-\\', 'filename' => 'undecided.gif', 'description' => $txt['icon_undecided']),
				array('code' => ':-*', 'filename' => 'kiss.gif', 'description' => $txt['icon_kiss']),
				array('code' => ':\'(', 'filename' => 'cry.gif', 'description' => $txt['icon_cry'])
			),
			'last' => true,
		);
	elseif ($user_info['smiley_set'] != 'none')
	{
		if (($temp = cache_get_data('posting_smileys', 480)) == null)
		{
			$request = $smcFunc['db_query']('', '
				SELECT code, filename, description, smiley_row, hidden
				FROM {db_prefix}smileys
				WHERE hidden IN (0, 2)
				ORDER BY smiley_row, smiley_order',
				array(
				)
			);
			while ($row = $smcFunc['db_fetch_assoc']($request))
			{
				$row['filename'] = htmlspecialchars($row['filename']);
				$row['description'] = htmlspecialchars($row['description']);

				$context['smileys'][empty($row['hidden']) ? 'postform' : 'popup'][$row['smiley_row']]['smileys'][] = $row;
			}
			$smcFunc['db_free_result']($request);

			cache_put_data('posting_smileys', $context['smileys'], 480);
		}
		else
			$context['smileys'] = $temp;
	}

	// Clean house... add slashes to the code for javascript.
	foreach (array_keys($context['smileys']) as $location)
	{
		foreach ($context['smileys'][$location] as $j => $row)
		{
			$n = count($context['smileys'][$location][$j]['smileys']);
			for ($i = 0; $i < $n; $i++)
			{
				$context['smileys'][$location][$j]['smileys'][$i]['code'] = addslashes($context['smileys'][$location][$j]['smileys'][$i]['code']);
				$context['smileys'][$location][$j]['smileys'][$i]['js_description'] = addslashes($context['smileys'][$location][$j]['smileys'][$i]['description']);
			}

			$context['smileys'][$location][$j]['smileys'][$n - 1]['last'] = true;
		}
		if (!empty($context['smileys'][$location]))
			$context['smileys'][$location][count($context['smileys'][$location]) - 1]['last'] = true;
	}

	return $context['smileys'];
}

// only parse smileys
function Shoutbox_ParseSmileys($message)
{
	parsesmileys($message);

	return $message;
}

?>
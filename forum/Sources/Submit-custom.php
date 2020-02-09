<?php
/**********************************************************************************
* Submit-custom.php                                                               *
***********************************************************************************
* SMF Arcade                                                                      *
* =============================================================================== *
* Software Version:           SMF Arcade 2.5 RC1                                  *
* Software by:                Niko Pahajoki (http://www.madjoki.com)              *
* Copyright 2004-2009 by:     Niko Pahajoki (http://www.madjoki.com)              *
* Support, News, Updates at:  http://www.smfarcade.info                           *
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

/*
	!!!
*/

function ArcadeCustomPlay(&$game, &$session, $xml = false)
{
	global $scripturl, $txt, $db_prefix, $context, $modSettings, $smcFunc;

	require_once($modSettings['gamesDirectory'] . '/' . $game['directory'] . '/' . $game['file']);
	
	$game['class'] = new $game['internal_name']($game['id'], $game['url']['base_url'], $scripturl . '?action=arcade;game=' . $game['id']);

	// If data is missing this is new game
	$newGame = !isset($session['custom_data']) || empty($session['custom_data']);

	if ($newGame)
		$game['class']->newSession();
	else
		$game['class']->setSession($session['custom_data']);

	$context['playing_custom'] = true;

	$game['class']->main();
	
	$session['custom_data'] = $game['class']->getSession();
		
	return $newGame ? true : 'noincrease';
}

function ArcadeCustomXMLPlay(&$game, &$session)
{
	return ArcadeCustomPlay($game, $session, true);
}

function ArcadeCustomHtml(&$game, $auto_start = true)
{
	global $txt, $context, $settings;

	echo '
	<div id="game">
		', $game['class']->showGame(), '
	</div>';

	unset($context['arcade']['game']['class']);
}

function ArcadeCustomSubmit($game, $session)
{
	global $context;

	$result = $context['game']['class']->getResult();
	unset($context['game']['class']);

	return array(
		'cheating' => '',
		'score' => $result['score'],
		'start_time' => $result['start_time'],
		'duration' => round($result['end_time'] - $result['start_time'], 0),
		'end_time' => round($result['end_time'], 0)
	);
}

?>
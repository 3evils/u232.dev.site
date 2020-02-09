<?php
/**********************************************************************************
* newscore.php                                                                    *
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

// Provides support for phpbb games
if (!isset($_POST['game_name']))
	die('Hacking attempt...');

$_POST['action'] = 'arcade';
$_POST['sa'] = 'submit';
$_POST['phpbb'] = true;

require_once(dirname(__FILE__) . '/index.php');

?>
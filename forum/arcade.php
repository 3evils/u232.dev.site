<?php
/**********************************************************************************
* arcade.php                                                                      *
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

if (!isset($_REQUEST['sessdo']))
	die('Hacking attempt...');

$_POST['action'] = 'arcade';
if (isset($_REQUEST['gamename']))
	$_POST['game'] = $_REQUEST['gamename'];
$_POST['v3arcade'] = true;

if ($_REQUEST['sessdo'] == 'sessionstart')
	$_POST['sa'] = 'vbSessionStart';
elseif ($_REQUEST['sessdo'] == 'permrequest')
	$_POST['sa'] = 'vbPermRequest';
elseif ($_REQUEST['sessdo'] == 'burn')
	$_POST['sa'] = 'vbBurn';
else
	die('Hacking attempt...');

require_once(dirname(__FILE__) . '/index.php');

?>
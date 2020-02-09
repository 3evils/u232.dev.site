<?php
/********************************************************
* Customized profile
* Version: 1.1 
* Official support: SmfPersonal
* Founder: ^HeRaCLeS^
* Date: 2010
/**********************************************************/

if (!defined('SMF'))
	die('Hacking attempt...');

	
function CustomizedProfile()
{
	global $context, $txt, $smcFunc;
  
	isAllowedTo('admin_forum');
	loadTemplate('CustomizedProfile');
	$context[$context['admin_menu_name']]['tab_data'] = array(
		'title' => $txt['CP_title'],
		'description' => $txt['CP_desc'],
		'tabs' => array(
			'main' => array(
				'description' => '',
			),
		),
	);
	if(isset($_REQUEST['sa']) && $_REQUEST['sa'] == 'save')
	{
	checkSession('post');
	updateSettings(
		array(
			'CP_enable' => !empty($_POST['CP_enable']) ? 1 : 0,
			'CP-mp_enable' => !empty($_POST['CP-mp_enable']) ? 1 : 0,
			'Customizedprofile' => $smcFunc['htmlspecialchars']($_POST['color']),
		)
	);
	redirectexit('action=admin;area=CustomizedProfile');
	}
	else
	{
	$context['page_title'] = $txt['CP_title_nav'] .' - '.$context['forum_name'];
	$context['sub_template'] = 'Customizedprofile_settings';
	}
}
?>	
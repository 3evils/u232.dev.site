<?php
// Version: 1.2: AdvancedNews

if (!defined('SMF'))
	die('Hacking attempt...');

function advanced_news_admin_areas(&$admin_areas)
{
	global $txt;

	loadLanguage('AdvancedNews');
	$admin_areas['config']['areas']['modsettings']['subsections']['advancednews'] = array($txt['advanced_news_title']);
}

function advanced_news_modify_modifications(&$sub_actions)
{
	$sub_actions['advancednews'] = 'ModifyAdvancedNewsSettings';
}

function advanced_news_actions(&$actions)
{
	$actions['news'] = array('AdvancedNews.php', 'News');
}

function ModifyAdvancedNewsSettings($return_config = false)
{
	global $context, $txt, $scripturl;

	$config_vars = array(
		array('check', 'disable_advanced_news_page'),
	);

	if ($return_config)
		return $config_vars;

	if (isset($_GET['save']))
	{
		checkSession();

		// We wanna be sure the menu item isn't stuck in cache hell, now, don't we?
		clean_cache();

		saveDBSettings($config_vars);
		writeLog();

		redirectexit('action=admin;area=modsettings;sa=advancednews');
	}

	$context['post_url'] = $scripturl . '?action=admin;area=modsettings;save;sa=advancednews';
	$context['settings_title'] = $txt['advanced_news_settings_title'];

	prepareDBSettingContext($config_vars);
}

function advanced_news_load_theme()
{
	global $context, $modSettings, $settings;

	$context['can_view_news'] = allowedTo('view_news');

	if (!$context['can_view_news'])
	{
		$settings['enable_news'] = false;
		$modSettings['news'] = '';
	}
}

function advanced_news_load_permissions(&$permissionGroups, &$permissionList, &$leftPermissionGroups, &$hiddenPermissions, &$relabelPermissions)
{
	loadLanguage('AdvancedNews');
	$permissionList['membergroup'] += array(
		'view_news' => array(false, 'general', 'view_basic_info'),
	);
}

function advanced_news_menu_buttons($menu_buttons)
{
	global $txt, $context, $scripturl, $modSettings, $settings;

	$new_button = array(
		'title' => $txt['news'],
		'href' => $scripturl . '?action=news',
		'show' => empty($modSettings['disable_advanced_news_page']) && allowedTo('view_news') && !empty($settings['enable_news']),
	);

	$new_menu_buttons = array();
	foreach ($menu_buttons as $area => $info)
	{
		$new_menu_buttons[$area] = $info;
		if ($area == 'home')
			$new_menu_buttons['news'] = $new_button;
	}

	$menu_buttons = $new_menu_buttons;
}

function News()
{
	global $context, $txt, $scripturl, $modSettings;

	// Load our template...
	loadTemplate('AdvancedNews');

	// Define our page title...
	$context['page_title'] = $txt['news']. ' - '. $context['forum_name'];

	// Define our linktree item.
	$context['linktree'][] = array(
		'url' => $scripturl. '?action=news',
		'name' => $txt['news'],
	);

	// If the News Page is disabled, output a fatal error.
	if (!empty($modSettings['disable_advanced_news_page']))
		fatal_lang_error('advanced_news_page_disabled', false);

	// Are we allowed to view the forum news?
	isAllowedTo('view_news');

	// I got style. Do you?
	$context['html_headers'] .= '
	<style type="text/css">
		div#newspage div:not(:last-child)
		{
			padding-bottom: 0.5em;
			margin-bottom: 0.5em;
			border-bottom: dotted 1px grey;
		}
	</style>';
}

?>
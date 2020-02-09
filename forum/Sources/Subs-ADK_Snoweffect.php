<?php
/********************************************************
* Adk Snow Effect           
* Version: 2.0
* Official support: http://www.smfpersonal.net 
* Author: enik
* Update and Optimization: ^HeRaCLeS^ 
* 2011
/**********************************************************/

if (!defined('SMF'))
    die('Hacking attempt...');
    
function ADK_Snoweffect() {
	global $settings, $modSettings, $context, $txt;

	if(!empty($modSettings['enable_adk_snow_effect'])){
		if (!empty($modSettings['ADK_SeVelo'])) {
			if ($modSettings['ADK_SeVelo'] == 'slow') {
				$ADK_SeVelox = '5';
				$ADK_SeVeloy = '4';
			}
			elseif ($modSettings['ADK_SeVelo'] == 'median') {
				$ADK_SeVelox = '8';
				$ADK_SeVeloy = '7';
			}
			elseif ($modSettings['ADK_SeVelo'] == 'fast') {
				$ADK_SeVelox = '11';
				$ADK_SeVeloy = '10';
			}
			else {
				$ADK_SeVelox = '5';
				$ADK_SeVeloy = '4';
			}
		}
		if (!empty($modSettings['ADK_SeStick'])) {
			$ADK_SeStick = false;
		}
		else  {
			$ADK_SeStick = true;
		}
		if (!empty($modSettings['ADK_SeMouse'])) {
			$ADK_SeMouse = false;
		}
		else  {
			$ADK_SeMouse = true;
		}
		if ($modSettings['ADK_SeChart'] == 1) { $ADK_SeChart = '*'; }  
		elseif ($modSettings['ADK_SeChart'] == 2) { $ADK_SeChart = '&bull;'; }  
		elseif ($modSettings['ADK_SeChart'] == 3) { $ADK_SeChart = '&middot;'; }  
		elseif ($modSettings['ADK_SeChart'] == 4) { $ADK_SeChart = '&curren;'; }  
		elseif ($modSettings['ADK_SeChart'] == 5) { $ADK_SeChart = '&times;'; }  
		elseif ($modSettings['ADK_SeChart'] == 6) { $ADK_SeChart = '*'; }  

		$context['insert_after_template'] .= '
			<script type="text/javascript"><!-- // --><![CDATA[
				var ADK_Secolor = "#'. $modSettings['ADK_Secolor'] .'";
				var ADK_SeChart = "'. $ADK_SeChart .'";
				var ADK_SeVelox = "'. $ADK_SeVelox .'";
				var ADK_SeVeloy = "'. $ADK_SeVeloy .'";
				var ADK_SeStick = "'. $ADK_SeStick .'";
				var ADK_SeMouse = "'. $ADK_SeMouse .'";
			// ]]></script>
		  <script type="text/javascript" src="'. $settings['default_theme_url']. '/scripts/snowstorm.js"></script>';
	}
}

function AdminADK_Snoweffect(&$admin_areas)
{
	global $txt;
		
	$admin_areas['config']['areas']['Adkeffect'] = array(
		'label' => $txt['Adkseffect_name'],
		'file' => 'Subs-ADK_Snoweffect.php',
		'function' => 'AddsnoweffectSettings',
		'icon' => 'themes.gif',	
		'subsections' => array(
			'AdkSnowEffect' => array($txt['Adkseffect_name']),
		),
	);
}

function AddsnoweffectSettings($return_config = false)
{
	global $txt, $scripturl, $context, $sourcedir;
	require_once($sourcedir . '/ManageSettings.php');
	$context['page_title'] = $txt['Adkseffect_name'];
	$subActions = array(
		'AdkSnowEffect' => 'AdkSnowEffect',
	);

	loadGeneralSettingParameters($subActions, 'AdkSnowEffect');

	$context[$context['admin_menu_name']]['tab_data'] = array(
		'title' => $txt['Adkseffect_name'],
		'description' => $txt['Adkseffect_desc'],
		'tabs' => array(
			'AdkSnowEffect' => array(
			),
		),
	);

	call_user_func($subActions[$_REQUEST['sa']]);
}

function AdkSnowEffect($return_config = false)
{
	global $context, $sourcedir, $txt, $scripturl, $modSettings, $settings;
	require_once($sourcedir . '/ManageServer.php');

	$config_vars = array(
		array('check', 'enable_adk_snow_effect'),
		'',
		array(
		'select',
		'ADK_SeVelo',
			array(
				'slow' => $txt['ADK_SeVelo_Default'],
				'slow' => $txt['ADK_SeVelo_slow'],
				'median' => $txt['ADK_SeVelo_median'],
				'fast' => $txt['ADK_SeVelo_fast'],
			)
		),
		array(
		'select',
		'ADK_SeChart',
			array(
				'1' => $txt['Adk_efChart_Default'],
				'2' => $txt['Adk_efChart_bull'],
				'3' => $txt['Adk_efChart_middot'],
				'4' => $txt['Adk_efChart_curren'],
				'5' => $txt['Adk_efChart_times'],
				'6' => $txt['Adk_efChart_ast'],
			)
		),
		array(
		'select',
		'ADK_Secolor',
			array(
				'F7F7F7' => $txt['Adk_efColor_Default'],
				'000000' => $txt['Adk_efColor_black'],
				'C0C0C0' => $txt['Adk_efColor_silver'],
				'FFFFFF' => $txt['Adk_efColor_white'],
				'FFFF00' => $txt['Adk_efColor_yellow'],
				'FFA500' => $txt['Adk_efColor_orange'],
				'FF0000' => $txt['Adk_efColor_red'],
				'FFC8CB' => $txt['Adk_efColor_pink'],
				'800080' => $txt['Adk_efColor_purple'],
				'008000' => $txt['Adk_efColor_green'],
				'008080' => $txt['Adk_efColor_teal'],
				'00FF00' => $txt['Adk_efColor_lime_green'],
				'0000FF' => $txt['Adk_efColor_blue'],
				'0066FF' => $txt['Adk_efColor_blue1'],
				'000080' => $txt['Adk_efColor_navy'],
				'800000' => $txt['Adk_efColor_maroon'],
				'A52525' => $txt['Adk_efColor_brown'],
				'F5F5DC' => $txt['Adk_efColor_beige'],
			)
		),
		array('check', 'ADK_SeStick'),
		array('check', 'ADK_SeMouse'),
	);

	if ($return_config)
		return $config_vars;

	$context['post_url'] = $scripturl . '?action=admin;area=Adkeffect;sa=AdkSnowEffect;save';
	$context['page_title'] = $txt['Adkseffect_name'];
	$context['settings_insert_below'] = $txt['Adkseffect_donate'];		

	if (isset($_GET['save'])){
	
		checkSession();
		saveDBSettings($config_vars);
		redirectexit('action=admin;area=Adkeffect;sa=AdkSnowEffect');
	}

	prepareDBSettingContext($config_vars);
}

?>
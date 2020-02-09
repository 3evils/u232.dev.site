<?php
if (!defined('SMF'))
	die('Hacking attempt...'); 

/*  Annoy User!!  */

function AnnoyUserToggle() {
	global $user_info, $user_profile, $context, $smcFunc, $txt;

	checkSession('get');

	isAllowedTo('annoyuser');
	is_not_guest();

	if (empty($_REQUEST['u']))
		fatal_lang_error('no_access', false);
	$_REQUEST['u'] = (int) $_REQUEST['u'];
	
	if($_REQUEST['u'] == $context['user']['id']) // Hallowed Be Thy Name
		fatal_lang_error('cannot_annoy_yourself');

	$query = $smcFunc['db_query']('', '
		SELECT annoyuser FROM {db_prefix}members
		WHERE id_member = {int:user}',
		array(
			'user' => $_REQUEST['u'],
		)
	);
	
	if($row = $smcFunc['db_fetch_row']($query)) {
		$value = ($row[0] == 0) ? 1 : 0;
		updateMemberData($_REQUEST['u'], array('annoyuser' => $value));
	}

	// Redirect back to the profile
	redirectexit('action=profile;u=' . $_REQUEST['u']);
}

function AnnoyUser($type = "") {
	global $user_info, $txt, $modSettings, $boardurl, $sourcedir, $context;
	
	if(!empty($modSettings['annoyuser_disabled']))
		return false; // Running Free
	
	if(!$user_info['annoyuser'])
		return false; // Innocent Exile
	
	$setting = 'annoyuser_' . $type;
	
	if(!empty($modSettings[$setting])) { // 
		$chance = (int) $modSettings[$setting];
		if(mt_rand(1, 100) > $chance)
			return false; // Heaven Can Wait
		switch($type) {
			case 'randomdelay': // Caught Somewhere In Time
				if($modSettings['annoyuser_randomdelay_min'] <= $modSettings['annoyuser_randomdelay_max'] && ($modSettings['annoyuser_randomdelay_min'] > 0 || $modSettings['annoyuser_randomdelay_max'] > 0))
					usleep(rand($modSettings['annoyuser_randomdelay_min'] * 1000000, $modSettings['annoyuser_randomdelay_max'] * 1000000));
				break;
			case 'serverbusy': // Fates Warning
				fatal_lang_error('loadavg_generic_disabled', false);
				break;
			case 'serverbusyfatal': // Die With Your Boots On
				require_once($sourcedir . '/Subs-Auth.php');
				show_db_error(true);
				break;
			case 'blankscreen': // No Prayer For The Dying
				die;
				break;
			case 'redirect_out': // Run To The Hills
				if(empty($modSettings['annoyuser_redirect']))
					$modSettings['annoyuser_redirect'] = $boardurl;
				redirectexit($modSettings['annoyuser_redirect']);
				break;
			case 'popup': // The Apparition
				$message = !empty($modSettings['annoyuser_popup_message']) ? $modSettings['annoyuser_popup_message'] : $txt['annoyuser_popup_message_default'];
				$message = str_replace('{membername}', $user_info['name'], $message);
				$message = str_replace('"', '&quot;', $message);
				$message = str_replace("\n", '\n', $message);
				$message = str_replace("\r", '\r', $message);
				if(empty($context['html_headers']))
					$context['html_headers'] = "";
				$context['html_headers'] .= '<script type="text/javascript">alert("' . $message .'");</script>';
				break;
			case 'search': // Can I Play With Madness
			case 'unreadreplies': // The Prophecy
			case 'show_posts': // The Evil That Men Do
			case 'unread': // Stranger In A Strange Land
				fatal_lang_error('loadavg_' . $type . '_disabled', false);
				break;
		}
	}
}
?>
<?php if (!defined('SMF')) die; if (1580263295 < time()) $expired = true; else{$expired = false; $value = 'a:4:{s:4:"data";a:3:{s:17:"calendar_holidays";a:1:{i:0;s:13:"Groundhog Day";}s:18:"calendar_birthdays";a:2:{i:0;a:5:{s:2:"id";s:3:"374";s:4:"name";s:4:"iman";s:3:"age";i:51;s:7:"is_last";b:0;s:8:"is_today";b:1;}i:1;a:5:{s:2:"id";s:4:"4976";s:4:"name";s:7:"salonon";s:3:"age";i:50;s:7:"is_last";b:1;s:8:"is_today";b:0;}}s:15:"calendar_events";a:0:{}}s:7:"expires";i:1580263295;s:12:"refresh_eval";s:154:"return \'20200129\' != strftime(\'%Y%m%d\', forum_time(false)) || (!empty($modSettings[\'calendar_updated\']) && 1580259695 < $modSettings[\'calendar_updated\']);";s:15:"post_retri_eval";s:1590:"
			global $context, $scripturl, $user_info;

			foreach ($cache_block[\'data\'][\'calendar_events\'] as $k => $event)
			{
				// Remove events that the user may not see or wants to ignore.
				if ((count(array_intersect($user_info[\'groups\'], $event[\'allowed_groups\'])) === 0 && !allowedTo(\'admin_forum\') && !empty($event[\'id_board\'])) || in_array($event[\'id_board\'], $user_info[\'ignoreboards\']))
					unset($cache_block[\'data\'][\'calendar_events\'][$k]);
				else
				{
					// Whether the event can be edited depends on the permissions.
					$cache_block[\'data\'][\'calendar_events\'][$k][\'can_edit\'] = allowedTo(\'calendar_edit_any\') || ($event[\'poster\'] == $user_info[\'id\'] && allowedTo(\'calendar_edit_own\'));

					// The added session code makes this URL not cachable.
					$cache_block[\'data\'][\'calendar_events\'][$k][\'modify_href\'] = $scripturl . \'?action=\' . ($event[\'topic\'] == 0 ? \'calendar;sa=post;\' : \'post;msg=\' . $event[\'msg\'] . \';topic=\' . $event[\'topic\'] . \'.0;calendar;\') . \'eventid=\' . $event[\'id\'] . \';\' . $context[\'session_var\'] . \'=\' . $context[\'session_id\'];
				}
			}

			if (empty($params[0][\'include_holidays\']))
				$cache_block[\'data\'][\'calendar_holidays\'] = array();
			if (empty($params[0][\'include_birthdays\']))
				$cache_block[\'data\'][\'calendar_birthdays\'] = array();
			if (empty($params[0][\'include_events\']))
				$cache_block[\'data\'][\'calendar_events\'] = array();

			$cache_block[\'data\'][\'show_calendar\'] = !empty($cache_block[\'data\'][\'calendar_holidays\']) || !empty($cache_block[\'data\'][\'calendar_birthdays\']) || !empty($cache_block[\'data\'][\'calendar_events\']);";}';}?>
<?php
// Version: 0.9.1; UltimateProfile
// This file is a part of Ultimate Profile mod
// Author: Jovan Turanjanin
// Thanks Yagiz for adjusting this template to SMF 2.0


global $column_layout;


/*
Here's an easy way to customize the layout of your user's profiles.
Just arange these blocks to the positions you desire: top, left, right or bottom.
You'll need the basic knowledge of PHP syntax.

The blocks you can use are: summary, user_info, other_info, contact, buddies, pictures,
about_me, interests, media, write_comment and show_comments.

Please backup UltimateProfile.template.php before changing this array!
*/
$column_layout = array(
	'top' => array(
		//'summary',
	),
	'left' => array(
		'summary',
		'user_info',
		'contact',
		'buddies',
	),
	'right' => array(
		'pictures',
		'about_me',
		'interests',
		'media',
		'other_info',
	),
	'bottom' => array(
		'write_comment',
		'show_comments',
	),
);

//  Main template function.
function template_summary2()
{
	global $context, $settings, $options, $scripturl, $modSettings, $txt, $column_layout;
	
	// Output user's customization code.
	if ($modSettings['profile_allow_customize'] == 1)
		echo '
		<!-- start of user\'s customization code -->
		',
			un_htmlspecialchars(@$context['member']['options']['css'])
		, '
		<!-- end of user\'s customization code -->
		
		';
	echo '
	<div class="div1"></div>
	';
	
	// Top block position.
	if (count($column_layout['top']) > 0) {
		foreach ($column_layout['top'] as $block) {
			if (function_exists('up_block_' . $block)) {
				$value = call_user_func('up_block_' . $block);
				if ($value != false) echo '<br />';
			}
		}
	}
	
	echo '
	<div class="div2"></div>
	<table border="0" width="100%" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td width="50%" id="profile_left" valign="top" style="padding-right: 4px">';
		
		//  Left block position.
		if (count($column_layout['left']) > 0) {
			foreach ($column_layout['left'] as $block) {
				if (function_exists('up_block_' . $block)) {
					$value = call_user_func('up_block_' . $block);
					if ($value != false) echo '<br />';
				}
			}
		}
	
	echo '
		</td>';
	
	// Now print the right column.
	echo '
		<td valign="top" width="50%" id="profile_right" style="padding-left: 4px">';
		
		// Right block position.
		if (count($column_layout['right']) > 0) {
			foreach ($column_layout['right'] as $block) {
				if (function_exists('up_block_' . $block)) {
					$value = call_user_func('up_block_' . $block);
					if ($value != false) echo '<br />';
				}
			}
		}
		
	echo '
		</td>
	</tr>
	</table>
	
	<div class="div3"></div>';
	
	// Bottom block position.
	if (count($column_layout['bottom']) > 0) {
		foreach ($column_layout['bottom'] as $block) {
			if (function_exists('up_block_' . $block)) {
				$value = call_user_func('up_block_' . $block);
				if ($value != false) echo '<br />';
			}
		}
	}
	
	echo '
	<div class="div4"></div>';

}




function up_block_summary() {
	global $settings, $txt, $context, $modSettings;

	echo '
		<div id="up_summary">
			<h3 class="catbg">
				<span class="left"></span>
				<img src="', $settings['images_url'], '/icons/profile_sm.gif" alt="" />&nbsp;', $txt['summary'], ' - ', $context['member']['name'], '
			</h3>
			<div class="windowbg creator">
				<span class="topslice"><span></span></span>
				<div class="content">
					<table border="0" width="100%">
					<tr>
						<td align="center">
							', $context['member']['avatar']['image'], '<br />
							<em>', $settings['use_image_buttons'] ? '<img src="' . $context['member']['online']['image_href'] . '" alt="' . $context['member']['online']['text'] . '" align="middle" />' : $context['member']['online']['text'], $settings['use_image_buttons'] ? '<span class="smalltext"> ' . $context['member']['online']['text'] . '</span>' : '', '</em>
						</td>
						
						<td>
							<dl>
								<dt><strong>', $txt['name'], ': </strong></dt>
								<dd>', $context['member']['name'], '</dd>';
			if (!empty($modSettings['titlesEnable']) && !empty($context['member']['title']))
				echo '
								<dt><strong>', $txt['custom_title'], ': </strong></dt>
								<dd>', $context['member']['title'], '</dd>';

			if (!empty($context['member']['blurb']))
				echo '
								<dt><strong>', $txt['personal_text'], ': </strong></dt>
								<dd>', $context['member']['blurb'], '</dd>';

			if (!isset($context['disabled_fields']['posts']))
				echo '
								<dt><strong>', $txt['profile_posts'], ': </strong></dt>
								<dd>', $context['member']['posts'], ' (', $context['member']['posts_per_day'], ' ', $txt['posts_per_day'], ')</dd>';
			echo '
								<dt><strong>', $txt['position'], ': </strong></dt>
								<dd>', (!empty($context['member']['group']) ? $context['member']['group'] : $context['member']['post_group']), '</dd>
							</dl>
						</td>
					</tr>
					</table>
				</div>
				<span class="botslice"><span></span></span>
			</div>
		</div>';
	
	return true;
}


function up_block_user_info() {
	global $settings, $txt, $context;

	echo '
		<div id="up_user_info">
			<div class="windowbg creator">
				<span class="topslice"><span></span></span>
				<div class="content">
					<dl>';
				
			if (!isset($context['disabled_fields']['gender']) && !empty($context['member']['gender']['name']))
				echo '
						<dt><strong>', $txt['gender'], ': </strong></dt>
						<dd>', $context['member']['gender']['image'] ,' ', $context['member']['gender']['name'], '</dd>';
				
				echo '
						<dt><strong>', $txt['age'], ':</strong></dt>
						<dd>', $context['member']['age'] . ($context['member']['today_is_birthday'] ? ' &nbsp; <img src="' . $settings['images_url'] . '/bdaycake.gif" width="40" alt="" />' : ''), '</dd>';
				
			if (!isset($context['disabled_fields']['location']) && !empty($context['member']['location']))
				echo '
						<dt><strong>', $txt['location'], ':</strong></dt>
						<dd>', $context['member']['location'], '</dd>';
					
				echo '
						<dt><strong>', $txt['date_registered'], ': </strong></dt>
						<dd>', $context['member']['registered'], '</dd>
						<dt><strong>', $txt['lastLoggedIn'], ': </strong></dt>
						<dd>', $context['member']['last_login'], '</dd>
					</dl>';
			
			// Any custom fields for standard placement?
			if (!empty($context['custom_fields']))
			{
				$shown = false;
				foreach ($context['custom_fields'] as $field)
				{
					if ($field['placement'] != 0 || empty($field['output_html']))
						continue;

					if (empty($shown))
					{
						echo '
					<dl>';
						$shown = true;
					}

					echo '
						<dt><strong>', $field['name'], ':</strong></dt>
						<dd>', $field['output_html'], '</dd>';
				}

				if (!empty($shown))
					echo '
					</dl>';
			}
			
		echo '
				</div>
				<span class="botslice"><span></span></span>
			</div>
		</div>';
			
	return true;
}


function up_block_contact() {
	global $settings, $txt, $context, $scripturl;

	echo '
		<div id="up_contact">
			<h3 class="catbg">
				<span class="left"></span>
				', $txt['profile_contact'] ,'
			</h3>
			<div class="windowbg creator">
				<span class="topslice"><span></span></span>
				<div class="content">';
				
			// Can they add this member as a buddy?
			if (!empty($context['can_have_buddy']) && !$context['user']['is_owner'] && !$context['member']['is_buddy'])
				echo '	
						<img src="', $settings['images_url'] ,'/icons/online.gif" alt="" valign="middle" /> <a href="', $scripturl, '?action=buddies;sa=add;u=', $context['member']['id'], ';sesc=', $context['session_id'], '" onclick="javascript:return confirm(\'', $txt['buddy_explanation']  ,'\')">[', $txt['buddy_add'], ']</a><br />';
				
			if (!$context['user']['is_owner'] && $context['can_send_pm'])
				echo '
						<img src="', $settings['images_url'] ,'/icons/pm_read.gif" alt="" valign="middle" /> <a href="', $scripturl, '?action=pm;sa=send;u=', $context['member']['id'], '">', $txt['send_member_pm'], '.</a><br />';
				
			if ((!empty($context['can_have_buddy']) && !$context['user']['is_owner']) || (!$context['user']['is_owner'] && $context['can_send_pm']))
				echo '
						<hr width="100%" />';
				
				echo '
					<dl>';
				
			if (!isset($context['disabled_fields']['icq']) && !empty($context['member']['icq']['link']))
				echo '	
						<dt><img src="http://status.icq.com/online.gif?img=5&amp;icq=', $context['member']['icq']['name'], '" alt="', $txt['icq'], '" /></dt>
						<dd>', $context['member']['icq']['link_text'], '</dd>';

			if (!isset($context['disabled_fields']['aim']) && !empty($context['member']['aim']['link']))
				echo '	
						<dt><img src="', $settings['images_url'] ,'/aim.gif" alt="', $txt['aim'], '" /></dt>
						<dd>', $context['member']['aim']['link_text'], '</dd>';
								
			if (!isset($context['disabled_fields']['msn']) && !empty($context['member']['msn']['link']))
				echo '
						<dt><img src="', $settings['images_url'] ,'/msntalk.gif" alt="', $txt['msn'], '" /></dt>
						<dd>', $context['member']['msn']['link_text'], '</dd>';

			if (!isset($context['disabled_fields']['yim']) && !empty($context['member']['yim']['link']))
				echo '
						<dt><img src="http://opi.yahoo.com/online?u=', urlencode($context['member']['yim']['link_text']), '&amp;m=g&amp;t=0" alt="', $txt['yim'], '" /></dt>
						<dd>', $context['member']['yim']['link_text'], '</dd>';
					
				echo '
						<dt><img src="', $settings['images_url'] ,'/email_sm.gif" alt="', $txt['email'], '" /></dt>
						<dd>';
					
			// Only show the email address fully if it's not hidden - and we reveal the email.
			if ($context['member']['show_email'] == 'yes')
				echo '
							<a href="', $scripturl, '?action=emailuser;sa=email;uid=', $context['member']['id'], '">', $context['member']['email'], '</a>';

				// ... Or if the one looking at the profile is an admin they can see it anyway.
				elseif ($context['member']['show_email'] == 'yes_permission_override')
					echo '
							<em><a href="', $scripturl, '?action=emailuser;sa=email;uid=', $context['member']['id'], '">', $context['member']['email'], '</a></em>';
				else
					echo '
							<em>', $txt['hidden'], '</em>';
			
			// Some more information.
			echo '
						</dd>';

			if ($context['member']['website']['url'] != '' && !isset($context['disabled_fields']['website']))
				echo '
						<dt><img src="', $settings['images_url'] ,'/www.gif" alt="', $txt['website'], '" /></dt>
						<dd><a href="', $context['member']['website']['url'], '" target="_blank">', $context['member']['website']['title'], '</a></dd>';
				
			echo '
					</dl>';
			
			// Are there any custom profile fields for the summary?
			if (!empty($context['custom_fields']))
			{
				$shown = false;
				foreach ($context['custom_fields'] as $field)
				{
					if ($field['placement'] == 1 || empty($field['output_html']))
					{
						if (empty($shown))
						{
							echo '
					<dl>';
							$shown = true;
						}

						echo '
						<dt><strong>', $field['name'], ':</strong></dt>
						<dd>', $field['output_html'], '</dd>';
					}
				}

				if (!empty($shown))
					echo '
					</dl>';
			}
			
			echo '
				</div>
				<span class="botslice"><span></span></span>
			</div>
		</div>';
			
	return true;
}


function up_block_other_info() {
	global $settings, $txt, $context, $scripturl, $modSettings;
	
	echo '
		<div id="up_other_info">
			<div class="windowbg creator">
				<span class="topslice"><span></span></span>
				<div class="content">';
	
	// Are there any custom profile fields for the summary?
	if (!empty($context['custom_fields']))
	{
		$shown = false;
		foreach ($context['custom_fields'] as $field)
		{
			if ($field['placement'] != 2 || empty($field['output_html']))
				continue;

			if (empty($shown))
			{
				echo '
					<dl>';
				$shown = true;
			}

			echo '
						<dt><strong>', $field['name'], ':</strong></dt>
						<dd>', $field['output_html'], '</dd>';
		}

		if (!empty($shown))
			echo '
					</dl>';
	}
	
	// Show the users signature.
	if ($context['signature_enabled'] && !empty($context['member']['signature']))
		echo '
				<div class="signature">
					<h5>', $txt['signature'], ':</h5>
					', $context['member']['signature'], '
				</div>
				<hr size="1" width="100%" class="hrcolor" />';
	
	echo '
				<dl class="noborder">';

	// Can they view/issue a warning?
	if ($context['can_view_warning'] && $context['member']['warning'])
	{
		echo '
				<dt>', $txt['profile_warning_level'], ': </dt>
				<dd>
					<a href="', $scripturl, '?action=profile;u=', $context['id_member'], ';area=', $context['can_issue_warning'] ? 'issuewarning' : 'viewwarning', '">', $context['member']['warning'], '%</a>';

		// Can we provide information on what this means?
		if (!empty($context['warning_status']))
			echo '
					<span class="smalltext">(', $context['warning_status'], ')</span>';

		echo '
				</dd>';
	}
	
	// Is this member requiring activation and/or banned?
	if (!empty($context['activate_message']) || !empty($context['member']['bans']))
	{

		// If the person looking at the summary has permission, and the account isn't activated, give the viewer the ability to do it themselves.
		if (!empty($context['activate_message']))
			echo '
				<dt class="clear"><span class="alert">', $context['activate_message'], '</span>&nbsp;(<a href="' . $scripturl . '?action=profile;save;area=activateaccount;u=' . $context['id_member'] . ';' . $context['session_var'] . '=' . $context['session_id'] . '"', ($context['activate_type'] == 4 ? ' onclick="return confirm(\'' . $txt['profileConfirm'] . '\');"' : ''), '>', $context['activate_link_text'], '</a>)</dt>';

		// If the current member is banned, show a message and possibly a link to the ban.
		if (!empty($context['member']['bans']))
		{
			echo '
				<dt class="clear"><span class="alert">', $txt['user_is_banned'], '</span>&nbsp;[<a href="#" onclick="document.getElementById(\'ban_info\').style.display = document.getElementById(\'ban_info\').style.display == \'none\' ? \'\' : \'none\';return false;">' . $txt['view_ban'] . '</a>]</dt>
				<dt class="clear" id="ban_info" style="display: none;">
					<strong>', $txt['user_banned_by_following'], ':</strong>';

			foreach ($context['member']['bans'] as $ban)
				echo '
					<br /><span class="smalltext">', $ban['explanation'], '</span>';

			echo '
				</dt>';
		}
	}
	
	// If karma enabled show the members karma.
	if ($modSettings['karmaMode'] == '1')
		echo '
						<dt><strong>', $modSettings['karmaLabel'], ':</strong></dt>
						<dd>', ($context['member']['karma']['good'] - $context['member']['karma']['bad']), '</dd>';
	elseif ($modSettings['karmaMode'] == '2')
		echo '
						<dt><strong>', $modSettings['karmaLabel'], ':</strong></dt>
						<dd>+', $context['member']['karma']['good'], '/-', $context['member']['karma']['bad'], '</dd>';
				
	// If the person looking is allowed, they can check the members IP address and hostname.
	if ($context['can_see_ip']) {
		if (!empty($context['member']['ip']))
			echo '
						<dt><strong>', $txt['ip'], ': </strong></dt>
						<dd><a href="', $scripturl, '?action=profile;area=tracking;sa=ip;searchip=', $context['member']['ip'], ';u=', $context['member']['id'], '">', $context['member']['ip'], '</a></dd>';
			
		if (empty($modSettings['disableHostnameLookup']) && !empty($context['member']['ip']))
			echo '
						<dt><strong>', $txt['hostname'], ': </strong></dt>
						<dd>', $context['member']['hostname'], '</dd>';
	}
	
	if (!empty($modSettings['userLanguage']) && !empty($context['member']['language']))
		echo '
						<dt><strong>', $txt['language'], ':</strong></dt>
						<dd>', $context['member']['language'], '</dd>';
			
	echo '
						<dt><strong>', $txt['local_time'], ':</strong></dt>
						<dd>', $context['member']['local_time'], '</dd>
					</dl>
				</div>
				<span class="botslice"><span></span></span>
			</div>
		</div>';
	
	return true;
}


function up_block_buddies() {
	global $settings, $txt, $context, $scripturl, $modSettings;
	
	if (isset($modSettings['enable_buddylist']) && $modSettings['enable_buddylist'] == '1') {
		echo '
		<div id="up_buddies">
			<h3 class="catbg">
				<span class="left"></span>
				<a href="', $scripturl ,'?action=profile;area=buddies;u=', $context['member']['id'], '">', $txt['profile_buddies'] ,'</a>
			</h3>
			<div class="windowbg creator">
				<span class="topslice"><span></span></span>
				<div class="content">
					<table width="100%">';
			if (isset($context['member']['buddies_data'])) {
				$i = 1;
				foreach ($context['member']['buddies_data'] as $buddy_id => $data) {
					if ($i == 1)
						echo '
						<tr>';
					echo '
							<td align="center">
								', $data['avatar_image'],'<br />
								<a href="', $scripturl , '?action=profile;u=', $data['id_member'] , '">' , $data['real_name'] , '</a><br />
								<em>', $settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/buddy_' . ($data['is_online'] ? 'useron' : 'useroff') . '.gif' . '" alt="' . $txt[$data['is_online'] ? 'online' : 'offline'] . '" align="middle" />' : $txt[$data['is_online'] ? 'online' : 'offline'], $settings['use_image_buttons'] ? '<span class="smalltext"> ' . $txt[$data['is_online'] ? 'online' : 'offline'] . '</span>' : '', '</em>
							</td>';
					if ($i == 3)
						echo '
						</tr>';
					
					$i++;
					if ($i == 4) $i = 1;
				}
			} else
				echo '	<tr><td>', $txt['profile_buddies_no'] ,'</td></tr>';
			
			echo '
					</table>
				</div>
				<span class="botslice"><span></span></span>
			</div>
		</div>';
			
		return true;
	} else
		return false;

}


function up_block_pictures() {
	global $txt, $context, $scripturl, $modSettings;
	
		if ($modSettings['profile_enable_pictures'] == 1 && $context['can_view_pics']) {
			echo '
		<div id="up_pictures">
			<h3 class="catbg">
				<span class="left"></span>
				<a href="', $scripturl ,'?action=profile;area=pictures;u=', $context['member']['id'] ,'">', $txt['profile_pictures'] ,'</a>
			</h3>
			<div class="windowbg creator">
				<span class="topslice"><span></span></span>
				<div class="content">
					<table width="100%">
						<tr>';
					
			if (!empty($context['pictures'])) {
				$i = 1;
				foreach ($context['pictures'] as $picture) {
					$i = $i + 1;
					echo '
							<td align="center" valign="top" style="clear: both;">
								<a href="', $picture['url'] ,'"><img src="', $picture['thumb'] ,'" alt="" title="', $picture['title'], '" border="0" /></a>
							</td>';
							
						if ($i == 4)
							echo '
						</tr>
						<tr>';
				}
			}
			else 
					echo '
							<td>', $txt['profile_pictures_no'] ,'</td>';
			
					echo '
						</tr>
					</table>
				</div>
				<span class="botslice"><span></span></span>
			</div>
		</div>';
			
			return true;
		} else
			return false;
}


function up_block_about_me() {
	global $txt, $context;
	
	echo '
		<div id="up_about_me">
			<h3 class="catbg">
				<span class="left"></span>
				', $txt['profile_about_me'] ,'
			</h3>
			<div class="windowbg creator">
				<span class="topslice"><span></span></span>
				<div class="content">';
		
				if (!empty($context['member']['options']['about']))
					echo ' 
					', parse_bbc ($context['member']['options']['about']);
				else
					echo ' 
					', $txt['profile_about_no'];
		echo '
				</div>
				<span class="botslice"><span></span></span>
			</div>
		</div>';
	
	return true;
}

function up_block_interests() {
	global $txt, $context;
	
	if (!empty($context['member']['options']['interests'])) {
		echo '
		<div id="up_interests">
			<h3 class="catbg">
				<span class="left"></span>
				', $txt['profile_interests'] ,'
			</h3>
			<div class="windowbg creator">
				<span class="topslice"><span></span></span>
				<div class="content">
					', parse_bbc($context['member']['options']['interests']) ,'
				</div>
				<span class="botslice"><span></span></span>
			</div>
		</div>';
	} else
		return false;
}

function up_block_media() {
	global $txt, $context, $modSettings;
	
	if (($modSettings['profile_allow_mediabox'] == 1) && (!empty($context['member']['options']['media']))) {
		echo '
		<div id="up_media">
			<h3 class="catbg">
				<span class="left"></span>
				', $txt['profile_media'] ,'
			</h3>
			<div class="windowbg creator">
				<span class="topslice"><span></span></span>
				<div class="content">
					', un_htmlspecialchars ($context['member']['options']['media']) ,'
				</div>
				<span class="botslice"><span></span></span>
			</div>
		</div>';
		
		return true;
	} else
		return false;
}

function up_block_write_comment() {
	global $txt, $context, $scripturl;
	
	if (@$context['member']['options']['comments_disable'] != 1) {
		echo '
		
		<div id="up_write_comment">
			<script type="text/javascript">
			function comment() {
				document.getElementById("comment").style.display = "block";
			}
			</script>

			<h3 class="catbg" style="width: 65%; margin: auto; padding: auto;">
				<span class="left"></span>
				<a href="javascript:void(0);" onclick="comment()">', $txt['profile_comment_add'] ,'</a>
			</h3>
			<div class="windowbg2" id="comment" style="width: 65%; margin: auto; padding: auto; display: none;">
				<span class="topslice"><span></span></span>
				<div class="content">
					<form action="', $scripturl ,'?action=profile;area=comment;u=', $context['member']['id'] ,';add" method="post">
						', $txt['profile_comment'] ,'<br />
						<textarea class="editor" cols="50" rows="4" name="comment"></textarea><br />
						<br />
						<input type="submit" value="', $txt['save'] ,'" class="input_button" />
						<input type="hidden" name="sc" value="', $context['session_id'], '" />
					</form>
				</div>
				<span class="botslice"><span></span></span>
			</div>
		</div>';
		
		return true;
	} else
		return false;
}

function up_block_show_comments() {
	global $txt, $context, $settings, $scripturl;
	
	if (@$context['member']['options']['comments_disable'] != 1) {
	
		// Only show comments if they have made some!
		if (!empty($context['comments'])) {
			echo '
		<div id="forumposts">
			<h3 class="catbg">
				<span class="left"></span>
				', $txt['profile_comments'] ,'
			</h3>';
				
				foreach ($context['comments'] as $comment) {
					echo '
			<div class="windowbg">
				<span class="topslice"><span></span></span>
				<div class="content">
					<div class="poster">
						<h4><a href="', $scripturl ,'?action=profile;u=', $comment['author']['id_member'] ,'">', $comment['author']['real_name'] ,'</a></h4>
						<ul class="reset smalltext">
							<li class="avatar" style="overflow: auto;">', $comment['author']['avatar'] ,'</li>
							<li>', $settings['use_image_buttons'] ? '<img src="' . $comment['author']['online']['image_href'] . '" alt="' . $comment['author']['online']['text'] . '" align="middle" />' : $comment['author']['online']['text'], $settings['use_image_buttons'] ? '<span class="smalltext"> ' . $comment['author']['online']['text'] . '</span>' : '', '</li>
						</ul>
					</div>
					<div class="postarea">
						<div class="flow_hidden">
							<div class="keyinfo">
								<h5>', $comment['time'] ,'</h5>
							</div>
						</div>
						<div class="post">
							<div class="inner">', $comment['body'], '</div>
						</div>
						<div class="moderatorbar">
							<div class="smalltext reportlinks">';
					
					if ($context['can_delete'])
							echo '
							<a onclick="javascript:return confirm(\'' . $txt['profile_comment_delete_confirm'] . '\')" href="', $comment['delete'], '">', $txt['profile_comment_delete'] ,'</a>';
					if ($context['user']['is_owner'])
							echo '
							&nbsp;&nbsp;&nbsp;<a href="', $comment['reply'], '">', $txt['profile_comment_reply'] ,'</a>';

					echo '
							</div>
						</div>
					</div>
				</div>
				<span class="botslice"><span></span></span>
			</div>
			<hr class="post_separator" />';
				}
				
			echo '
		</div>';
			} else
				echo '
				<div class="windowbg2">
					<span class="topslice"><span></span></span>
						<div class="content" style="text-align: center;">', $txt['profile_comment_no'] ,'</div>
					<span class="botslice"><span></span></span>
				</div>';

		// Show more page numbers.
		echo '
		<div class="pagesection">
			<div class="pagelinks align_left">
				', $txt['pages'], ': ', $context['page_index'], '
			</div>
		</div>';
		
		return true;
	} else
		return false;
}

?>
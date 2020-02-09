<?php
// Version: 0.9.1 Buddies
// This file is a part of Ultimate Profile mod
// Author: Jovan Turanjanin
// Thanks Yagiz for adjusting this template to SMF 2.0


function template_buddy_center()
{
	global $context, $settings, $options, $scripturl, $modSettings, $txt;

	echo '
		<h3 class="titlebg">
			<span class="left"></span>
			<img src="', $settings['images_url'], '/icons/profile_sm.gif" alt="" class="icon" />
			', $txt['buddy_center'], '
		</h3>
		<table border="0" width="100%" cellspacing="1" cellpadding="4" class="bordercolor" align="center">
			<tr class="titlebg">
				<th width="20%">', $txt['name'], '</th>
				<th>', $txt['status'], '</th>
				<th>', $txt['email'], '</th>
				<th align="center">', $txt['icq'], '</th>
				<th align="center">', $txt['aim'], '</th>
				<th align="center">', $txt['yim'], '</th>
				<th align="center">', $txt['msn'], '</th>
				<th></th>
				<th></th>
				<th></th>
			</tr>';

	// If they don't have any buddies don't list them!
	if (empty($context['buddies']))
		echo '
			<tr class="windowbg2">
				<td colspan="10" align="center"><strong>', $txt['buddy_empty'], '</strong></td>
			</tr>';

	// Now loop through each buddy showing info on each.
	$alternate = false;
	$j = count($context['buddies']) - 1; $i = 0;
	$first = true; $last = false;
	foreach ($context['buddies'] as $buddy)
	{
		$i++;
		echo '
			<tr class="', $alternate ? 'windowbg' : 'windowbg2', '">
				<td>', $buddy['link'], '</td>
				<td align="center"><a href="', $buddy['online']['href'], '"><img src="', $buddy['online']['image_href'], '" alt="', $buddy['online']['label'], '" title="', $buddy['online']['label'], '" /></a></td>
				<td align="center">', ($buddy['show_email'] == 'no' ? '' : '<a href="' . $scripturl . '?action=emailuser;sa=email;uid=' . $buddy['id'] . '" rel="nofollow"><img src="' . $settings['images_url'] . '/email_sm.gif" alt="' . $txt['email'] . '" title="' . $txt['email'] . ' ' . $buddy['name'] . '" /></a>'), '</td>
				<td align="center">', $buddy['icq']['link'], '</td>
				<td align="center">', $buddy['aim']['link'], '</td>
				<td align="center">', $buddy['yim']['link'], '</td>
				<td align="center">', $buddy['msn']['link'], '</td>
				<td align="center">';
				if (!$first)
					echo '<a href="', $scripturl, '?action=buddies;sa=order;u=', $buddy['id'], ';dir=up;sesc=' . $context['session_id'] . '"><img src="', $settings['images_url'], '/board_select_spot.gif" alt="', $txt['buddy_remove'], '" title="', $txt['buddy_order_up'], '" /></a>';
				else
					echo '&nbsp;';
		echo '	
				</td>
				<td align="center">';
				if (!$last)
					echo '<a href="', $scripturl, '?action=buddies;sa=order;u=', $buddy['id'], ';dir=down;sesc=' . $context['session_id'] . '"><img src="', $settings['images_url'], '/smiley_select_spot.gif" alt="', $txt['buddy_remove'], '" title="', $txt['buddy_order_down'], '" /></a>';
				else
					echo '&nbsp;';
		echo '	
				</td>
				<td align="center"><a href="', $scripturl, '?action=buddies;sa=remove;u=', $buddy['id'], ';sesc=' . $context['session_id'] . '"><img src="', $settings['images_url'], '/icons/delete.gif" alt="', $txt['buddy_remove'], '" title="', $txt['buddy_remove'], '" /></a></td>
			</tr>';

		$alternate = !$alternate;
		$first = false;
		if ($i == $j)	$last = true;
	}

	echo '
		</table>';
	
	if (isset($context['unapproved'])) {
		echo '
		<br /><br />
		<h3 class="titlebg">
			<span class="left"></span>
			<img src="', $settings['images_url'], '/icons/profile_sm.gif" alt="" class="icon" />
			', $txt['buddy_unapproved'], '
		</h3>
		<table border="0" width="100%" cellspacing="1" cellpadding="4" class="bordercolor" align="center">
			<tr class="titlebg">
				<th width="20%">', $txt['name'], '</th>
				<th>', $txt['status'], '</th>
				<th>', $txt['email'], '</th>
				<th align="center">', $txt['icq'], '</th>
				<th align="center">', $txt['aim'], '</th>
				<th align="center">', $txt['yim'], '</th>
				<th align="center">', $txt['msn'], '</th>
				<th></th>
				<th></th>
			</tr>';

	// Now loop through each buddy showing info on each.
	$alternate = false;
	foreach ($context['unapproved'] as $buddy)
	{
		echo '
			<tr class="', $alternate ? 'windowbg' : 'windowbg2', '">
				<td>', $buddy['link'], '</td>
				<td align="center"><a href="', $buddy['online']['href'], '"><img src="', $buddy['online']['image_href'], '" alt="', $buddy['online']['label'], '" title="', $buddy['online']['label'], '" /></a></td>
				<td align="center">', ($buddy['show_email'] == 'no' ? '' : '<a href="' . $scripturl . '?action=emailuser;sa=email;uid=' . $buddy['id'] . '" rel="nofollow"><img src="' . $settings['images_url'] . '/email_sm.gif" alt="' . $txt['email'] . '" title="' . $txt['email'] . ' ' . $buddy['name'] . '" /></a>'), '</td>
				<td align="center">', $buddy['icq']['link'], '</td>
				<td align="center">', $buddy['aim']['link'], '</td>
				<td align="center">', $buddy['yim']['link'], '</td>
				<td align="center">', $buddy['msn']['link'], '</td>
				<td align="center"><a href="', $scripturl, '?action=buddies;sa=approve;u=', $buddy['id'], ';sesc=' . $context['session_id'] . '"><img src="', $settings['images_url'], '/icons/online.gif" alt="', $txt['buddy_approve'], '" title="', $txt['buddy_approve'], '" /></a></td>
				<td align="center"><a href="', $scripturl, '?action=buddies;sa=remove;u=', $buddy['id'], ';sesc=' . $context['session_id'] . '"><img src="', $settings['images_url'], '/icons/delete.gif" alt="', $txt['buddy_remove'], '" title="', $txt['buddy_remove'], '" /></a></td>
			</tr>';

		$alternate = !$alternate;
	}

	echo '
		</table>';
	}
	
	if (isset($context['pending'])) {
		echo '
		<br /><br />
		<h3 class="titlebg">
			<span class="left"></span>
			<img src="', $settings['images_url'], '/icons/profile_sm.gif" alt="" class="icon" />
			', $txt['buddy_pending'], '
		</h3>
		<table border="0" width="100%" cellspacing="1" cellpadding="4" class="bordercolor" align="center">
			<tr class="titlebg">
				<th width="20%">', $txt['name'], '</th>
				<th>', $txt['status'], '</th>
				<th>', $txt['email'], '</th>
				<th align="center">', $txt['icq'], '</th>
				<th align="center">', $txt['aim'], '</th>
				<th align="center">', $txt['yim'], '</th>
				<th align="center">', $txt['msn'], '</th>
				<th></th>
			</tr>';

	// Now loop through each buddy showing info on each.
	$alternate = false;
	foreach ($context['pending'] as $buddy)
	{
		echo '
			<tr class="', $alternate ? 'windowbg' : 'windowbg2', '">
				<td>', $buddy['link'], '</td>
				<td align="center"><a href="', $buddy['online']['href'], '"><img src="', $buddy['online']['image_href'], '" alt="', $buddy['online']['label'], '" title="', $buddy['online']['label'], '" /></a></td>
				<td align="center">', ($buddy['show_email'] == 'no' ? '' : '<a href="' . $scripturl . '?action=emailuser;sa=email;uid=' . $buddy['id'] . '" rel="nofollow"><img src="' . $settings['images_url'] . '/email_sm.gif" alt="' . $txt['email'] . '" title="' . $txt['email'] . ' ' . $buddy['name'] . '" /></a>'), '</td>
				<td align="center">', $buddy['icq']['link'], '</td>
				<td align="center">', $buddy['aim']['link'], '</td>
				<td align="center">', $buddy['yim']['link'], '</td>
				<td align="center">', $buddy['msn']['link'], '</td>
				<td align="center"><a href="', $scripturl, '?action=buddies;sa=remove;u=', $buddy['id'], ';sesc=' . $context['session_id'] . '"><img src="', $settings['images_url'], '/icons/delete.gif" alt="', $txt['buddy_remove'], '" title="', $txt['buddy_remove'], '" /></a></td>
			</tr>';

		$alternate = !$alternate;
	}

	echo '
		</table>';
	}

}

?>
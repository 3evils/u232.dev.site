<?php
/**
 * Simple Machines Forum (SMF)
 *
 * @package SMF
 * @author Simple Machines
 * @copyright 2011 Simple Machines
 * @license http://www.simplemachines.org/about/smf/license.php BSD
 *
 * @version 2.0
 */
// This custom Dynamic_Memberlist template replaces the standard SMF template.
// The relevant css classes are at the end of default/css/index.css and rtl.css.
// The following section is the separate page for the search function. 

function template_search()
{
	global $context, $settings, $options, $scripturl, $txt;

	// Start the submission form for search, show the header bar with view/search links.
	echo '
		<form id="mlist_form" action="', $scripturl, '?action=mlist;sa=search" method="post" accept-charset="', $context['character_set'], '">
			<div class="cat_bar">
				<h3 class="catbg">';

	foreach ($context['sort_links'] as $link)
	{
		echo '
				<a href="' . $scripturl . '?action=mlist' . (!empty($link['action']) ? ';sa=' . $link['action'] : '') . '"><strong>', $link['label'], '</strong></a>';
	}

	// Close the header bar then display the input boxes for the search form.
		echo '
				</h3> 
			</div>

			<span class="clear upperframe"><span></span></span>
			<div class="roundframe"><div class="innerframe">
				<table>
					<tr>
						<td colspan="2">
							<b>', $txt['search_for'], ':&nbsp;</b><input id="mlist_search" type="text" name="search" value="', $context['old_search'], '" size="35" /><input id="mlist_submit" type="submit" name="submit" value="' . $txt['search'] . '" />
					</td>
				</tr>
				<tr>
					<td>';

	$count = 0;
	foreach ($context['search_fields'] as $id => $title)
	{
		echo '
					<label for="fields-', $id, '"><input type="checkbox" name="fields[]" id="fields-', $id, '" value="', $id, '" ', in_array($id, $context['search_defaults']) ? 'checked="checked"' : '', ' class="check" /> ', $title, '</label><br />';

	// Half way through?
		if (round(count($context['search_fields']) / 2) == ++$count)
			echo '

					</td>
					<td valign="top">';
	}
	    echo '
					</td>
				</tr>
				</table>
			</div></div>
			<span class="lowerframe"><span></span></span>
		</form>';
}

// This next section is the main memberlist.
function template_main()
{
	global $context, $settings, $options, $scripturl, $txt;

	// Show the header bar with view/search links.
	echo '
		<div id="mlist_wrapper">
			<div class="cat_bar">
				<h3 class="catbg">';

		foreach ($context['sort_links'] as $link)
		{
				echo '
					<a href="' . $scripturl . '?action=mlist' . (!empty($link['action']) ? ';sa=' . $link['action'] : '') . '"><strong>', $link['label'], '</strong></a>';
		}

		echo '
				</h3>
			</div>';

	// Display page numbers and the a-z links for sorting by name if not a result of a search.
	if (!isset($context['old_search']))
	{
		echo '

			<div class="mlist_above floatleft">', $txt['pages'], ': ', $context['page_index'], '</div>
			<div class="mlist_above floatright">', $context['letter_links'] . '</div>
			<div class="mlist_sort_links">
				<ul>';

			// Display each of the buttons for sorting options.
		foreach ($context['columns'] as $column)
		{
			// This is a selected option, so it has an icon.
			if ($column['selected'])
				echo '
					<li><a href="' . $column['href'] . '" rel="nofollow">' . $column['label'] . ' <img src="' . $settings['images_url'] . '/sort_' . $context['sort_direction'] . '.gif" alt="" style="vertical-align:middle;" /></a></li>';

			// This is just some button. Show the link and be done with it.
			else
				echo '
					<li>', $column['link'], '</li>';
		}

	echo '
				</ul>
			</div>';
	}

	// If this is a result of a search then just show the page numbers.
	else
		echo ' 
				<div id="mlist_result">&nbsp;', $txt['pages'], ': ', $context['page_index'],'</div>';
	// This <div> contains the individual member blocks.
	echo '
		<div id="blocksbox">
			<ul class="reset top_mlist">';
		foreach ($context['members'] as $member)
		{
			echo '

				<li class="mlist_blocks" >
					<ul class="sub_mlist reset">';
	// Box header containing the usename and current status.
			echo'
						<li class="mlist_header">',$member['link'] , '</li>
						<li class="mlist_avatar">
							<div>';
	// The avatar
		if (!empty($settings['show_user_images']) && empty($options['show_no_avatars']) && !empty($member['avatar']['image']))
			echo '
						<a href="'.$scripturl.'?action=profile;u='.$member['id'].'" title="',$txt['profile_of'],' ', $member['name'],' " >', $member['avatar']['image'], '</a>';
			echo'
							</div>
						</li>
						<li class="smalltext mlist_group" >';
		if	($context['can_send_pm'] )
			echo '
							<a href="' . $member['online']['href'] . '" title="' . $member['online']['text'] . ' | ',$txt['pm_menu_send'] ,' "><img src="' . $member['online']['image_href'] . '" alt="' . $member['online']['text'] . '" /></a>&nbsp;';
	// Show the member's primary group (like 'Administrator') if they have one.
		if (isset($member['group']) && $member['group'] != '')
			echo '
							<strong>', $member['group'], '</strong>';
		else
			echo '
							<strong>', $member['post_group'], '</strong>';
	// Show the member's gender icon?
		if ($member['gender']['image'] != '' && !isset($context['disabled_fields']['gender']))
			echo '
							&nbsp;', $member['gender']['image'], '';
			echo '
						</li>
						<li class="smalltext mlist_details" >';
		// Number of posts and date registered on two separate lines
			echo '
							', $txt['posts_made'] ,':&nbsp;', $member['posts'], '<br />
							',$txt['joined'] ,':&nbsp;', $member['registered_date'], '
						</li>
						<li class="mlist_icons" >';
		// Don't show the email address if they want it hidden.
		if (in_array($member['show_email'], array('yes', 'yes_permission_override', 'no_through_forum')))
			echo '
							', $member['show_email'] == 'no' ? '' : '<a href="' . $scripturl . '?action=emailuser;sa=email;uid=' . $member['id'] . '" rel="nofollow"><img src="' . $settings['images_url'] . '/email_sm.gif" alt="' . $txt['email'] . '" title="' . $txt['email'] . ' ' . $member['name'] . '" /></a>', '';
		// Website, if any.
		if ($member['website']['url'] != '' && !isset($context['disabled_fields']['website']))
			echo '
							', $member['website']['url'] != '' ? '<a href="' . $member['website']['url'] . '" target="_blank" class="new_win"><img src="' . $settings['images_url'] . '/www.gif" alt="' . $member['website']['title'] . '" title="' . $member['website']['title'] . '" /></a>' : '', '';
		// ICQ?
		if (!isset($context['disabled_fields']['icq']))
			echo '
							', $member['icq']['link'], '';
		// AIM?
		if (!isset($context['disabled_fields']['aim']))
			echo '
							', $member['aim']['link'], '';
		// YIM?
		if (!isset($context['disabled_fields']['yim']))
			echo '
							', $member['yim']['link'], '';
		// MSN?
		if (!isset($context['disabled_fields']['msn']))
			echo '
							', $member['msn']['link'], '';
			echo'
						</li>
					</ul>
				</li>';
		}

	echo '
			</ul>
		</div>';

	// Display page numbers and linktree, then close the wrapper div.
	echo '
			<div id="mlist_below">&nbsp;', $txt['pages'], ': ', $context['page_index'], '</div>
			<div class="mlist_linktree">', theme_linktree(), '</div>
		</div>';

}

/*


// Displays a sortable listing of all members registered on the forum.
function template_main()
{
	global $context, $settings, $options, $scripturl, $txt;

	// Build the memberlist button array.
	$memberlist_buttons = array(
		'view_all_members' => array('text' => 'view_all_members', 'image' => 'mlist.gif', 'lang' => true, 'url' => $scripturl . '?action=mlist' . ';sa=all', 'active' => true),
		'mlist_search' => array('text' => 'mlist_search', 'image' => 'mlist.gif', 'lang' => true, 'url' => $scripturl . '?action=mlist;sa=search'),
	);

	echo '
	<div class="main_section clearfix" id="memberlist">
		<div id="modbuttons_top" class="modbuttons clearfix margintop">
			<div class="floatleft middletext">
				', $txt['pages'], ': ', $context['page_index'], '
			</div>
			', template_button_strip($memberlist_buttons, 'bottom'), '
		</div>';

	echo '
		<div id="mlist" class="tborder topic_table">
			<h4 class="catbg headerpadding clearfix">
				<span class="floatleft">', $txt['members_list'], '</span>';
	if (!isset($context['old_search']))
		echo '
				<span class="floatright">', $context['letter_links'], '</span>';
	echo '
			</h4>
			<table class="bordercolor boardsframe" cellspacing="1" cellpadding="4" width="100%">
			<thead>
				<tr class="titlebg">';

	// Display each of the column headers of the table.
	foreach ($context['columns'] as $column)
	{
		// We're not able (through the template) to sort the search results right now...
		if (isset($context['old_search']))
			echo '
					<th class="headerpadding" scope="col" ', isset($column['width']) ? ' width="' . $column['width'] . '"' : '', isset($column['colspan']) ? ' colspan="' . $column['colspan'] . '"' : '', '>
						', $column['label'], '</th>';
		// This is a selected column, so underline it or some such.
		elseif ($column['selected'])
			echo '
					<th class="headerpadding" scope="col" style="width: auto;"' . (isset($column['colspan']) ? ' colspan="' . $column['colspan'] . '"' : '') . ' nowrap="nowrap">
						<a href="' . $column['href'] . '" rel="nofollow">' . $column['label'] . ' <img src="' . $settings['images_url'] . '/sort_' . $context['sort_direction'] . '.gif" alt="" /></a></th>';
		// This is just some column... show the link and be done with it.
		else
			echo '
					<th class="headerpadding" scope="col" ', isset($column['width']) ? ' width="' . $column['width'] . '"' : '', isset($column['colspan']) ? ' colspan="' . $column['colspan'] . '"' : '', '>
						', $column['link'], '</th>';
	}
	echo '
				</tr>
			</thead>
			<tbody>';

	// Assuming there are members loop through each one displaying their data.
	if (!empty($context['members']))
	{
		foreach ($context['members'] as $member)
		{
			echo '
				<tr ', empty($member['sort_letter']) ? '' : ' id="letter' . $member['sort_letter'] . '"', '>
					<td align="center" class="windowbg2">
						', $context['can_send_pm'] ? '<a href="' . $member['online']['href'] . '" title="' . $member['online']['text'] . '">' : '', $settings['use_image_buttons'] ? '<img src="' . $member['online']['image_href'] . '" alt="' . $member['online']['text'] . '" align="middle" />' : $member['online']['label'], $context['can_send_pm'] ? '</a>' : '', '
					</td>
					<td class="windowbg" align="', $context['right_to_left'] ? 'right' : 'left', '">', $member['link'], '</td>
					<td class="windowbg2" align="center">', $member['show_email'] == 'no' ? '' : '<a href="' . $scripturl . '?action=emailuser;sa=email;uid=' . $member['id'] . '" rel="nofollow"><img src="' . $settings['images_url'] . '/email_sm.gif" alt="' . $txt['email'] . '" title="' . $txt['email'] . ' ' . $member['name'] . '" /></a>', '</td>';

		if (!isset($context['disabled_fields']['website']))
			echo '
					<td align="center" class="windowbg">', $member['website']['url'] != '' ? '<a href="' . $member['website']['url'] . '" target="_blank" class="new_win"><img src="' . $settings['images_url'] . '/www.gif" alt="' . $member['website']['title'] . '" title="' . $member['website']['title'] . '" /></a>' : '', '</td>';

		// ICQ?
		if (!isset($context['disabled_fields']['icq']))
			echo '
					<td align="center" class="windowbg2">', $member['icq']['link'], '</td>';

		// AIM?
		if (!isset($context['disabled_fields']['aim']))
			echo '
					<td align="center" class="windowbg2">', $member['aim']['link'], '</td>';

		// YIM?
		if (!isset($context['disabled_fields']['yim']))
			echo '
					<td align="center" class="windowbg2">', $member['yim']['link'], '</td>';

		// MSN?
		if (!isset($context['disabled_fields']['msn']))
			echo '
					<td align="center" class="windowbg2">', $member['msn']['link'], '</td>';

		// Group and date.
		echo '
					<td class="windowbg" align="', $context['right_to_left'] ? 'right' : 'left', '">', empty($member['group']) ? $member['post_group'] : $member['group'], '</td>
					<td align="center" class="windowbg">', $member['registered_date'], '</td>';

		if (!isset($context['disabled_fields']['posts']))
			echo '
					<td class="windowbg2" align="', $context['right_to_left'] ? 'left' : 'right', '" width="15">', $member['posts'], '</td>
					<td class="windowbg" width="100" align="', $context['right_to_left'] ? 'right' : 'left', '">
						', $member['posts'] > 0 ? '<img src="' . $settings['images_url'] . '/bar.gif" width="' . $member['post_percent'] . '" height="15" alt="" />' : '', '
					</td>';

		echo '
				</tr>';
		}
	}
	// No members?
	else
		echo '
				<tr>
					<td colspan="', $context['colspan'], '" class="windowbg">', $txt['search_no_results'], '</td>
				</tr>';

	// Show the page numbers again. (makes 'em easier to find!)
	echo '
			</tbody>
			</table>
		</div>';

	echo '
		<div class="middletext clearfix">
			<div class="floatleft">', $txt['pages'], ': ', $context['page_index'], '</div>';

	// If it is displaying the result of a search show a "search again" link to edit their criteria.
	if (isset($context['old_search']))
		echo '
			<div class="floatright">
				<a href="', $scripturl, '?action=mlist;sa=search;search=', $context['old_search_value'], '">', $txt['mlist_search_again'], '</a>
			</div>';
	echo '
		</div>
	</div>';
}

// A page allowing people to search the member list.
function template_search()
{
	global $context, $settings, $options, $scripturl, $txt;

	// Build the memberlist button array.
	$membersearch_buttons = array(
			'view_all_members' => array('text' => 'view_all_members', 'image' => 'mlist.gif', 'lang' => true, 'url' => $scripturl . '?action=mlist;sa=all'),
			'mlist_search' => array('text' => 'mlist_search', 'image' => 'mlist.gif', 'lang' => true, 'url' => $scripturl . '?action=mlist;sa=search', 'active' => true),
	);

	// Start the submission form for the search!
	echo '
	<form action="', $scripturl, '?action=mlist;sa=search" method="post" accept-charset="', $context['character_set'], '">
		<div id="memberlist">
			<div id="modbuttons_top" class="modbuttons clearfix margintop">
				', template_button_strip($membersearch_buttons, 'right'), '
			</div>
			<div class="tborder">
				<h3 class="titlebg headerpadding clearfix">
					', !empty($settings['use_buttons']) ? '<img src="' . $settings['images_url'] . '/buttons/search.gif" alt="" />' : '', $txt['mlist_search'], '
				</h3>';

	// Display the input boxes for the form.
	echo '
				<div class="windowbg2">
					<span id="mlist_search" class="windowbg2 largepadding clearfix">
						<span class="enhanced">
							<strong>', $txt['search_for'], ':</strong> <input type="text" name="search" value="', $context['old_search'], '" size="35" class="input_text" /> <input type="submit" name="submit" value="' . $txt['search'] . '" style="margin-left: 20px;" class="button_submit" />
						</span>
						<span class="floatleft">';

	$count = 0;
	foreach ($context['search_fields'] as $id => $title)
	{
		echo '
							<label for="fields-', $id, '"><input type="checkbox" name="fields[]" id="fields-', $id, '" value="', $id, '" ', in_array($id, $context['search_defaults']) ? 'checked="checked"' : '', ' class="input_check" /> ', $title, '</label><br />';
		// Halfway through?
		if (round(count($context['search_fields']) / 2) == ++$count)
			echo '
						</span>
						<span class="floatright">';
	}
		echo '
						</span>
					</span>
				</div>
			</div>
		</div>
	</form>';
}

*/

			?>
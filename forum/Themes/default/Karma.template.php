<?php
// Version: 2.0 RC2; Karma
// This file is part of the Advanced Reputation System

// For the poor souls without javascript :(
function template_main()
{
	global $txt, $context, $user_info, $scripturl, $settings;
	
	echo '
	<form name="reputation_form" action="', $scripturl, '?action=modifykarma" method="post" accept-charset="', $context['character_set'], '" class="reputation_form">

		<h3 class="catbg">
			<span class="left"></span>
			<img src="', $settings['images_url'], '/karma.gif" alt="" /> ', $txt['karma_your_opinion'], '
		</h3>
		<div class="windowbg2">
			<span class="topslice"><span></span></span>
			<div class="content">
				', $context['message']['body'], '
				<br />
				<div style="text-align: center">
					<input type="radio" name="type" value="agree"', !$context['can_pos_rep'] ? ' disabled="disabled"' : ' checked="checked"', ' /> ', $txt['karma_agree'], '
					<input type="radio" name="type" value="disagree"', !$context['can_neg_rep'] ? ' disabled="disabled"' : !$context['can_pos_rep'] ? ' checked="checked"' : '', ' /> ', $txt['karma_disagree'], '<br />
					<br />
					', $txt['comment'], ': <input type="text" size="50" name="reputation_comment" />
					<input type="submit" value="', $txt['submit'], '" />
					<input type="hidden" name="topic" value="', $context['topic'], '" />
					<input type="hidden" name="sc" value="', $context['session_id'], '" />
					<input type="hidden" name="uid" value="', $context['uid'], '" />
					<input type="hidden" name="m" value="', $context['m'], '" />
				</div>
			</form>
		</div>
		<span class="botslice"><span></span></span>
	</div>
	<br />';
}

// License and registration, please. No? Okay, just your reputation log.
function template_log()
{
	global $txt, $context, $user_info, $scripturl, $settings;

	echo '
	<h3 class="catbg">
		<span class="left"></span>
		<img src="', $settings['images_url'], '/karma.gif" alt="" /> ', $txt['reputation_log'], '
	</h3>
	<div class="windowbg2">
		<span class="topslice"><span></span></span>
		<div class="content">';

	if(empty($context['rep_sent']))
				echo '
			', $txt['no_reputation_log'];
	else
	{
		echo '
			<table border="0" cellspacing="0" cellpadding="8" width="100%">
				<tr class="windowbg" style="font-weight: bold;">
					<td width="1%">&nbsp;</td>
					<td width="15%">', $txt['from'], '</td>
					<td width="15%">', $txt['to'], '</td>
					<td width="25%">', $txt['topic'], '</td>
					<td width="14%">', $txt['date'], '</td>
					<td width="30%">', $txt['comment'], '</td>
				</tr>';

		$which = false;

		foreach($context['rep_sent'] as $rep_sent)
		{
			$which = !$which;

			echo '
				<tr class="windowbg', $which ? '2' : '', '">
					<td align="center"><img src="', $settings['images_url'], '/', $rep_sent['action_type'], '_basic.gif" /></td>
					<td><a href="', $scripturl, '?action=profile;u=', $rep_sent['executor']['id'], '">', $rep_sent['executor']['name'], '</a></td>
					<td><a href="', $scripturl, '?action=profile;u=', $rep_sent['target']['id'], '">', $rep_sent['target']['name'], '</a></td>
					<td><a href="', $scripturl, '?topic=', $rep_sent['topic_href'], '">', $rep_sent['topic_title'], '</a></td>
					<td>', $rep_sent['time'], '</td>
					<td>', $rep_sent['comment'], '</td>
				</tr>';
		}

		echo '
			</table>
			<br />
			<strong>', $txt['pages'], ': ', $context['page_index'], '</strong>';
	}

	echo '
		</div>
		<span class="botslice"><span></span></span>
	</div>';
}
?>
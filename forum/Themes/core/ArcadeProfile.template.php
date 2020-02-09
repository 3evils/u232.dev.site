<?php
// Version: 2.5 RC1; ArcadeProfile

function template_arcade_arena_challenge()
{
	global $scripturl, $txt, $context, $settings, $user_info, $modSettings;

	echo '
	<form action="', $scripturl, '?action=arcade;sa=arenaInvite2" method="post">
		<div id="profileview">
			<div class="tborder">
				<h3 class="catbg">', $txt['arcade_invite_user'], ' - ', $context['member']['name'], '</h3>
				<div class="infocenter_section">
					<div class="windowbg">
						<p class="section"></p>
						<div class="windowbg2 sectionbody middletext">';

	if (!empty($context['matches']))
	{
		echo '
							<strong>', $txt['invite_to_existing'], '</strong>:
							<select name="match">';

		foreach ($context['matches'] as $match)
			echo '
								<option value="', $match['id'], '">', $match['name'], '</option>';

		echo '
							</select>
							<input type="submit" value="', $txt['arcade_invite'], '" /><br />';
	}

	echo '
							<a href="', $scripturl, '?action=arcade;sa=newMatch;players=2;player[]=', $context['member']['id'], '">', $txt['arcade_create_new'], '</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>';
}

function template_arcade_user_statistics()
{
	global $scripturl, $txt, $context, $settings, $memberContext;

	echo '
	<div class="tborder arcadestats">
		<h3 class="titlebg headerpadding">', $txt['arcade_member_stats'], ' - ', $context['member']['name'], '</h3>
		<div class="arcadestats_section">
			<div class="windowbg">
				<p class="section"><img src="', $settings['images_url'], '/stats_info.gif" width="20" height="20" alt="" /></p>
				<div class="windowbg2 sectionbody middletext">
					<table border="0" cellpadding="1" cellspacing="0" width="100%">
						<tr>
							<td nowrap="nowrap">', $txt['arcade_champion_in'], ':</td>
							<td align="right">', comma_format($context['arcade']['member_stats']['champion']), ' ', $txt['arcade_games'], '</td>
						</tr><tr>
							<td nowrap="nowrap">', $txt['arcade_rated_game'], ':</td>
							<td align="right">', comma_format($context['arcade']['member_stats']['rates']), ' ', $txt['arcade_games'], '</td>
						</tr><tr>
							<td nowrap="nowrap">', $txt['arcade_average_rating'], ':</td>
							<td align="right">', comma_format($context['arcade']['member_stats']['avg_rating']), '</td>
						</tr>
					</table>
				</div>
			</div>
		</div>';

	if (!empty($context['arcade']['member_stats']['scores']))
	{
		echo '
		<div class="arcadestats_section">
			<h4 class="headerpadding titlebg">', $txt['arcade_member_best_scores'], '</h4>
			<div class="windowbg">
				<p class="section"><img src="', $settings['images_url'], '/stats_info.gif" width="20" height="20" alt="" /></p>
				<div class="windowbg2 sectionbody middletext">
					<table border="0" cellpadding="1" cellspacing="0" width="100%">';

		foreach ($context['arcade']['member_stats']['scores'] as $score)
			echo '
						<tr>
							<td></td>
							<td>', $score['position'], '</td>
							<td><a href="', $score['link'], '">', $score['name'], '</a></td>
							<td>', $score['score'], '</td>
							<td>', $score['time'], '</td>
						</tr>';

		echo '
					</table>
				</div>
			</div>
		</div>';
	}

	if (!empty($context['arcade']['member_stats']['latest_scores']))
	{
		echo '
		<div class="arcadestats_section">
			<h4 class="headerpadding titlebg">', $txt['arcade_latest_scores'], '</h4>
			<div class="windowbg">
				<p class="section"><img src="', $settings['images_url'], '/stats_info.gif" width="20" height="20" alt="" /></p>
				<div class="windowbg2 sectionbody middletext">
					<table border="0" cellpadding="1" cellspacing="0" width="100%">';

		foreach ($context['arcade']['member_stats']['latest_scores'] as $score)
			echo '
						<tr>
							<td></td>
							<td>', $score['position'], '</td>
							<td><a href="', $score['link'], '">', $score['name'], '</a></td>
							<td>', $score['score'], '</td>
							<td>', $score['time'], '</td>
						</tr>';

		echo '
					</table>
				</div>
			</div>
		</div>';
	}

	echo '
	</div>';
}

function template_profile_arcade_notification()
{
	global $scripturl, $txt, $context;

	echo '
	<tr valign="top">
		<td width="40%">
			<b>', $txt['arcade_notifications'], '</b>
		</td>
		<td>';

	foreach ($context['notifications'] as $id => $notify)
		echo '
			<input type="checkbox" id="', $id, '" name="', $id, '" value="1"', $notify['value'] ? ' checked="checked"' : '', ' class="check" /> <label for="', $id, '">', $notify['text'], '</label><br />';

	echo '
		</td>
	</tr>';
}

?>
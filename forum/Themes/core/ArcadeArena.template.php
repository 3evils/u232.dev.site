<?php
// Version: 2.5 RC1; ArcadeArena

function template_arcade_arena_matches()
{
	global $scripturl, $txt, $context, $settings, $user_info, $modSettings;

	$buttons = array();

	if ($context['arcade']['can_create_match'])
		$buttons['newMatch'] = array(
			'text' => 'arcade_newMatch',
			'image' => 'arcade_newmatch.gif',
			'url' => $scripturl . '?action=arcade;sa=newMatch',
			'lang' => true,
		);

	echo '
	<div id="arcadebuttons_top" class="modbuttons clearfix margintop">
		<div class="floatleft middletext">', $txt['pages'], ': ', $context['page_index'], !empty($modSettings['topbottomEnable']) ? $context['menu_separator'] . '&nbsp;&nbsp;<a href="#bot"><b>' . $txt['go_down'] . '</b></a>' : '', '</div>
		', template_button_strip($buttons, 'bottom'), '
	</div>
	<div class="tborder">
		<table cellspacing="1" class="bordercolor matchesframe">
			<thead>
				<tr>';

	if (!empty($context['matches']))
		echo '
					<th class="catbg3 headerpadding"></th>
					<th class="catbg3 headerpadding">', $txt['match_name'], '</th>
					<th class="catbg3 headerpadding">', $txt['match_status'], '</th>
					<th class="catbg3 headerpadding">', $txt['match_players'], '</th>
					<th class="catbg3 headerpadding">', $txt['match_round'], '</th>';
	else
		echo '
					<th class="catbg3 headerpadding"><b>', $txt['arcade_no_matches'], '</b></th>';

	echo '
				</tr>
			</thead>';

	foreach ($context['matches'] as $match)
	{
		echo '
				<tr class="windowbg">
					<td></td>
					<td class="windowbg2">', $match['link'], '<br >', $match['starter']['link'], '</td>
					<td>', $txt[$match['status']], '</td>
					<td>', $match['players'], ' / ', $match['players_limit'], '</td>
					<td>', $match['round'], ' / ', $match['rounds'], '</td>
				</tr>';
	}

	echo '
		</table>
	</div>
	<div id="arcadebuttons_bottom" class="modbuttons clearfix marginbottom">
		<div class="floatleft middletext">', $txt['pages'], ': ', $context['page_index'], !empty($modSettings['topbottomEnable']) ? $context['menu_separator'] . '&nbsp;&nbsp;<a href="#top"><strong>' . $txt['go_up'] . '</strong></a>' : '', '</div>
		', template_button_strip($buttons, 'top'), '
	</div>';
}

function template_arcade_arena_view_match_above()
{
	global $scripturl, $txt, $context, $settings, $user_info, $modSettings;

	echo '
	<script language="JavaScript" type="text/javascript"><!-- // --><![CDATA[
		var arenaPanel = new smfToggle("arcade_info", ', empty($options['arena_panel_collapse']) ? 'false' : 'true', ');
		arenaPanel.setOptions("arena_panel_collapse", "', $context['session_id'], '");
		arenaPanel.addToggleImage("arena_toggle", "/collapse.gif", "/expand.gif");
		arenaPanel.addTogglePanel("arena_panel");
	// ]]></script>
	<div class="tborder" style="margin-bottom: 15px">
		<div class="catbg headerpadding clearfix">
			<span class="floatleft">', $context['match']['name'], '</span>
			<div class="floatright">
				<a href="#" onclick="arenaPanel.toggle(); return false;"><img id="arena_toggle" src="', $settings['images_url'], '/', empty($options['arena_panel_collapse']) ? 'collapse.gif' : 'expand.gif', '" alt="*" align="bottom" style="margin: 0 1ex;" /></a>
			</div>
		</div>
		<div id="arena_panel" class="bordercolor" style="', empty($options['game_panel_collapse']) ? '' : ' display: none;', '">
			<div class="clearfix windowbg2" style="padding: 0.5em 0.7em">
				<div class="floatleft">
					<strong>', $txt['match_status'], '</strong>: ', $txt[$context['match']['status']], '<br />
					<strong>', $txt['match_players'], '</strong>: ', $context['match']['num_players'], ' / ', $context['match']['players_limit'], '<br />
					<strong>', $txt['match_round'], '</strong>: ', $context['match']['round'], ' / ', $context['match']['num_rounds'], '
				</div>
				<div class="floatright">';

	if ($context['can_start_match'])
	{
		echo '
					<a href="', $scripturl, '?action=arcade;sa=viewMatch;start;match=', $context['match']['id'], ';' . $context['session_var'] . '=' . $context['session_id'], '" class="arc_button floatleft clearfix">
						<span class="floatleft">', $txt['arcade_startMatch'], '</span>
						<img src="', $settings['images_url'], '/arena_accept.png" class="floatright" alt="" />
					</a><br />';
	}
	if ($context['can_play_match'])
	{
		echo '
					<a href="', $scripturl, '?action=arcade;sa=play;match=', $context['match']['id'], '" class="arc_button floatleft clearfix">
						<span class="floatleft">', $txt['arcade_play'], '</span>
						<img src="', $settings['images_url'], '/arena_accept.png" class="floatright" alt="" />
					</a><br />';
	}

	if ($context['can_edit_match'])
	{
		echo '
					<a href="', $scripturl, '?action=arcade;sa=viewMatch;delete;match=', $context['match']['id'], ';' . $context['session_var'] . '=', $context['session_id'], '" class="arc_button floatleft clearfix">
						<span class="floatleft">', $txt['arcade_cancelMatch'], '</span>
						<img src="', $settings['images_url'], '/arena_decline.png" class="floatright" alt="" />
					</a><br />';
	}

	if ($context['can_join_match'])
	{
		echo '
					<a href="', $scripturl, '?action=arcade;sa=viewMatch;join;match=', $context['match']['id'], ';' . $context['session_var'] . '=', $context['session_id'], '" class="arc_button floatleft clearfix">
						<span class="floatleft">', $txt['arcade_joinMatch'], '</span>
						<img src="', $settings['images_url'], '/arena_accept.png" class="floatright" alt="" />
					</a><br />';
	}
	elseif ($context['can_leave'])
	{
		echo '
					<a href="', $scripturl, '?action=arcade;sa=viewMatch;leave;match=', $context['match']['id'], ';' . $context['session_var'] . '=', $context['session_id'], '" class="arc_button floatleft clearfix">
						<span class="floatleft">', $txt['arcade_leaveMatch'], '</span>
						<img src="', $settings['images_url'], '/arena_decline.png" class="floatright" alt="" />
					</a><br />';
	}
	elseif ($context['can_accept'])
	{
		echo '
					<a href="', $scripturl, '?action=arcade;sa=viewMatch;join;match=', $context['match']['id'], ';' . $context['session_var'] . '=', $context['session_id'], '" class="arc_button floatleft clearfix">
						<span class="floatleft">', $txt['arcade_accept'], '</span>
						<img src="', $settings['images_url'], '/arena_accept.png" class="floatright" alt="" />
					</a><br />
					<a href="', $scripturl, '?action=arcade;sa=viewMatch;leave;match=', $context['match']['id'], ';' . $context['session_var'] . '=', $context['session_id'], '" class="arc_button floatleft clearfix">
						<span class="floatleft">', $txt['arcade_decline'], '</span>
						<img src="', $settings['images_url'], '/arena_decline.png" class="floatright" alt="" />
					</a><br />';
	}

	echo '
				</div>
			</div>
		</div>
	</div><br />';
}

function template_arcade_arena_view_match()
{
	global $scripturl, $txt, $context, $settings, $user_info, $modSettings;

	echo '
	<div class="floatleft tborder" style="width: 48%">
		<table cellspacing="1" class="playerlist bordercolor">
			<tr>
				<th class="catbg3 headerpadding">', $txt['arcade_position'], '</th>
				<th class="catbg3 headerpadding">', $txt['arcade_member'], '</th>
				<th class="catbg3 headerpadding">', $txt['match_status'], '</th>
				<th class="catbg3 headerpadding">', $txt['arcade_score'], '</th>
			</tr>';

	foreach ($context['match']['players'] as $player)
	{
		echo '
			<tr class="windowbg">
				<td width="15">', $player['rank'], '.</td>
				<td class="windowbg2">
					<span class="floatleft">', $player['link'], '</span>
					<span class="floatright">';

		if ($player['can_kick'])
			echo '
						<a href="', $player['kick_url'], '"><img src="', $settings['images_url'], '/arena_decline.png" alt="" /></a>';

		echo '
					</span>
				</td>
				<td>', $player['status'], '</td>
				<td>', $player['score'], '</td>
			</tr>';
	}

	echo '
		</table>
	</div>

	<div class="floatright tborder" style="width: 48%">
		<table cellspacing="1" class="gameslist bordercolor">
			<tr>
				<th class="catbg3 headerpadding" colspan="2">', $txt['arcade_rounds'], '</th>
			</tr>';

	foreach ($context['match']['rounds'] as $round)
	{
		echo '
			<tr class="windowbg">
				<td width="15">', $round['round'], '.</td>';

		if ($round['select'] && !$round['can_play'])
			echo '
				<td class="windowbg2">', $round['name'], '</td>';
		elseif ($round['select'] && $round['can_play'])
			echo '
				<td class="windowbg2"><a href="', $round['play_url'], '">', $round['name'], '</a></td>';
		elseif ($round['can_select'])
			echo '
				<td class="windowbg2"><a href="', $round['url'], '">', $txt['select_game'], '</a></td>';
		else
			echo '
				<td class="windowbg2"></td>';

		echo '
			</tr>';
	}

	echo '
		</table>
	</div>
	<div class="clearfix"></div>';
}

function template_arcade_arena_view_match_below()
{
	global $scripturl, $txt, $context, $settings, $user_info, $modSettings;

}

function template_arcade_arena_new_match()
{
	global $scripturl, $txt, $context, $settings, $user_info, $modSettings;

	echo '
	<form action="', $scripturl, '?action=arcade;sa=newMatch2" method="post">
		<input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
		<input type="hidden" name="segnum" value="', $context['form_sequence_number'], '" />

		<div class="tborder">
			<h3 class="titlebg headerpadding">', $txt['arcade_new_match'], '</h3>';

	if (!empty($context['errors']))
		echo '
			<div class="windowbg">
				<div style="color: red; margin: 1ex 0 2ex 3ex;" id="error_list">
					', implode('<br />', $context['errors']), '
				</div>
			</div>';

	echo '
			<div class="windowbg2">
				<table width="100%"><tr>
					<td width="150"><label for="match_name">', $txt['arcade_match_name'], '</label></td>
					<td><input type="text" name="match_name" id="match_name"  value="', $context['match']['name'], '" style="width: 99%"/></td>
				</tr><tr>
					<td><label for="game_mode">', $txt['game_mode'], '</label></td>
					<td>
						<select name="game_mode" id="game_mode">
							<option value="normal"', $context['match']['game_mode'] == 'normal' ? ' selected="selected"' : '', '>', $txt['game_mode_normal'], '</option>
							<option value="knockout"', $context['match']['game_mode'] == 'knockout' ? ' selected="selected"' : '', '>', $txt['game_mode_knockout'], '</option>
						</select><br />
					</td>
				</tr><tr>
					<td valign="top">', $txt['players'], '</td>
					<td>
						<input type="text" name="player" id="player" size="25" />
						<input name="player_submit" value="', $txt['player_add'], '" onclick="return oPlayerSuggest.onSubmit();" type="submit">
						<div id="player_container"></div>
						<script language="JavaScript" type="text/javascript" src="', $settings['default_theme_url'], '/scripts/suggest.js?rc1"></script>
						<script language="JavaScript" type="text/javascript"><!-- // --><![CDATA[
							var oPlayerSuggest = new smc_AutoSuggest({
								sSelf: \'oPlayerSuggest\',
								sSessionVar: \'', $context['session_var'], '\',
								sSessionId: \'', $context['session_id'], '\',
								sSuggestId: \'player\',
								sControlId: \'player\',
								sSearchType: \'member\',
								bItemList: true,
								sPostName: \'players_list\',
								sURLMask: \'action=profile;u=%item_id%\',
								sItemListContainerId: \'player_container\',
								aListItems: [';

	foreach ($context['players'] as $member)
		echo '
									{
										sItemId: ', JavaScriptEscape($member['id']), ',
										sItemName: ', JavaScriptEscape($member['name']), '
									}', $member['id'] == $context['last_player_id'] ? '' : ',';

		echo '
								]
							});
						// ]]></script>
						<noscript>';

	foreach ($context['players'] as $member)
		echo '
							<div>
								<input type="hidden" name="players_list[]" value="', $member['id'], '" />
								<a href="', $scripturl, '?action=profile;u=', $member['id'], '" class="extern" onclick="window.open(this.href, \'_blank\'); return false;">', $member['name'], '</a>
								', $user_info['id'] == $member['id'] ? '' : '<input type="image" name="delete_player" value="' . $member['id'] . '" src="' . $settings['images_url'] . '/pm_recipient_delete.gif" alt="' . $txt['player_remove'] . '" />', '
							</div>';

	echo '
						</noscript>
					</td>
				</tr><tr>
					<td><label for="num_players">', $txt['num_players'], '</label></td>
					<td><input type="text" name="num_players" id="num_players" value="', $context['match']['num_players'], '" size="20" /><br />
					<span class="smalltext">', $txt['num_players_help'], '</span></td>
				</tr><tr>
					<td valign="top">', $txt['rounds'], '</td>
					<td>
						<input id="arenagame" style="width: 240px;" autocomplete="off" type="text" name="arenagame_input" value="" />
						<input type="submit" name="arenagame_submit" value="', $txt['add_game'], '" onclick="return gameSelectorarenagame.onSubmit();" />
						<div id="suggest_arenagame" class="game_suggest"></div>
						<script language="JavaScript" type="text/javascript"><!-- // --><![CDATA[
							var gameSelectorarenagame = new gameSelector("', $context['session_id'], '", "arenagame");
						// ]]></script>';

	foreach ($context['match']['rounds'] as $i => $game)
	{
		$game = $context['games'][$game];

		echo '
						<div id="suggest_template_arenagame_', $i, '">
							<input type="hidden" name="rounds[', $i, ']" value="', $game['id'], '" />
							<a href="', $scripturl, '?action=arcade;game=', $game['id'], '" id="game_link_to_', $i, '" class="extern" onclick="window.open(this.href, \'_blank\'); return false;">', $game['name'], '</a>
							<input type="image" name="delete_round" value="', $i, '" onclick="return gameSelectorarenagame.deleteItem(', $i, ');" src="', $settings['images_url'], '/pm_recipient_delete.gif" alt="', $txt['player_remove'], '" /></a>
						</div>';
	}

	echo '
						<div id="suggest_template_arenagame" style="visibility: hidden; display: none;">
							<input type="hidden" name="rounds[]" value="::GAME_ID::" />
							<a href="', $scripturl, '?action=arcade;game=::GAME_ID::" id="game_link_to_::ROUND::" class="extern" onclick="window.open(this.href, \'_blank\'); return false;">::GAME_NAME::</a>
							<input type="image" onclick="return \'::DELETE_ROUND_URL::\'" src="', $settings['images_url'], '/pm_recipient_delete.gif" alt="', $txt['game_remove'], '" />
						</div>
					</td>
				</tr></table>
			</div>
			<div class="windowbg2" style="text-align: right">
				<input type="submit" name="continue" value="', $txt['arcade_continue'], '" />
			</div>
		</div>
	</form>';
}

?>
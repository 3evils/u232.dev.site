<?php
// Version: 2.5 RC1; ArcadeGame

function template_arcade_game_above()
{
	global $scripturl, $txt, $context, $settings;

	// Play link
	$context['arcade']['buttons']['play'] =  array(
		'text' => 'arcade_play',
		'image' => 'arcade_play.gif', // Theres no image for this included (yet)
		'url' => !empty($context['arcade']['play']) ? $scripturl . '?action=arcade;sa=play;game=' . $context['game']['id'] . '" onclick="arcadeRestart(); return false;' : $scripturl . '?action=arcade;sa=play;game=' . $context['game']['id'],
		'lang' => true
	);

	// Highscores link if it is supported
	if ($context['game']['highscore_support'])
		$context['arcade']['buttons']['score'] =  array(
			'text' => 'arcade_viewscore',
			'image' => 'arcade_viewscore.gif', // Theres no image for this included (yet)
			'url' => $scripturl . '?action=arcade;sa=highscore;game=' . $context['game']['id'],
			'lang' => true
		);

	// Random game
	$context['arcade']['buttons']['random'] =  array(
		'text' => 'arcade_random_game',
		'image' => 'arcade_random.gif', // Theres no image for this included (yet)
		'url' => $scripturl . '?action=arcade;sa=play;random',
		'lang' => true
	);

	if ($context['arcade']['can_admin_arcade'])
		$context['arcade']['buttons']['edit'] =  array(
			'text' => 'arcade_edit_game',
			'image' => 'arcade_edit_game.gif', // Theres no image for this included (yet)
			'url' => $scripturl . '?action=admin;area=managegames;sa=edit;game=' . $context['game']['id'],
			'lang' => true
		);

	$ratecode = '';
	$rating = $context['game']['rating'];


	if ($context['arcade']['can_rate'])
	{
		// Can rate

		for ($i = 1; $i <= 5; $i++)
		{
			if ($i <= $rating)
				$ratecode .= '<a href="' . $scripturl . '?action=arcade;sa=rate;game=' . $context['game']['id'] . ';rate=' . $i . ';' . $context['session_var'] . '=' . $context['session_id'] . '" onclick="arcade_rate(' . $i . ', ' . $context['game']['id'] . '); return false;"><img id="imgrate' . $i . '" src="' . $settings['images_url'] . '/arcade_star.gif" alt="*" /></a>';

			else
				$ratecode .= '<a href="' . $scripturl . '?action=arcade;sa=rate;game=' . $context['game']['id'] . ';rate=' . $i . ';' . $context['session_var'] . '=' . $context['session_id'] . '" onclick="arcade_rate(' . $i . ', ' . $context['game']['id'] . '); return false;"><img id="imgrate' . $i . '" src="' . $settings['images_url'] . '/arcade_star2.gif" alt="*" /></a>';
		}
	}
	else
	{
		// Can't rate
		$ratecode = str_repeat('<img src="' . $settings['images_url'] . '/arcade_star.gif" alt="*" />' , $rating);
		$ratecode .= str_repeat('<img src="' . $settings['images_url'] . '/arcade_star2.gif" alt="*" />' , 5 - $rating);
	}

	echo '
	<script language="JavaScript" type="text/javascript"><!-- // --><![CDATA[
		var gamePanel = new smfToggle("arcade_info", ', empty($options['game_panel_collapse']) ? 'false' : 'true', ');
		gamePanel.setOptions("game_panel_collapse", "', $context['session_id'], '");
		gamePanel.addToggleImage("game_toggle", "/collapse.gif", "/expand.gif");
		gamePanel.addTogglePanel("game_panel");
	// ]]></script>
	<div class="tborder" style="margin-bottom: 15px">
		<div class="catbg headerpadding clearfix">
			<span class="floatleft">', $context['game']['name'], '</span>
			<div class="floatright">
				<a href="#" onclick="gamePanel.toggle(); return false;"><img id="game_toggle" src="', $settings['images_url'], '/', empty($options['game_panel_collapse']) ? 'collapse.gif' : 'expand.gif', '" alt="*" align="bottom" style="margin: 0 1ex;" /></a>
			</div>
		</div>
		<div id="game_panel" class="bordercolor" style="', empty($options['game_panel_collapse']) ? '' : ' display: none;', '">
			<div class="clearfix windowbg2" style="padding: 0.5em 0.7em">
				', !empty($context['game']['thumbnail']) ? '
				<div class="floatleft" style="margin-right: 10px">
					<img src="' . $context['game']['thumbnail'] . '" alt="" />
				</div>' : '', '
				<div class="floatleft">';

	if ($context['game']['is_champion'])
		echo '
					<strong>', $txt['arcade_champion'], ':</strong> ', $context['game']['champion']['link'], ' - ', $context['game']['champion']['score'], '<br />';
	if ($context['game']['is_personal_best'])
		echo '
					<strong>', $txt['arcade_personal_best'], ':</strong> ', $context['game']['personal_best'], '<br />';

	echo '
				</div>
				<div class="floatright" style="text-align: right">';

	if ($context['arcade']['can_favorite'])
		echo '
					<a href="', $context['game']['url']['favorite'], '" onclick="arcade_favorite(', $context['game']['id'], '); return false;">', !$context['game']['is_favorite'] ?  '<img id="favgame' . $context['game']['id'] . '" src="' . $settings['images_url'] . '/favorite.gif" alt="' . $txt['arcade_add_favorites'] . '" />' : '<img id="favgame' . $context['game']['id'] . '" src="' . $settings['images_url'] . '/favorite2.gif" alt="' . $txt['arcade_remove_favorite'] . '" />', '</a><br />';

	if ($context['arcade']['can_rate'])
		echo '
					', $ratecode, '<br />';

	echo '
				</div>
			</div>
		</div>
	</div>';
}

// Play screen
function template_arcade_game_play()
{
	global $scripturl, $txt, $context, $settings;

	echo '
	<div id="arcadebuttons_middle" class="modbuttons clearfix margintop">
		', template_button_strip($context['arcade']['buttons'], 'bottom'), '
	</div>
	<div class="tborder">
		<div class="bordercolor">
			<div class="windowbg2" style="padding: 0.5em 0.7em; text-align: center;">
				', $context['game']['html']($context['game'], true), '
				', !$context['arcade']['can_submit'] ? '<br /><b>' . $txt['arcade_cannot_save'] . '</b>' : '', '
			</div>
		</div>
	</div>';

}

// Highscore
function template_arcade_game_highscore()
{
	global $scripturl, $txt, $context, $settings;

	if (isset($context['arcade']['submit']))
	{
		if ($context['arcade']['submit'] == 'newscore') // Was score submitted
		{
			$score = &$context['arcade']['new_score'];

			echo '
	<div class="tborder">
		<div class="titlebg headerpadding">
			', $txt['arcade_submit_score'], '
		</div>
		<div class="bordercolor">
			<div class="windowbg2" style="padding: 0.5em 0.7em; text-align: center">';

			// No permission to save
			if (!$score['saved'])
				echo '
				<div>
					', $txt[$score['error']], '<br />
					<strong>', $txt['arcade_score'], ':</strong> ', $score['score'], '
				</div>';

			else
			{
				echo '
				<div>
					', $txt['arcade_score_saved'], '<br />
					<strong>', $txt['arcade_score'], ':</strong> ', $score['score'], '<br />';

				if ($score['is_new_champion'])
					echo '
					', $txt['arcade_you_are_now_champion'], '<br />';

				elseif ($score['is_personal_best'])
					echo '
					', $txt['arcade_this_is_your_best'], '<br />';

				if ($score['can_comment'])
					echo '
				</div>
				<div>
					<form action="', $scripturl, '?action=arcade;sa=highscore;game=', $context['game']['id'], ';score=',  $score['id'], '" method="post">
						<input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
						<input type="text" id="new_comment" name="new_comment" style="width: 95%;" />
						<input type="submit" name="csave" value="', $txt['arcade_save'], '" />
					</form>
				</div>';

			}

			echo '
			</div>
		</div>
	</div><br />';
		}
		elseif ($context['arcade']['submit'] == 'askname')
		{
			echo '
	<div class="tborder">
		<div class="titlebg headerpadding">
			', $txt['arcade_submit_score'], '
		</div>
		<div class="bordercolor">
			<div class="windowbg2" style="padding: 0.5em 0.7em; "text-align: center">
				<form action="', $scripturl, '?action=arcade;sa=save" method="post">
					<input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
					<input type="text" name="name" style="width: 95%;" />
					<input type="submit" value="', $txt['arcade_save'], '" />
				</form>
			</div>
		</div>
	</div><br />';
		}
	}

	echo '
	<div id="arcadebuttons_middle" class="modbuttons clearfix margintop">
		<div class="floatleft middletext">', $txt['pages'], ': ', $context['page_index'], !empty($modSettings['topbottomEnable']) ? $context['menu_separator'] . '&nbsp;&nbsp;<a href="#bot"><b>' . $txt['go_down'] . '</b></a>' : '', '</div>
		', template_button_strip($context['arcade']['buttons'], 'bottom'), '
	</div>
	<form name="score" action="', $scripturl, '?action=arcade;sa=highscore" method="post">
		<input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
		<input type="hidden" name="game" value="', $context['game']['id'], '" />

		<div class="tborder">
			<div class="titlebg headerpadding">
				', $txt['arcade_highscores'], '
			</div>
			<table cellspacing="1" class="bordercolor scoresframe">
				<thead>
					<tr>';

	// Is there games?
	if (!empty($context['arcade']['scores']))
	{
		echo '
						<th class="catbg3 headerpadding" width="5">', $txt['arcade_position'], '</th>
						<th class="catbg3 headerpadding">', $txt['arcade_member'], '</th>
						<th class="catbg3 headerpadding"> ', $txt['arcade_comment'], '</th>
						<th class="catbg3 headerpadding">', $txt['arcade_score'], '</th>';

		if ($context['arcade']['can_admin_arcade'])
			echo '
						<th class="catbg3 headerpadding" align="center" width="15"><input type="checkbox" onclick="invertAll(this, this.form, \'scores[]\');" class="check" /></th>';
	}
	else
	{
		echo '
						<th class="catbg3 headerpadding"><strong>', $txt['arcade_no_scores'], '</strong></th>';
	}

	echo '
					</tr>
				</thead>';

	$edit_button = create_button('modify.gif', 'arcade_edit', '', 'title="' . $txt['arcade_edit'] . '"');

	foreach ($context['arcade']['scores'] as $score)
	{
		$div_con = addslashes(sprintf($txt['arcade_when'], $score['time'], duration_format($score['duration'])));

		echo '
				<tr class="', $score['own'] ? 'windowbg3' : 'windowbg', '"', !empty($score['highlight']) ? ' style="font-weight: bold;"' : '', ' onmouseover="arcadeBox(\'', $div_con, '\')" onmousemove="arcadeBoxMove(event)" onmouseout="arcadeBox(\'\')">
					<td class="windowbg2" align="center">', $score['position'], '</td>
					<td>', $score['member']['link'], '</td>
					<td width="300" class="windowbg2">';

		if ($score['can_edit'] && empty($score['edit']))
			echo '
						<div id="comment', $score['id'], '" class="floatleft">
							', $score['comment'], '
						</div>
						<div id="edit', $score['id'], '" class="floatleft" style="display: none;">
							<input type="text" id="c', $score['id'], '" value="', $score['raw_comment'], '" style="width: 95%;"  />
							<input type="button" onclick="arcadeCommentEdit(', $score['id'], ', ', $context['game']['id'], ', 1); return false;" name="csave" value="', $txt['arcade_save'], '" />
						</div>
						<a id="editlink', $score['id'], '" onclick="arcadeCommentEdit(', $score['id'], ', ', $context['game']['id'], ', 0); return false;" href="', $scripturl, '?action=arcade;sa=highscore;game=', $context['game']['id'], ';edit;score=', $score['id'], '" class="floatright">', $edit_button, '</a>';
		elseif ($score['can_edit'] && !empty($score['edit']))
		{
			echo '
						<input type="hidden" name="score" value="', $score['id'], '" />
						<input type="text" name="new_comment" id="c', $score['id'], '" value="', $score['raw_comment'], '" style="width: 95%;" />
						<input type="submit" name="csave" value="', $txt['arcade_save'], '" />';
		}
		else
			echo $score['comment'];

		echo '
					</td>
					<td align="center">', $score['score'], '</td>';


		if ($context['arcade']['can_admin_arcade'])
			echo '
					<td class="windowbg2" align="center"><input type="checkbox" name="scores[]" value="', $score['id'], '" class="check" /></td>';

		echo '
				</tr>';
	}

	if ($context['arcade']['can_admin_arcade'])
	{
		echo '
				<tr class="catbg">
					<td colspan="', $context['arcade']['can_admin_arcade'] ? '6' : '5', '" align="right">
						<select name="qaction">
							<option value="">--------</option>
							<option value="delete">', $txt['arcade_delete_selected'], '</option>
						</select>
						<input value="', $txt['go'], '" onclick="return document.forms.score.qaction.value != \'\' && confirm(\'', $txt['arcade_are_you_sure'], '\');" type="submit" />
					</td>
				</tr>';
	}

	echo '
			</table>
		</div>
	</form>';
}

// Below game
function template_arcade_game_below()
{
	global $scripturl, $txt, $context, $settings;

	echo '
	<div id="arcadebuttons_bottom" class="modbuttons clearfix marginbottom">
		', isset($context['page_index']) ? ('<div class="floatleft middletext">' . $txt['pages'] . ': ' . $context['page_index'] . (!empty($modSettings['topbottomEnable']) ? $context['menu_separator'] . '&nbsp;&nbsp;<a href="#bot"><b>' . $txt['go_down'] . '</b></a>' : '') . '</div>')  : '', '
		', template_button_strip($context['arcade']['buttons'], 'top'), '
	</div>
	<div class="tborder" id="arcadebox" style="display: none; position: fixed; left: 0px; top: 0px; width: 33%;">
		<table border="0" width="100%" cellspacing="1" cellpadding="4" class="bordercolor">
			<tr>
				<td class="windowbg2"><div id="arcadebox_html" style=""></div></td>
			</tr>
		</table>
	</div>';
}

?>
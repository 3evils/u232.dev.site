<?php
// Version: 2.5 RC1; ArcadeStats

function template_arcade_statistics()
{
	global $scripturl, $txt, $context, $settings;

	echo '
	<div class="tborder clearfix">
		<h3 class="catbg headerpadding">', $txt['arcade_stats'], '</h3>';

	// Most played games
	if (!empty($context['arcade']['statistics']['play']) > 0)
	{
		echo '
		<div class="arcadestats_section">
			<h4 class="headerpadding titlebg">', $txt['arcade_most_played'], '</h4>
			<div class="windowbg">
				<p class="section"><img src="', $settings['images_url'], '/gold.gif" alt="" /></p>
				<div class="windowbg2 sectionbody middletext">
					<table border="0" cellpadding="1" cellspacing="0" width="100%">';

		foreach ($context['arcade']['statistics']['play'] as $game)
			echo '
						<tr>
							<td width="60%" valign="top">', $game['link'], '</td>
							<td width="20%" align="left" valign="top">', $game['plays'] > 0 ? '<img src="' . $settings['images_url'] . '/bar.gif" width="' . $game['precent'] . '" height="15" alt="" />' : '&nbsp;', '</td>
							<td width="20%" align="right" valign="top">', $game['plays'], '</td>
						</tr>';

		echo '
					</table>
				</div>
			</div>
		</div>';
	}

	// Most active in arcade
	if (!empty($context['arcade']['statistics']['active']))
	{
		echo '
		<div class="arcadestats_section">
			<h4 class="headerpadding titlebg">', $txt['arcade_most_active'], '</h4>
			<div class="windowbg">
				<p class="section">
					<img src="', $settings['images_url'], '/gold.gif" alt="" />
				</p>
				<div class="windowbg2 sectionbody middletext">
					<table border="0" cellpadding="1" cellspacing="0" width="100%">';

		foreach ($context['arcade']['statistics']['active'] as $game)
			echo '
						<tr>
							<td width="60%" valign="top">', $game['link'], '</td>
							<td width="20%" align="left" valign="top">', $game['scores'] > 0 ? '<img src="' . $settings['images_url'] . '/bar.gif" width="' . $game['precent'] . '" height="15" alt="" />' : '&nbsp;', '</td>
							<td width="20%" align="right" valign="top">', $game['scores'], '</td>
						</tr>';

		echo '
					</table>
				</div>
			</div>
		</div>';
	}

	// Top rated games
	if (!empty($context['arcade']['statistics']['rating']))
	{
		echo '
		<div class="arcadestats_section">
			<h4 class="headerpadding titlebg">', $txt['arcade_best_games'], '</h4>
			<div class="windowbg">
				<p class="section">
					<img src="', $settings['images_url'], '/gold.gif" alt="" />
				</p>
				<div class="windowbg2 sectionbody middletext">
					<table border="0" cellpadding="1" cellspacing="0" width="100%">';

		foreach ($context['arcade']['statistics']['rating'] as $game)
			echo '
						<tr>
							<td width="60%" valign="top">', $game['link'], '</td>
							<td width="20%" align="left" valign="top">', $game['rating'] > 0 ? '<img src="' . $settings['images_url'] . '/bar.gif" width="' . $game['precent'] . '" height="15" alt="" />' : '&nbsp;', '</td>
							<td width="20%" align="right" valign="top">', $game['rating'], '</td>
						</tr>';

		echo '
					</table>
				</div>
			</div>
		</div>';
	}

	// Best players by champions
	if (!empty($context['arcade']['statistics']['champions']))
	{
		echo '
		<div class="arcadestats_section">
			<h4 class="headerpadding titlebg">', $txt['arcade_best_players'], '</h4>
			<div class="windowbg">
				<p class="section">
					<img src="', $settings['images_url'], '/gold.gif" alt="" />
				</p>
				<div class="windowbg2 sectionbody middletext">
					<table border="0" cellpadding="1" cellspacing="0" width="100%">';

		foreach ($context['arcade']['statistics']['champions'] as $game)
			echo '
						<tr>
							<td width="60%" valign="top">', $game['link'], '</td>
							<td width="20%" align="left" valign="top">', $game['champions'] > 0 ? '<img src="' . $settings['images_url'] . '/bar.gif" width="' . $game['precent'] . '" height="15" alt="" />' : '&nbsp;', '</td>
							<td width="20%" align="right" valign="top">', $game['champions'], '</td>
						</tr>';

		echo '
					</table>
				</div>
			</div>
		</div>';
	}

	if (!empty($context['arcade']['statistics']['longest']))
	{
		echo '
		<div class="arcadestats_section">
			<h4 class="headerpadding titlebg">', $txt['arcade_longest_champions'], '</h4>
			<div class="windowbg">
				<p class="section">
					<img src="', $settings['images_url'], '/gold.gif" alt="" />
				</p>
				<div class="windowbg2 sectionbody middletext">
					<table border="0" cellpadding="1" cellspacing="0" width="100%">';

		foreach ($context['arcade']['statistics']['longest'] as $game)
			echo '
						<tr>
							<td width="40%" valign="top">', $game['member_link'], ' (', $game['game_link'], ')</td>
							<td width="20%" align="left" valign="top">', $game['duration'] > 0 ? '<img src="' . $settings['images_url'] . '/bar.gif" width="' . $game['precent'] . '" height="15" alt="" />' : '&nbsp;', '</td>
							<td width="40%" align="right" valign="top">', $game['current'] ? '<b>' . $game['duration'] . '</b>' : $game['duration'], '</td>
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

?>
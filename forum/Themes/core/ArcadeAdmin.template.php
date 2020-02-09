<?php
// Version: 2.5 RC1; ArcadeAdmin

function template_arcade_admin_main()
{
	global $context, $settings, $options, $txt, $modSettings, $arcade_version;

	echo '
	<div class="tborder floatleft" style="width: 69%;">
		<h3 class="catbg headerpadding">', $txt['arcade_latest_news'], '</h3>
		<div id="arcade_news" style="overflow: auto; height: 18ex;" class="windowbg2 smallpadding">
			', sprintf($txt['arcade_unable_to_connect'], 'SMFArcade.info'), '
		</div>
	</div>
	<div class="tborder floatright" style="width: 30%;">
		<h3 class="catbg headerpadding">', $txt['arcade_status'], '</h3>
		<div style="overflow: auto; height: 18ex;" class="windowbg2 smallpadding">
			', $txt['arcade_installed_version'], ': <span id="arcade_installed_version">', $arcade_version, '</span><br />
			', $txt['arcade_latest_version'], ': <span id="arcade_latest_version">???</span>
		</div>
	</div>
	<div style="clear: both"></div>
	<script language="JavaScript" type="text/javascript"><!-- // --><![CDATA[
		function setArcadeNews()
		{
			if (typeof(window.arcadeNews) == "undefined" || typeof(window.arcadeNews.length) == "undefined")
					return;

				var str = "<div style=\"margin: 4px; font-size: 0.85em;\">";

				for (var i = 0; i < window.arcadeNews.length; i++)
				{
					str += "\n	<div style=\"padding-bottom: 2px;\"><a href=\"" + window.arcadeNews[i].url + "\">" + window.arcadeNews[i].subject + "</a> on " + window.arcadeNews[i].time + "</div>";
					str += "\n	<div style=\"padding-left: 2ex; margin-bottom: 1.5ex; border-top: 1px dashed;\">"
					str += "\n		" + window.arcadeNews[i].message;
					str += "\n	</div>";
				}

				setInnerHTML(document.getElementById("arcade_news"), str + "</div>");
		}

		function setArcadeVersion()
		{
			if (typeof(window.arcadeCurrentVersion) == "undefined")
				return;

			setInnerHTML(document.getElementById("arcade_latest_version"), window.arcadeCurrentVersion);
		}
	// ]]></script>
	<script language="JavaScript" type="text/javascript" src="http://service.smfarcade.info/arcade/news.js?v=', urlencode($arcade_version), '" defer="defer"></script>';
}

function template_arcade_admin_maintenance()
{
	global $scripturl, $txt, $context, $settings;

	if ($context['maintenance_finished'])
		echo '
			<div class="windowbg" style="margin: 1ex; padding: 1ex 2ex; border: 1px dashed green; color: green;">
				', $txt['arcade_maintain_done'], '
			</div>';

	echo '
	<table border="0" cellspacing="0" cellpadding="4" width="80%" class="tborder" align="center">
		<tr class="titlebg">
			<td>', $txt['arcade_maintenance'], '</td>
		</tr>
		<tr class="windowbg2">
			<td>
				<ul>
					<li><a href="', $scripturl , '?action=admin;area=arcademaintenance;maintenance=fixScores;' . $context['session_var'] . '=', $context['session_id'], '">', $txt['arcade_maintenance_fixScores'], '</a></li>
					<li><a href="', $scripturl , '?action=admin;area=arcademaintenance;maintenance=updateGamecache;' . $context['session_var'] . '=', $context['session_id'], '">', $txt['arcade_maintenance_updateGamecache'], '</a></li>
				</ul>
			</td>
		</tr>
	</table>';

}

function template_arcade_admin_maintenance_highscore()
{
	global $scripturl, $txt, $context, $settings;

	echo '
	<form name="category" action="', $scripturl, '?action=admin;area=arcademaintenance;sa=highscore" method="post">
		<table border="0" cellspacing="0" cellpadding="4" width="80%" class="tborder" align="center">
			<tr class="titlebg">
				<td>', $txt['arcade_maintenance'], ' - ', $txt['arcade_maintenance_highscore'], '</td>
			</tr>
			<tr class="windowbg2">
				<td>
					<div>
						<input type="radio" name="score_action" value="older" />', $txt['arcade_remove_scores_older_than'], ' <input name="age" value="30" /> ', $txt['arcade_remove_scores_days'], '
					</div>
					<div>
						<input type="radio" name="score_action" value="all" />', $txt['arcade_remove_all_scores'], '
					</div>
					<div style="margin: 1ex;" align="right">
						<input class="button_submit" type="submit" name="clear_score" value="', $txt['arcade_remove_now'], '" />
					</div>
				</td>
			</tr>
		</table>

		<input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
	</form>';
}

function template_arcade_admin_category_list()
{
	global $scripturl, $txt, $context, $settings;

	echo '
	<form name="category" action="', $scripturl, '?action=admin;area=arcadecategory;save" method="post">
		<table border="0" cellspacing="0" cellpadding="4" width="80%" class="tborder" align="center">
			<tr class="titlebg">
				<td colspan="3">', $txt['arcade_categories'], '</td>
			</tr>';

	foreach ($context['arcade_category'] as $category)
	{
		echo '
			<tr class="windowbg2">
				<td width="20" align="center" valign="top" style="margin-top: 5px;">
					<input id="cat', $category['id'], '" type="checkbox" name="category[', $category['id'], ']" value="', $category['id'], '" style="check" />
				</td>
				<td width="50" align="left" valign="top" style="margin-top: 5px;">
					<input type="text" name="category_order[', $category['id'], ']" value="', $category['order'], '" style="width: 100%;" />
				</td>
				<td valign="top">
					<a href="', $category['href'], '">', $category['name'], '</a>
				</td>
			</tr>';
	}

	echo '
			<tr class="windowbg2">
				<td align="right" colspan="3">
					<input class="button_submit" type="submit" name="save_settings" value="', $txt['arcade_save_category'], '" />
				</td>
			</tr>
		</table>

		<input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
	</form>';
}

function template_arcade_admin_category_edit()
{
	global $scripturl, $txt, $context, $settings;

	echo '
	<form name="category" action="', $scripturl, '?action=admin;area=arcadecategory;sa=save" method="post">
		<input type="hidden" name="category" value="', $context['category']['id'], '" />
		<table border="0" cellspacing="0" cellpadding="4" width="80%" class="tborder" align="center">
			<tr class="titlebg">
				<td colspan="3">', $txt['arcade_categories'], '</td>
			</tr>
			<tr class="windowbg2">
				<td>', $txt['category_name'], '</td>
				<td width="50%"><input type="text" name="category_name" value="', $context['category']['name'], '" /></td>
			</tr>
			<tr class="windowbg2">
				<td>', $txt['arcade_category_permission_allowed'], '</td>
				<td width="50%">';

	foreach ($context['groups'] as $group)
		echo '
					<label for="groups_', $group['id'], '"><input type="checkbox" name="groups[]" value="', $group['id'], '" id="groups_', $group['id'], '"', $group['checked'] ? ' checked="checked"' : '', ' class="check" /><span', $group['is_post_group'] ? ' style="border-bottom: 1px dotted;" title="' . $txt['pgroups_post_group'] . '"' : '', '>', $group['name'], '</span></label><br />';

	echo '
					<i>', $txt['check_all'], '</i> <input type="checkbox" onclick="invertAll(this, this.form, \'groups[]\');" class="check" /><br />
					<br />
				</td>
			</tr>
			<tr class="windowbg2">
				<td align="right" colspan="3">
					<input class="button_submit" type="submit" name="save_settings" value="', $txt['arcade_save_category'], '" />
				</td>
			</tr>

			<input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
		</table>
	</form>';
}

function template_arcadeadmin_above()
{
	global $scripturl, $txt, $modSettings, $context, $settings, $arcade_version;

}

function template_arcadeadmin_below()
{
	global $arcade_version;

	// Print out copyright and version. Removing copyright is not allowed by license
	echo '
	<div id="arcade_bottom" class="smalltext" style="text-align: center;">
		Powered by: <a href="http://www.smfarcade.info/" target="_blank">SMF Arcade ', $arcade_version, '</a> &copy; <a href="http://www.madjoki.com/" target="_blank">Niko Pahajoki</a> 2004-2011</div>';

}

?>

<?php
// Version: 2.5 RC1; Arcade

function template_arcade_above()
{
	global $scripturl, $txt, $context, $settings, $options;

	if (!empty($context['arcade_tabs']))
	{
		echo '
	<div class="tborder">
		<div class="titlebg headerpadding clearfix">
			<span class="floatleft">', $context['arcade_tabs']['title'], '</span>
			<img id="arcade_toggle" class="floatright" src="', $settings['images_url'], '/upshrink.gif', '" alt="*" title="', $txt['upshrink_description'], '" style="display: none;" /></a>
		</div>
		<div id="arcade_panel" class="bordercolor"', empty($options['arcade_panel_collapse']) ? '' : ' style="display: none;"', '>
			<div class="windowbg2" style="padding: 0.5em 0.7em">';

		if (!empty($context['arcade']['notice']))
			echo '
				<span class="arcade_notice">', $context['arcade']['notice'], '</span><br />';

		echo '
				<form action="', $scripturl, '?action=arcade;sa=search" method="post">
					<input id="gamesearch" style="width: 240px;" autocomplete="off" type="text" name="name" value="', isset($context['arcade_search']['name']) ? $context['arcade_search']['name'] : '', '" /> <input type="submit" value="', $txt['arcade_search'], '" />
					<div id="suggest_gamesearch" class="game_suggest"></div>
					<div id="search_extra">
						<input type="checkbox" id="favorites" name="favorites" value="1"', !empty($context['arcade_search']['favorites']) ? ' checked="checked"' : '', ' class="check" /> <label for="favorites">', $txt['search_favorites'], '</label>
					</div>
					<script language="JavaScript" type="text/javascript"><!-- // --><![CDATA[
						var gSuggest = new gameSuggest("', $context['session_id'], '", "gamesearch");
					// ]]></script>
				</form>
			</div>
		</div>
	</div>
	<table cellpadding="0" cellspacing="0" border="0" style="margin-left: 10px;">
		<tr>
			<td class="maintab_first">&nbsp;</td>';

		// Print out all the items in this tab.
		foreach ($context['arcade_tabs']['tabs'] as $tab)
		{
			if (!empty($tab['is_selected']))
			{
				echo '
			<td class="maintab_active_first">&nbsp;</td>
			<td valign="top" class="maintab_active_back">
				<a href="', $tab['href'], '">', $tab['title'], '</a>
			</td>
			<td class="maintab_active_last">&nbsp;</td>';

					$selected_tab = $tab;
			}
			else
				echo '
			<td valign="top" class="maintab_back">
				<a href="', $tab['href'], '">', $tab['title'], '</a>
			</td>';
		}

		// the end of tabs
		echo '
			<td class="maintab_last">&nbsp;</td>
		</tr>
	</table><br />';
	
	echo '
		<script type="text/javascript"><!-- // --><![CDATA[
			var oMainHeaderToggle = new smc_Toggle({
				bToggleEnabled: true,
				bCurrentlyCollapsed: ', empty($options['arcade_panel_collapse']) ? 'false' : 'true', ',
				aSwappableContainers: [
					\'arcade_panel\'
				],
				aSwapImages: [
					{
						sId: \'arcade_toggle\',
						srcExpanded: smf_images_url + \'/upshrink.gif\',
						altExpanded: ', JavaScriptEscape($txt['upshrink_description']), ',
						srcCollapsed: smf_images_url + \'/upshrink2.gif\',
						altCollapsed: ', JavaScriptEscape($txt['upshrink_description']), '
					}
				],
				oThemeOptions: {
					bUseThemeSettings: ', $context['user']['is_guest'] ? 'false' : 'true', ',
					sOptionName: \'arcade_panel_collapse\',
					sSessionVar: ', JavaScriptEscape($context['session_var']), ',
					sSessionId: ', JavaScriptEscape($context['session_id']), '
				},
				oCookieOptions: {
					bUseCookie: ', $context['user']['is_guest'] ? 'true' : 'false', ',
					sCookieName: \'arcade_panel_collapse\'
				}
			});
		// ]]></script>';
	}

	echo '
	<div id="arcade_top">';
}

function template_arcade_below()
{
	global $arcade_version;

	// Print out copyright and version. Removing copyright is not allowed by license
	echo '
	</div>

	<div id="arcade_bottom" class="smalltext" style="text-align: center;">
		Powered by: <a href="http://www.smfarcade.info/" target="_blank">SMF Arcade ', $arcade_version, '</a> &copy; <a href="http://www.madjoki.com/" target="_blank">Niko Pahajoki</a> 2004-2011</div>';

}

?>
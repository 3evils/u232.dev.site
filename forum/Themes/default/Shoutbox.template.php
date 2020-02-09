<?php
/**********************************************************************************
* Shoutbox.template.php                                                           *
***********************************************************************************
*                                                                                 *
* SMFPacks Shoutbox v1.0                                                          *
* Copyright (c) 2009-2010 by Makito and NIBOGO. All rights reserved.              *
* Powered by www.smfpacks.com                                                     *
* Created by Makito                                                               *
* Developed by NIBOGO for SMFPacks.com                                            *
*                                                                                 *
**********************************************************************************/

function template_shoutbox($main = false)
{
	global $context, $settings, $scripturl, $txt, $shoutbox, $boardurl;

	// $context['shoutbox'] must exists
	if (!isset($context['shoutbox']))
		return;

	// maybe we want shoutbox in this page, but not where main function wants
	if ($main == 'main' && isset($context['shoutbox']['out_main']))
		return;

	// banned?
	if ($context['shoutbox']['banned'])
	{
		// print
		echo '
	<div class="tborder"' . (isset($context['shoutbox_popup']) ? ' style="margin:10px">
		<div class="windowbg2" style="padding:0.4em;text-align:left">' : ' style="margin:10px 0">
		<div class="catbg" style="padding:5px 15px;margin:0 auto;text-align:center">
			' . $shoutbox['boxTitle'] . '
		</div>
		<div class="windowbg2" style="margin-top:1px;padding:0.4em;text-align:left">') . '
			<span class="error"><b>' . $txt['sbe_2'] . '</b></span><br />
			<span class="smalltext"><b>' . $txt['sbm_19'] . '</b>: ' . $context['shoutbox']['banned']['reason'] . '<br />
			<b>' . $txt['sbm_13'] . '</b>: ' . $context['shoutbox']['banned']['end'] . '</span>
		</div>
	</div>';

		unset($context['shoutbox']);
		return;
	}

	// colorPicker
	if ($context['shoutbox']['can_post'] && (!isset($context['shoutbox']['disabled']['color']) || !isset($context['shoutbox']['disabled']['bgcolor'])))
		echo '
	<script language="javascript" type="text/javascript" src="', $settings['default_theme_url'], '/colorpicker.js"></script>';
	// shoutbox
	echo '
	<script language="javascript" type="text/javascript" src="', $settings['default_theme_url'], '/shoutbox.js"></script>';

	// javascript: config, langs, etc
	echo '
	<script language="javascript" type="text/javascript"><!-- // --><![CDATA[';

		if ($context['browser']['is_ie'])
			echo '
		Shoutbox.ie = 1;';
		if (!empty($shoutbox['showmsg_down']))
			echo '
		Shoutbox.msgdown = 1;';
		if (isset($context['shoutbox_popup']))
			echo '
		Shoutbox.popup = 1;';

		// settings :)
		echo '
		Shoutbox.refresh = ' . $shoutbox['refreshShouts'] . ';
		Shoutbox.height = ' . $shoutbox['height'] . ';
		Shoutbox.keepmsgs = ' . $shoutbox['keepShouts'] . ';';

		if ($context['shoutbox']['can_post'])
		{
			echo '
		Shoutbox.maxlength = ' . $shoutbox['maxMsgLenght'] . ';
		Shoutbox.minlength = ' . $shoutbox['minMsgLenght'] . ';
		Shoutbox.feature.default_color = "' . $shoutbox['textColor'] . '";
		Shoutbox.feature.default_bgcolor = "' . $shoutbox['backgroundColor'] . '";';

			if (isset($context['shoutbox']['disabled']['color']))
				echo '
		Shoutbox.disabled.color = 1;';
			if (isset($context['shoutbox']['disabled']['bgcolor']))
				echo '
		Shoutbox.disabled.bgcolor = 1;';
			if (isset($context['shoutbox']['disabled']['faces']))
				echo '
		Shoutbox.disabled.faces = 1;';
			if (isset($context['shoutbox']['disabled']['b']))
				echo '
		Shoutbox.disabled.b = 1;';
			if (isset($context['shoutbox']['disabled']['i']))
				echo '
		Shoutbox.disabled.i = 1;';
			if (isset($context['shoutbox']['disabled']['u']))
				echo '
		Shoutbox.disabled.u = 1;';
		}

		if (!empty($shoutbox['startHide']))
			echo '
		Shoutbox.hide = 1;';

		// langs
		echo '
		Shoutbox.lang.tooshort = "' . $txt['sb_12'] . '";
		Shoutbox.lang.toolong = "' . $txt['sb_12b'] . '";
		Shoutbox.lang.posting = "' . $txt['sbe_1'] . '";
		Shoutbox.lang.banned = "' . $txt['sbe_2'] . '. ' . $txt['sbe_3'] . '";';

	echo '
	// ]]></script>';

	// title
	echo '
	<div class="cat_bar">
		<h3 class="catbg">
			<a href="javascript:void(0);" class="collapse floatright" style="margin-top: 5px;">
				<img border="0" onclick="Shoutbox_ShowHide();" id="shoutbox_img" alt="-" src="' . $settings['theme_url'] . '/images/collapse.gif" />
			</a>
			', $shoutbox['boxTitle'], '
		</h3>
	</div>
	<div id="shoutbox" ' . (!isset($context['shoutbox_popup']) ? ' style="display:none">' : '>');

	// view
	echo'
			<div class="windowbg2">
				<span class="topslice"><span></span></span>
					<div class="content">';
				
					// post: form and bbcodes
					if (empty($shoutbox['showform_down']))
					{
						template_shoutbox_postbar($main);
						
						echo'
							<hr />';
					}
				
					echo'
						<div class="smalltext" style="text-align: right;">
							<span class="floatleft" style="margin-right:0.5em">
								<img id="shoutbox_status" src="', $settings['default_images_url'], '/loading.gif" alt="" border="0" />
							</span>
							<a href="#refresh" onclick="Shoutbox_GetMsgs(); return false;">' . $txt['sb_62'] . '</a>' . (!isset($context['shoutbox_popup']) ? '
							| <a href="' . $scripturl . '?action=shoutbox" ' . (isset($context['shoutbox_popup']) ? '' : ' onclick="return reqWin(this.href,800, ' . $shoutbox['height'] . ' + 80);"') . '>' . $txt['sb_63'] . '</a>' : '') . ($context['shoutbox']['can_moderate'] ? '
							| <a href="' . $scripturl . '?action=shoutbox;sa=moderate" ' . (isset($context['shoutbox_popup']) ? '' : ' onclick="return reqWin(this.href,800, ' . $shoutbox['height'] . ' + 80);"') . '>' . $txt['sb_61'] . '</a>' : '') . '
							<span id="shoutbox_bar"> | <a title="Powered by SMFPacks.com" target="_blank" href="http://www.smfpacks.com/">&copy;</a></span>
						</div>';
		
				// msgs: if $shoutbox['showmsg_down'], vertical align to bottom :|
				echo '
						<div id="shoutbox_banned" style="margin:0 0.6em;padding-right:0.6em;overflow:auto;height:' . $shoutbox['height'] . 'px;max-height:' . $shoutbox['height'] . 'px">
							' . (!empty($shoutbox['showmsg_down']) ? '<table cellspacing="0" cellpadding="0" border="0" align="left"><tr><td valign="bottom" height="' . $shoutbox['height'] . '"><table id="shoutbox_table" cellspacing="0" cellpadding="2" border="0">' : '<table id="shoutbox_table" cellspacing="0" cellpadding="2" border="0" align="left">');
		
					// IE all the time !!!
					if (!$context['browser']['is_ie'])
						echo '
								' . (empty($shoutbox['showmsg_down']) ? '<thead id="shoutbox_msgs"></thead>' : '<tr id="shoutbox_msgs"><td></td></tr>');
		
				echo '
							</table>' . (!empty($shoutbox['showmsg_down']) ? '</td></tr></table>' : '') . '
						</div>';
						
				// post: form and bbcodes
				if (!empty($shoutbox['showform_down']))
				{
					echo'
						<hr />';
					template_shoutbox_postbar($main);
				}
				
		echo'
					</div>
				<span class="botslice"><span></span></span>
			</div>';

	echo '
	</div>';

	// new msgs sound
	echo '
	<div style="height:0.1em">
		<object id="shoutbox_object" width="1" height="1" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0">
			<param name="movie" value="', $settings['default_theme_url'], '/shoutbox.swf?dir=', $boardurl, '"></param>
			<param name="quality" value="high"></param>
			<embed id="shoutbox_embed" src="', $settings['default_theme_url'], '/shoutbox.swf?dir=', $boardurl, '" width="1" height="1" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"></embed>
		</object>
	</div>';

	// script to start
	echo '
	<script language="javascript" type="text/javascript"><!-- // --><![CDATA[
		Shoutbox_GetFeatures();
		if (!Shoutbox.hide) Shoutbox.msgs = setTimeout("Shoutbox_GetMsgs();", 1000);
	// ]]></script>';

	// shoutbox is printed
	unset($context['shoutbox']);
}

function template_shoutbox_postbar($main)
{
	global $context, $settings, $scripturl, $txt, $shoutbox, $boardurl, $user_info, $modSettings;

	// $context['shoutbox'] must exists
	if (!isset($context['shoutbox']) || $main == 'post')
		return;

	if (!$context['shoutbox']['can_post'])
		return;

	echo '
			<div>
				<table cellpadding="0" cellspacing="0" border="0" width="100%">
					<tr>
						<td width="100%" style="padding-right: 1ex;">
							<input type="text" id="shoutbox_message" style="text-decoration:underline;padding:2px 0;width:90%" onkeypress="var k = window.event ? event.keyCode : event.which; if (k == 13) Shoutbox_SentMsg(\'' . $context['session_id'] . '\');" />
							<input type="submit" class="button_submit" value="', $txt['sendtopic_send'], '" onclick="Shoutbox_SentMsg(\'' . $context['session_id'] . '\');"" />
						</td>
						<td>
							&nbsp;&nbsp;
						</td>';

	/* do we use this?
	echo '
						<td nowrap="nowrap" valign="middle" style="padding:0 0.6em 0 0.4em">
							<input type="submit" id="shoutbox_send"  value="" onclick="Shoutbox_SentMsg(\'' . $context['session_id'] . '\');" /></td>';
	*/

	echo '
						<td nowrap="nowrap" valign="middle" style="padding:0.1em 0 0 0.2em">
							<div style="position:relative">';

	// disable sound
	echo '
								<img id="shoutbox_nosound" onclick="Shoutbox_SetStyle(\'nosound\');" onmouseover="Shoutbox_Hover(this, true)" onmouseout="Shoutbox_Hover(this, false)" src="' . $settings['default_images_url'] . '/bbc/nosound.gif" alt="nosound" style="background-image:url(\'' . $settings['images_url'] . '/bbc/bbc_bg.gif\');cursor:pointer" />';

	// bold
	if (!isset($context['shoutbox']['disabled']['b']))
		echo '
								<img id="shoutbox_b" onclick="Shoutbox_SetStyle(\'b\');" onmouseover="Shoutbox_Hover(this, true)" onmouseout="Shoutbox_Hover(this, false)" src="' . $settings['default_images_url'] . '/bbc/bold.gif" alt="b" style="background-image:url(\'' . $settings['images_url'] . '/bbc/bbc_bg.gif\');cursor:pointer" />';

	// italic
	if (!isset($context['shoutbox']['disabled']['i']))
		echo '
								<img id="shoutbox_i" onclick="Shoutbox_SetStyle(\'i\');" onmouseover="Shoutbox_Hover(this, true)" onmouseout="Shoutbox_Hover(this, false)" src="' . $settings['default_images_url'] . '/bbc/italicize.gif" alt="i" style="background-image:url(\'' . $settings['images_url'] . '/bbc/bbc_bg.gif\');cursor:pointer" />';

	// underline
	if (!isset($context['shoutbox']['disabled']['u']))
		echo '
								<img id="shoutbox_u" onclick="Shoutbox_SetStyle(\'u\');" onmouseover="Shoutbox_Hover(this, true)" onmouseout="Shoutbox_Hover(this, false)" src="' . $settings['default_images_url'] . '/bbc/underline.gif" alt="u" style="background-image:url(\'' . $settings['images_url'] . '/bbc/bbc_bg.gif\');cursor:pointer" />';

	// smileys
	if (!isset($context['shoutbox']['disabled']['smileys']))
		echo '
								<img onclick="Shoutbox_SetStyle(\'smileys\');" onmouseover="Shoutbox_Hover(this, true)" onmouseout="Shoutbox_Hover(this, false)" src="' . $settings['default_images_url'] . '/bbc/smileys.gif" alt="smileys" style="background-image:url(\'' . $settings['images_url'] . '/bbc/bbc_bg.gif\');cursor:pointer" />';

	// faces
	if (!isset($context['shoutbox']['disabled']['faces']))
		echo '
								<img id="shoutbox_face" onclick="o = document.getElementById(\'shoutbox_faces\').style; o.display = o.display == \'none\' ? \'\' : \'none\';" onmouseover="Shoutbox_Hover(this, true)" onmouseout="Shoutbox_Hover(this, false)" src="' . $settings['default_images_url'] . '/bbc/face.gif" alt="faces" style="background-image:url(\'' . $settings['images_url'] . '/bbc/bbc_bg.gif\');cursor:pointer" />';

	// float
	$float = 0;

	// color
	if (!isset($context['shoutbox']['disabled']['color']))
	{
		$float = !isset($context['shoutbox']['disabled']['bgcolor']) ? 28 : 0;
		echo '
								<img id="shoutbox_color" onclick="document.getElementById(\'colorpicker\').style.right = \'' . $float . 'px\'; colorPicker[\'bg\'] = false; ColorPicker_ShowHide();" onmouseover="Shoutbox_Hover(this, true)" onmouseout="Shoutbox_Hover(this, false)" src="' . $settings['default_images_url'] . '/bbc/fontcolor.gif" alt="color" style="background-image:url(\'' . $settings['images_url'] . '/bbc/bbc_bg.gif\');cursor:pointer" />';
		$float += 28;
	}

	// bgcolor
	if (!isset($context['shoutbox']['disabled']['bgcolor']))
		echo '
								<img id="shoutbox_bgcolor" onclick="document.getElementById(\'colorpicker\').style.right = \'0\'; colorPicker[\'bg\'] = true; ColorPicker_ShowHide();" onmouseover="Shoutbox_Hover(this, true)" onmouseout="Shoutbox_Hover(this, false)" src="' . $settings['default_images_url'] . '/bbc/bgcolor.gif" alt="bgcolor" style="background-image:url(\'' . $settings['images_url'] . '/bbc/bbc_bg.gif\');cursor:pointer" />';

	// face
	if (!isset($context['shoutbox']['disabled']['faces']) && !empty($shoutbox['faces']))
	{
		echo '
								<div class="tborder" style="display:none;position:absolute;' . (empty($shoutbox['showform_down']) ? 'bottom:-163px' : 'bottom:28px') . ';right:' . $float . 'px" id="shoutbox_faces">
									<div class="windowbg2 smalltext" style="text-align:left;padding:6px 8px;width:145px;overflow:auto;height:150px;max-height:150px">
										';

		foreach (explode(',', $shoutbox['faces']) as $f)
			echo '<div style="padding:2px 0"><a href="#setFace" onclick="Shoutbox_SetStyle(\'face\', \'' . $f . '\'); return false;" style="display:block;font-family:' . $f . '">' . $f . '</a></div>';

		echo '
									</div>
								</div>';
		$float += 28;
	}

	if (!isset($context['shoutbox']['disabled']['smileys']))
	{
		// SMF 2.0
		$settings['smileys_url'] = $modSettings['smileys_url'] . '/' . $user_info['smiley_set'];

		echo '
								<div class="tborder" style="display:none;position:absolute;' . (empty($shoutbox['showform_down']) ? 'bottom:-163px' : 'bottom:28px') . ';right:' . $float . 'px" id="shoutbox_smileys">
									<div class="windowbg2 smalltext" style="padding:6px 8px;text-align:left;width:65px;overflow:auto;height:150px;max-height:150px">
										';

		foreach ($context['shoutbox']['smileys']['postform'] as $smiley_row)
			foreach ($smiley_row['smileys'] as $smiley)
				echo '<img src="', $settings['smileys_url'], '/', $smiley['filename'], '" alt="" title="', $smiley['description'], '" onclick="replaceText(\' ', $smiley['code'], '\', document.getElementById(\'shoutbox_message\')); return false;" style="cursor:pointer;margin:3px" /> <br />';

		if (!empty($context['shoutbox']['smileys']['popup']))
			echo '
										<a href="javascript:moreSmileys();">[', $txt['sb_more'], ']</a>';

		echo '
									</div>
								</div>';
		$float += 28;
	}

	// color & bgcolor picker
	if (!isset($context['shoutbox']['disabled']['color']) || !isset($context['shoutbox']['disabled']['bgcolor']))
		echo '
								<div class="tborder" style="display:none;position:absolute;' . (empty($shoutbox['showform_down']) ? 'bottom:-163px' : 'bottom:28px') . ';right:0" id="colorpicker">
									<div class="windowbg2">
										<table cellpadding="4" cellspacing="0">
											<tr>
												<td rowspan="2">
													<div class="tborder" style="padding:0;margin0"><script language="javascript" type="text/javascript">ColorPicker_ColorBox();</script></div>
												</td>
												<td style="text-align:left" valign="top" class="smalltext" nowrap="nowrap">
													<div class="tborder" style="border-bottom:0;margin:0;padding:0;background-color:#fff;width:100px;height:20px" id="colorpicker_select"></div>
													<div class="tborder" style="border-top:0;margin:0;padding:0;background-color:#fff;width:100px;height:20px" id="colorpicker_sample"></div>
													<br /><input type="text" value="#FFFFFF" style="padding:2px;width:96px" class="smalltext" id="colorpicker_hexa" />
													<div style="text-align:right">
														<br /><a href="javascript:;" onclick="Shoutbox_SetStyle(colorPicker[\'bg\'] ? \'bgcolor\' : \'color\', document.getElementById(\'colorpicker_hexa\').value, true);">' . $txt['sb_13'] . '</a>
														| <a href="javascript:;" onclick="Shoutbox_SetStyle(colorPicker[\'bg\'] ? \'bgcolor\' : \'color\', \'\', true);">' . $txt['sb_17'] . '</a>
														<br /><br /><a href="javascript:;" onclick="ColorPicker_ShowHide(); Shoutbox_Hover(document.getElementById(colorPicker[\'bg\'] ? \'shoutbox_bgcolor\' : \'shoutbox_color\'), false);">' . $txt['sb_14'] . '</a>
													</div>
												</td>
											</tr>
											<tr>
												<td valign="bottom" align="center">
													<div class="tborder" style="width:90px;padding:0;margin:0"><script language="javascript" type="text/javascript">ColorPicker_BoxGrayScale();</script></div>
												</td>
											</tr>
										</table>
									</div>
								</div>';

	echo '
							</div>
						</td>
					</tr>
				</table>';

	if (!isset($context['shoutbox']['disabled']['smileys']) && !empty($context['shoutbox']['smileys']['popup']))
	{
		echo '
				<script language="JavaScript" type="text/javascript"><!-- // -->
					var smileys = [';

		foreach ($context['shoutbox']['smileys']['popup'] as $smiley_row)
		{
			echo '
					[';
			foreach ($smiley_row['smileys'] as $smiley)
			{
				echo '
						["', $smiley['code'], '","', $smiley['filename'], '","', $smiley['js_description'], '"]';
					if (empty($smiley['last']))
					echo ',';
			}

			echo ']';
			if (empty($smiley_row['last']))
				echo ',';
		}

		echo '];
					var smileyPopupWindow;

					function moreSmileySet(s)
					{
						replaceText(s, document.getElementById(\'shoutbox_message\'));
					}
					function moreSmileys()
					{
						var row, i;

						if (smileyPopupWindow)
							smileyPopupWindow.close();

						smileyPopupWindow = window.open("", "add_smileys", "toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,width=480,height=220,resizable=yes");
						smileyPopupWindow.document.write(\'\n<html>\');
						smileyPopupWindow.document.write(\'\n\t<head>\n\t\t<title>', $txt['sb_more_title'], '</title>\n\t\t<link rel="stylesheet" type="text/css" href="', $settings['default_theme_url'], '/style.css" />\n\t</head>\');
						smileyPopupWindow.document.write(\'\n\t<body style="margin: 1ex;">\n\t\t<table width="100%" cellpadding="5" cellspacing="0" border="0" class="tborder">\n\t\t\t<tr class="titlebg"><td align="left" class="smalltext">', $txt['sb_pick'], '</td></tr>\n\t\t\t<tr class="windowbg"><td align="left" class="smalltext">\');

						for (row = 0; row < smileys.length; row++)
						{
							for (i = 0; i < smileys[row].length; i++)
							{
								smileys[row][i][2] = smileys[row][i][2].replace(/"/g, \'&quot;\');
								smileyPopupWindow.document.write(\'<a href="javascript:void(0);" onclick="window.opener.moreSmileySet(&quot; \' + smileys[row][i][0] + \'&quot;); window.focus(); return false;"><img src="', $settings['smileys_url'], '/\' + smileys[row][i][1] + \'" alt="\' + smileys[row][i][2] + \'" title="\' + smileys[row][i][2] + \'" style="padding: 4px;" border="0" /></a> \');
							}
							smileyPopupWindow.document.write("<br />");
						}

						smileyPopupWindow.document.write(\'</td></tr>\n\t\t\t<tr><td style="margin:0 auto;text-align:center;" class="windowbg smalltext"><a href="javascript:window.close();\\">', $txt['sb_close'], '</a></td></tr>\n\t\t</table>\n\t</body>\n</html>\');
						smileyPopupWindow.document.close();
					}
				//	]]></script>';
	}

	echo '
			</div>';
}

function template_shoutbox_popup()
{
	global $context, $settings, $scripturl;

	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=', $context['character_set'], '" />
	<title>', $context['page_title'], '</title>';

	if (isset($context['shoutbox_smf_2']))
	{
		echo '
	<link rel="stylesheet" type="text/css" href="' . $settings['theme_url'] . '/css/index.css" />
	<script language="JavaScript" type="text/javascript" src="' . $settings['default_theme_url'] . '/scripts/script.js?rc1"></script>', $context['html_headers'];

		// IE7 needs some fixes for styles.
		if ($context['browser']['is_ie7'])
			echo '
	<link rel="stylesheet" type="text/css" href="', $settings['default_theme_url'], '/css/ie7.css" />';
		// ..and IE6!
		elseif ($context['browser']['is_ie6'])
			echo '
	<link rel="stylesheet" type="text/css" href="', $settings['default_theme_url'], '/css/ie6.css" />';
		// Firefox - all versions - too!
		elseif ($context['browser']['is_firefox'])
			echo '
	<link rel="stylesheet" type="text/css" href="', $settings['default_theme_url'], '/css/ff.css" />';
	}
	else
		echo '
	<link rel="stylesheet" type="text/css" href="' . $settings['theme_url'] . '/style.css?fin11" />
	<script language="JavaScript" type="text/javascript" src="' . $settings['default_theme_url'] . '/script.js?fin11"></script>';

	echo '
	<script language="JavaScript" type="text/javascript"><!-- // --><![CDATA[
		var smf_theme_url = "', $settings['theme_url'], '";
		var smf_images_url = "', $settings['images_url'], '";
		var smf_scripturl = "', $scripturl, '";
		var smf_iso_case_folding = ', $context['server']['iso_case_folding'] ? 'true' : 'false', ';
		var smf_charset = "', $context['character_set'], '";
	// ]]></script>
</head>
<body style="padding:0;margin:0">
	<div id="mainframe" style="margin:0;width:100%">';

	if (empty($context['shoutbox']))
		echo $txt['sbe_4'];
	else
		template_shoutbox();

	echo '
	</div>
</body>
</html>';
}

function template_shoutbox_getmsgs()
{
	global $context, $shoutbox;

	echo '<', '?xml version="1.0" encoding="', $context['character_set'], '"?', '>
<smf>';

	// close shoutbox :)
	if (isset($context['shoutbox_echo']['banned']))
		echo '
	<banned />';
	// reset shoutbox
	elseif (isset($context['shoutbox_echo']['prune']))
		echo '
	<reset />
	<msgs><![CDATA[' . (!$context['browser']['is_ie'] && empty($shoutbox['showmsg_down']) ? '<thead id="shoutbox_msgs"></thead>' : '') . (!$context['browser']['is_ie'] && !empty($shoutbox['showmsg_down']) ? '<tr id="shoutbox_msgs"></tr>' : '') . ']]></msgs>
	<count><![CDATA[0]]></count>';
	elseif (!empty($context['shoutbox_echo']['msgs']))
	{
		echo '
	<msgs><![CDATA[' . (!$context['browser']['is_ie'] && empty($shoutbox['showmsg_down']) ? '<thead id="shoutbox_msgs"></thead>' : '');

		if (empty($shoutbox['showmsg_down']))
			$_GET['row'] += count($context['shoutbox_echo']['msgs']);
		else
			$_GET['row'] = $_GET['row'] == 0 ? 1: $_GET['row'];

		foreach ($context['shoutbox_echo']['msgs'] as $shout)
			echo '<tr id="shoutbox_row' . (empty($shoutbox['showmsg_down']) ? $_GET['row']-- : $_GET['row']++) . '"><td nowrap="nowrap" style="text-align:right" class="' . $shoutbox['printClass'] . '" valign="top">' .  $shout['poster'] . '</td><td style="text-align:left" class="' . $shoutbox['printClass'] . '" valign="top">' . $shout['message'] . '</td></tr>';

		echo (!$context['browser']['is_ie'] && !empty($shoutbox['showmsg_down']) ? '<tr id="shoutbox_msgs"></tr>' : '') . ']]></msgs>
	<count><![CDATA[' . count($context['shoutbox_echo']['msgs']) . ']]></count>';

		if (isset($context['shoutbox_echo']['new_msgs']))
			echo '
	<newmsgs />';
		if (isset($context['shoutbox_echo']['reset']))
			echo '
	<reset />';
	}

	// any error?
	if (isset($context['shoutbox_echo']['error']))
		echo '
	<error><![CDATA[' . $context['shoutbox_echo']['error'] . ']]></error>';

	echo '
</smf>';
}

function template_shoutbox_panel()
{
	global $context, $settings, $scripturl, $txt, $shoutbox;

	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=', $context['character_set'], '" />
	<title>', $context['page_title'], '</title>';

	if (isset($context['shoutbox_smf_2']))
	{
		echo '
	<link rel="stylesheet" type="text/css" href="' . $settings['theme_url'] . '/css/index.css" />', $context['html_headers'];

		// IE7 needs some fixes for styles.
		if ($context['browser']['is_ie7'])
			echo '
	<link rel="stylesheet" type="text/css" href="', $settings['default_theme_url'], '/css/ie7.css" />';
		// ..and IE6!
		elseif ($context['browser']['is_ie6'])
			echo '
	<link rel="stylesheet" type="text/css" href="', $settings['default_theme_url'], '/css/ie6.css" />';
		// Firefox - all versions - too!
		elseif ($context['browser']['is_firefox'])
			echo '
	<link rel="stylesheet" type="text/css" href="', $settings['default_theme_url'], '/css/ff.css" />';
	}
	else
		echo '
	<link rel="stylesheet" type="text/css" href="' . $settings['theme_url'] . '/style.css?fin11" />';

	if ($context['shoutbox']['can_moderate'])
		echo (isset($context['shoutbox_smf_2']) ? '
	<script language="JavaScript" type="text/javascript" src="' . $settings['default_theme_url'] . '/scripts/script.js?rc1"></script>' : '
	<script language="JavaScript" type="text/javascript" src="' . $settings['default_theme_url'] . '/script.js?fin11"></script>'), '
	<script language="JavaScript" type="text/javascript"><!-- // --><![CDATA[
		var smf_theme_url = "', $settings['theme_url'], '";
		var smf_images_url = "', $settings['images_url'], '";
		var smf_scripturl = "', $scripturl, '";
		var smf_iso_case_folding = ', $context['server']['iso_case_folding'] ? 'true' : 'false', ';
		var smf_charset = "', $context['character_set'], '";
	// ]]></script>
	<script language="JavaScript" type="text/javascript" src="', $settings['default_theme_url'], '/shoutbox_moderation.js"></script>
	<script language="JavaScript" type="text/javascript"><!-- // --><![CDATA[
		shoutbox.sc = "', $context['session_id'], '";
		shoutbox.lang.editmsg = "', $txt['sbm_28'], '";
		shoutbox.lang.tooshort = "', $txt['sb_12'], '";
		shoutbox.lang.toolong = "', $txt['sb_12b'], '";
		shoutbox.lang.outstyle = "', $txt['sbm_29'], '";
		shoutbox.lang.emptyusers = "', $txt['sbm_30'], '";
		shoutbox.lang.wreason = "', $txt['sbm_31'], '";
		shoutbox.lang.wdays = "', $txt['sbm_32'], '";
		shoutbox.maxlength = ' . $shoutbox['maxMsgLenght'] . ';
		shoutbox.minlength = ' . $shoutbox['minMsgLenght'] . ';
	// ]]></script>';

	echo '
</head>
<body style="padding:0;margin:0">
	<div id="mainframe" style="margin:0;width:100%">';

	// can't moderate !!
	if (!$context['shoutbox']['can_moderate'])
		echo '
	<div style="margin:20px">
		<table border="0" cellspacing="0" cellpadding="0" class="tborder" style="width:100%">
			<tr class="titlebg">
				<td style="padding:0.5ex 1ex">' . $txt['sbm_3'] . '</td>
			</tr>
			<tr class="windowbg">
				<td style="padding:2ex">
					' . $txt['sbm_2'] . '
				</td>
			</tr>
		</table>
	</div>';
	else
	{
		echo '
	<div style="margin:5px">
		<div class="tborder">';

		// navbar
		echo '
			<div class="windowbg smalltext" style="padding:0.5em 0.6em;text-align:right">
				<span style="float:left"><img id="shoutbox_status" src="' . $settings['default_images_url'] . '/loading.gif" border="0" style="visibility:visible" /></span>
				<b><a href="' . $scripturl . '?action=shoutbox">Return Shoutbox</a></b>' . ($context['shoutbox']['can_edit'] || $context['shoutbox']['can_delete'] ? '
				| <a href="javascript:;" onclick="Shoutbox_GetMsgs();">Messages</a>' : '') . ($context['shoutbox']['can_prune'] ? '
				| <a href="javascript:;" onclick="if (window.confirm(\'' . $txt['sbm_8'] . '\')) Shoutbox_PruneMsgs();">Clear messages</a>' : '') . ($context['shoutbox']['can_ban'] ? '
				| <a href="javascript:;" onclick="Shoutbox_GetUsers();">Banned users</a>
				| <a href="javascript:;" onclick="Shoutbox_BanUsers();">Ban users</a>' : '') . '
				| <a title="SMFPacks.com" target="_blank" href="http://www.smfpacks.com/">&copy;</a>
			</div>';

		// content to show
		echo '
			<div class="windowbg2" style="margin-top:1px;padding:0.4em 0">
				<div id="shoutbox_pageindex" style="margin:0 0.6em 0.4em 0.6em;padding-right:0.6em;text-align:right" class="smalltext">
					' . $txt['sbm_5'] . '
				</div>
				<div style="margin:0 0.6em;padding-right:0.6em;overflow:auto;height:' . ($shoutbox['height'] + 15) . 'px;max-height:' . ($shoutbox['height'] + 15) . 'px" id="shoutbox_content">
					
				</div>
			</div>';

		echo '
		</div>
	</div>';
	}

	echo '
	<script language="JavaScript" type="text/javascript"><!-- // --><![CDATA[
		Shoutbox_GetMsgs();
	// ]]></script>
	</div>
</body>
</html>';
}

function template_shoutbox_panel_getmsgs()
{
	global $context, $txt, $shoutbox;

	echo '<', '?xml version="1.0" encoding="', $context['character_set'], '"?', '>
<smf>
	<pageindex><![CDATA[' . $context['shoutbox']['page_index'] . ']]></pageindex>
	<msgs><![CDATA[';

	if (!empty($context['shoutbox']['msgs']))
	{
		echo '<table cellpadding="2" cellspacing="0" align="left">';

		foreach ($context['shoutbox']['msgs'] as $s)
			echo '<tr>' . (isset($s['moderation']) ? '<td class="' . $shoutbox['printClass'] . '" nowrap="nowrap" valign="top">' . $s['moderation'] . '</td>' : '') . '<td class="' . $shoutbox['printClass'] . '"  nowrap="nowrap" style="padding-left:6px;text-align:right" valign="top">' . $s['user'] . '</td><td class="' . $shoutbox['printClass'] . '"  valign="top" style="text-align:left">' . $s['msg'] . '</td></tr>';

		echo '</table>';
	}

	echo ']]></msgs>';

	if (isset($context['shoutbox']['msg']))
		echo '
	<msg><![CDATA[' . $context['shoutbox']['msg'] . ']]></msg>';

	if (isset($context['shoutbox']['limit']))
		echo '
	<limit><![CDATA[' . $context['shoutbox']['limit'] . ']]></limit>';

	echo '
</smf>';
}

function template_shoutbox_panel_listusers()
{
	global $context, $txt, $scripturl;

	echo '<', '?xml version="1.0" encoding="', $context['character_set'], '"?', '>
<smf>
	<pageindex><![CDATA[' . $txt['sbm_15'] . ']]></pageindex>
	<list><![CDATA[';

	if (!empty($context['shoutbox_echo']['list']))
	{
		echo '<table cellpadding="4" cellspacing="0" align="left">';

		foreach ($context['shoutbox_echo']['list'] as $s)
		{
			echo '<tr><td class="smalltext" nowrap="nowrap" valign="top">' . $s['moderation'] . '</td>';
			echo '<td class="smalltext" nowrap="nowrap" align="right" valign="top">' . $s['user'] . '</td>';
			echo '<td class="smalltext" valign="top">' . $s['reason'] . '<div style="padding-top:8px">' . $s['details'] . '</div></td></tr>';
		}

		echo '</table>';
	}
	// if empty ...
	else
		echo '<div class="smalltext" style="text-align:left">' . $txt['sbm_16'] . '</div>';

	echo ']]></list>';

	if (isset($context['shoutbox_echo']['msg']))
		echo '
	<msg><![CDATA[' . $context['shoutbox_echo']['msg'] . ']]></msg>';

	echo '
</smf>';
}

function template_shoutbox_panel_banusers()
{
	global $context, $txt, $scripturl, $settings;

	echo '<', '?xml version="1.0" encoding="', $context['character_set'], '"?', '>
<smf>
	<pageindex><![CDATA[' . $txt['sbm_17'] . ']]></pageindex>';

	if (isset($context['shoutbox_echo']['form']))
	{
		echo '
	<form><![CDATA[';

		echo '<table cellpadding="4" cellspacing="0" align="left">';
		echo '<tr><td class="smalltext" valign="middle" style="text-align:right"><b>' . $txt['sbm_22'] . ':</b></td><td valign="middle"><input type="text" style="width:250px" id="form_users" class="smalltext" /> <a href="' . $scripturl . '?action=findmember;input=form_users;quote;sesc=' . $context['session_id'] . '" onclick="return reqWin(this.href, 350, 400)" target="_blank"><img src="' . $settings['images_url'] . '/icons/assist.gif" alt="' . $txt['sbm_18'] . '" /></a></td></tr>';
		echo '<tr><td class="smalltext" valign="middle" style="text-align:right"><b>' . $txt['sbm_19'] . ':</b></td><td valign="middle"><input type="text" style="width:350px" id="form_reason" class="smalltext" /></td></tr>';
		echo '<tr><td class="smalltext" valign="middle" style="text-align:right"><b>' . $txt['sbm_13'] . ':</b><br />' .$txt['sbm_20'] . '</td><td valign="middle"><input type="text" style="width:50px" id="form_days" value="0" class="smalltext" /></td></tr>';
		echo '<tr><td></td><td align="left" style="padding-top:8px"><input type="button" value="' . $txt['sbm_21'] . '" class="smalltext" style="padding:2px" onclick="Shoutbox_SendUsers();" /></td></tr>';
		echo '</table>';

	echo ']]></form>';
	}

	if (isset($context['shoutbox_echo']['msg']))
		echo '
	<msg><![CDATA[' . $context['shoutbox_echo']['msg'] . ']]></msg>';

	echo '
</smf>';
}

?>